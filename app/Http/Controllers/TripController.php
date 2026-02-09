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
use Illuminate\Validation\ValidationException;

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
                ->whereIn('status', ['pending', 'approved', 'ongoing'])
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


    public function update(Request $request, Trip $trip)
    {
        try {
            // 1) Validasi dasar
            $validated = $request->validate([
                'car_id'          => ['required', 'integer', 'exists:cars,id'],
                'requester_name'  => ['nullable', 'string', 'max:255'],
                'destination'     => ['required', 'string', 'max:255'],

                'driver_required' => ['required', 'boolean'],
                'driver_id'       => ['nullable', 'integer', 'exists:driver,id', 'required_if:driver_required,1'],

                'status'          => ['required', Rule::in(['pending', 'approved', 'cancelled'])],

                'start_at'        => ['required', 'date'],
                'end_at'          => ['required', 'date', 'after:start_at'],

                'notes'           => ['nullable', 'string'],
                'notes_cancel'    => ['nullable', 'string'],
            ]);

            $driverRequired = (int) $validated['driver_required'];

            // 2) Validasi conditional: kalau butuh sopir, driver_id wajib
            if ($driverRequired === 1 && empty($validated['driver_id'])) {
                throw ValidationException::withMessages([
                    'driver_id' => 'Sopir wajib dipilih jika kebutuhan sopir = Dengan sopir.',
                ]);
            }

            // 3) Normalisasi: kalau tanpa sopir, paksa driver_id null
            $driverId = $driverRequired === 1 ? (int) $validated['driver_id'] : null;

            $startAt = Carbon::parse($validated['start_at']);
            $endAt   = Carbon::parse($validated['end_at']);

            DB::beginTransaction();

            // 4) Cek overlap jadwal kendaraan (exclude trip ini sendiri)
            $hasOverlap = Trip::query()
                ->where('car_id', $validated['car_id'])
                ->where('id', '!=', $trip->id)
                ->whereIn('status', ['pending', 'approved']) // yang dianggap mengunci
                ->where(function ($q) use ($startAt, $endAt) {
                    $q->where('start_at', '<', $endAt)
                        ->where('end_at', '>', $startAt);
                })
                ->exists();

            if ($hasOverlap) {
                DB::rollBack();
                return back()
                    ->withInput()
                    ->with('error', 'Jadwal bentrok: kendaraan sudah terpakai pada rentang waktu tersebut.');
            }

            // 5) Update data
            $trip->update([
                'car_id'         => (int) $validated['car_id'],
                'requester_name' => $validated['requester_name'] ?? null,
                'destination'    => $validated['destination'],

                'driver_required' => $driverRequired,
                'driver_id'      => $driverId,

                'status'         => $validated['status'],
                'notes'          => $validated['notes'] ?? null,
                'notes_cancel'   => $validated['notes_cancel'] ?? null,

                'start_at'       => $startAt,
                'end_at'         => $endAt,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.trips.index')
                ->with('success', 'Data peminjaman berhasil diperbarui.');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', 'Input tidak valid. Cek field yang wajib diisi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat update peminjaman. Coba lagi.');
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
                ->with('error', 'Gagal menghapus data peminjaman. Coba lagi.');
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
