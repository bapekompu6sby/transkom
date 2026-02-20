<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TripExportController extends Controller
{
    public function basic(Trip $trip)
    {
        abort_if($trip->status !== 'approved', 403);
        $trip->load(['car', 'driver', 'user']); // car cuma 1, sesuai kebutuhanmu

        $pdf = Pdf::loadView('admin.trips.pdf.basic', compact('trip'))
            ->setPaper('A4', 'portrait');

        $filename = 'Surat_Biasa_Trip_' . $trip->id . '.pdf';
        return $pdf->download($filename);
    }

    public function special(Trip $trip)
    {
        abort_if($trip->status !== 'approved', 403);

        $trip->load(['car', 'driver', 'user']);

        $pdf = Pdf::loadView('admin.trips.pdf.special', compact('trip'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Surat_Khusus_Trip_' . $trip->id . '.pdf');
    }


    public function exportRekap(Request $request)
    {
        $validated = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2000,2100'],
        ]);

        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];

        // Range 1 bulan
        $rangeStart = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $rangeEnd   = (clone $rangeStart)->endOfMonth()->endOfDay();

        // Ambil data
        $trips = Trip::query()
            ->where('status', 'approved')
            ->whereBetween('start_at', [$rangeStart, $rangeEnd]) // ganti kalau fieldmu beda
            ->with(['car', 'driver', 'user'])
            ->orderBy('start_at')
            ->get();

        abort_if($trips->isEmpty(), 404);

        // Bulan Indonesia (FULL di controller)
        $monthName = Carbon::create($tahun, $bulan, 1)
            ->locale('id')
            ->translatedFormat('F'); // "Februari"

        // Helper format tanggal: "11 Feb 2026 09:22"
        $fmt = fn($val) => $val
            ? Carbon::parse($val)->locale('id')->translatedFormat('d M Y H:i')
            : '';

        // Mapping + format tanggal "s/d" jadi STRING (FULL di controller)
        $items = $trips->map(function ($t) use ($fmt) {
            $start = $t->start_at ?? $t->created_at;
            $end   = $t->end_at ?? null; // kalau tidak ada, tetap null

            $tanggal = $fmt($start);
            if ($end) {
                $tanggal .= "\n" . 's/d ' . $fmt($end); // newline, nanti di view jadi <br>
            }

            return [
                'nama'    => $t->driver?->name ?? 'tanpa driver',
                'tujuan'  => $t->destination ?? '-',
                'nopol'   => $t->car?->plate_number ?? $t->car?->nopol ?? '-',
                'tanggal' => $tanggal, // SUDAH jadi string final
                'requester_name' => $t->requester_name ?? '-',
            ];
        })->values()->all();

        $pdf = Pdf::loadView('admin.trips.pdf.rekap-kendaraan-keluar', [
            'items'      => $items,
            'monthName'  => strtoupper($monthName), // jadi "FEBRUARI"
            'year'       => $tahun,
            'totalRows'  => 20,

            // tanda tangan juga full dari controller

            'signCity'   => 'Surabaya',
            'signDay'    => '.....',
            'signMonthName' => $monthName, // "Februari"
            'signYear'   => $tahun,
            'signName'   => 'Sunaryo',
            'signNip'    => 'NIP.19730417200812001',
        ])->setPaper('A4', 'portrait');

        $fileMonth = str_pad((string)$bulan, 2, '0', STR_PAD_LEFT);
        return $pdf->download("ceklist-kendaraan-keluar-{$tahun}-{$fileMonth}.pdf");
    }
}
