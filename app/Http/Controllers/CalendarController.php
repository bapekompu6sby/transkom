<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

// Ganti sesuai model kamu
use App\Models\Trip;

class CalendarController extends Controller
{
    public function events(Request $request)
    {
        $request->validate([
            'start' => ['required'],
            'end'   => ['required'],
        ]);

        // fix timezone + encoding (opsional hardening)
        $rawStart = str_replace(' ', '+', $request->query('start'));
        $rawEnd   = str_replace(' ', '+', $request->query('end'));

        $start = Carbon::parse($rawStart);
        $end   = Carbon::parse($rawEnd);

        $trips = Trip::where('status', 'approved')
            ->with(['car:id,name']) // ambil nama mobil
            ->get();

        return response()->json(
            $trips->map(function ($t) {
                $s = Carbon::parse($t->start_at);
                $e = Carbon::parse($t->end_at);

                $time = $s->format('H:i') . '–' . $e->format('H:i');
                $carName = $t->car?->name ?? ('Car #' . $t->car_id);
                $req = $t->requester_name ?? '-';
                $dest = $t->destination ?? '-';

                return [
                    'id' => $t->id,
                    // ✅ title sesuai format yang kamu mau
                    'title' => "{$carName}-{$dest}",
                    'start' => $s->toIso8601String(),
                    'end'   => $e->toIso8601String(),

                    // opsional: kalau mau title aja (tanpa jam built-in)
                    // kalau kamu mau jam tampil otomatis, bisa set displayEventTime true dan title tanpa jam
                    'extendedProps' => [
                        'car_name' => $carName,
                        'requester_name' => $req,
                        'destination' => $dest,
                    ],
                ];
            })->values()
        );
    }
}
