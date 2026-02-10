<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Pusher\PushNotifications\PushNotifications;


class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['car', 'driver', 'user'])
            ->orderByDesc('id')
            ->get();

        $drivers = Driver::where('status', 'active')->get();
        $lastSeenId = (int) Trip::max('id');

        return view('admin.trips.index', compact('trips', 'drivers', 'lastSeenId'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id'          => ['required', 'integer', 'exists:cars,id'],
            'driver_required' => ['required', 'boolean'],
            'destination'     => ['required', 'string', 'max:255'],
            'start_at'        => ['required', 'date_format:Y-m-d\TH:i'],
            'end_at'          => ['required', 'date_format:Y-m-d\TH:i', 'after:start_at'],
            'notes'           => ['nullable', 'string'],
        ]);

        $userId = Auth::id();
        $requesterName = Auth::user()->name;

        $driverRequired = (int) $validated['driver_required'];

        // parse datetime-local dengan format yang tepat
        $startAt = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_at']);
        $endAt   = Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_at']);

        DB::beginTransaction();

        try {


            // ambil list jadwal yang overlap (bukan cuma exists)
            $overlaps = Trip::query()
                ->where('car_id', $validated['car_id'])
                ->whereIn('status', ['approved'])
                ->where(function ($q) use ($startAt, $endAt) {
                    $q->where('start_at', '<', $endAt)
                        ->where('end_at', '>', $startAt);
                })
                ->orderBy('start_at')
                ->get(['id', 'start_at', 'end_at', 'status']);

            if ($overlaps->isNotEmpty()) {
                DB::rollBack();

                // format jadwal tabrakan
                $fmt = fn($dt) => Carbon::parse($dt)->translatedFormat('d M Y, H:i');

                $list = $overlaps->map(function ($t) use ($fmt) {
                    return $fmt($t->start_at) . ' s/d ' . $fmt($t->end_at);
                })->implode(' | ');

                return redirect()
                    ->route('user.dashboard')
                    ->withInput()
                    ->with('failed', "Kendaraan sudah terjadwal pada rentang waktu ini: {$list}");
            }


            Trip::create([
                'car_id'          => $validated['car_id'],
                'user_id'         => $userId,
                'driver_required' => $driverRequired,
                'driver_id'       => null,
                'requester_name'  => $requesterName,
                'destination'     => $validated['destination'],
                'status'          => 'pending',
                'notes'           => $validated['notes'] ?? null,
                'notes_cancel'    => null,
                'start_at'        => $startAt,
                'end_at'          => $endAt,
            ]);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect()->route('user.dashboard')
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat menyimpan pengajuan. Coba lagi atau hubungi admin.');
        }
    }


    public function update(Request $request, Trip $trip): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'car_id'          => ['required', 'integer', 'exists:cars,id'],
                'requester_name'  => ['nullable', 'string', 'max:255'],
                'destination'     => ['required', 'string', 'max:255'],

                'driver_required' => ['required', 'boolean'],
                'driver_id'       => ['nullable', 'integer', 'exists:drivers,id', 'required_if:driver_required,1'],

                'status'          => ['required', Rule::in(['pending', 'approved', 'cancelled'])],

                'start_at'        => ['required', 'date'],
                'end_at'          => ['required', 'date', 'after:start_at'],

                'notes'           => ['nullable', 'string'],
                'notes_cancel'    => ['nullable', 'string'],
            ]);

            $driverRequired = (int) $validated['driver_required'];

            if ($driverRequired === 1 && empty($validated['driver_id'])) {
                throw ValidationException::withMessages([
                    'driver_id' => 'Sopir wajib dipilih jika kebutuhan sopir = Dengan sopir.',
                ]);
            }

            $driverId = $driverRequired === 1 ? (int) $validated['driver_id'] : null;

            $startAt = Carbon::parse($validated['start_at']);
            $endAt   = Carbon::parse($validated['end_at']);

            $oldDriverId = (int) ($trip->driver_id ?? 0);
            $oldStatus   = $trip->status;

            DB::beginTransaction();

            $isLockingStatus = in_array($validated['status'], ['approved'], true);

            if ($isLockingStatus) {
                $overlapTrip = Trip::query()
                    ->where('car_id', $validated['car_id'])
                    ->where('id', '!=', $trip->id)
                    ->whereIn('status', ['approved'])
                    ->where(function ($q) use ($startAt, $endAt) {
                        $q->where('start_at', '<', $endAt)
                            ->where('end_at', '>', $startAt);
                    })
                    ->first();

                if ($overlapTrip) {
                    DB::rollBack();

                    return back()
                        ->withInput()
                        ->with(
                            'failed',
                            'Jadwal bentrok pada waktu ' .
                                $overlapTrip->start_at->format('d M Y H:i') . ' - ' .
                                $overlapTrip->end_at->format('d M Y H:i')
                        );
                }
            }

            $trip->update([
                'car_id'          => (int) $validated['car_id'],
                'requester_name'  => $validated['requester_name'] ?? null,
                'destination'     => $validated['destination'],

                'driver_required' => $driverRequired,
                'driver_id'       => $driverId,

                'status'          => $validated['status'],
                'notes'           => $validated['notes'] ?? null,
                'notes_cancel'    => $validated['notes_cancel'] ?? null,

                'start_at'        => $startAt,
                'end_at'          => $endAt,
            ]);

            // === Tentukan apakah perlu notif (setelah update, karena pakai wasChanged) ===
            $driverJustAssigned = !empty($driverId) && ($oldDriverId !== (int) $driverId);
            $statusJustApproved = ($validated['status'] === 'approved' && $oldStatus !== 'approved');

            $importantChanged =
                $trip->wasChanged('destination') ||
                $trip->wasChanged('start_at') ||
                $trip->wasChanged('end_at') ||
                $trip->wasChanged('car_id');

            $shouldNotify =
                !empty($driverId) &&
                (
                    $driverJustAssigned ||
                    $statusJustApproved ||
                    // kalau status sudah approved, tapi ada revisi penting → kasih notif
                    ($validated['status'] === 'approved' && $importantChanged)
                );

            if ($shouldNotify) {
                DB::afterCommit(function () use ($trip, $driverId, $driverJustAssigned, $statusJustApproved, $importantChanged) {
                    try {
                        $beams = new \Pusher\PushNotifications\PushNotifications([
                            'instanceId' => config('services.beams.instance_id'),
                            'secretKey'  => config('services.beams.secret_key'),
                        ]);

                        $userId = 'driver:' . (int) $driverId;

                        if ($driverJustAssigned) {
                            $title = 'Penugasan Baru 🚗';
                        } elseif ($statusJustApproved) {
                            $title = 'Trip Di-approve ✅';
                        } elseif ($importantChanged) {
                            $title = 'Perubahan Jadwal/Detail Trip ✏️';
                        } else {
                            $title = 'Update Trip';
                        }

                        $body = "Trip ke {$trip->destination} (" .
                            $trip->start_at->format('d M Y H:i') . " - " .
                            $trip->end_at->format('d M Y H:i') . ")";

                        $beams->publishToUsers([$userId], [
                            'web' => [
                                'notification' => [
                                    'title' => $title,
                                    'body'  => $body,
                                    'deep_link' => url('/driver/dashboard'),
                                ],
                            ],
                        ]);
                    } catch (\Throwable $e) {
                        // notif gagal? gapapa. jangan ganggu success response.
                        report($e);
                    }
                });
            }

            DB::commit();

            return redirect()
                ->route('admin.trips.index')
                ->with('success', 'Data peminjaman berhasil diperbarui.');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('failed', 'Input tidak valid. Cek field yang wajib diisi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat update peminjaman. Coba lagi.');
        }
    }


    public function destroy(Trip $trip)
    {
        try {
            $trip->delete();

            return redirect()
                ->route('admin.trips.index')
                ->with('success', 'Data peminjaman berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.trips.index')
                ->with('failed', 'Gagal menghapus data peminjaman. Coba lagi.');
        }
    }

    public function checkNew(Request $request)
    {
        $lastSeenId = (int) $request->query('last_seen_id', 0);

        $latestId = (int) Trip::max('id');
        $hasNew = $latestId > $lastSeenId; // lebih cepat daripada exists()

        return response()
            ->json([
                'new' => $hasNew,
                'latest_id' => $latestId,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
