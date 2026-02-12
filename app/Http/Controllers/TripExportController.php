<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Barryvdh\DomPDF\Facade\Pdf;

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
}
