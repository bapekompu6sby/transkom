<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Trip;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    // public function index()
    // {
    //     $cars = Car::all();
    //     $drivers = Driver::where('status', 'active')->get();

    //     $now = Carbon::now();

    //     $trips = Trip::with(['car', 'driver', 'user'])
    //         ->where('end_at', '>=', $now)
    //         ->orderBy('start_at')
    //         ->get();
    //     return view('admin.dashboard', compact('cars', 'drivers', 'trips'));
    // }


    public function index(Request $request)
    {
        // ====== DATA DASHBOARD YANG SUDAH ADA ======
        $cars = Car::all();
        $drivers = Driver::where('status', 'active')->get();

        $now = Carbon::now();
        $trips = Trip::with(['car', 'driver', 'user'])
            ->where('end_at', '>=', $now)
            ->orderBy('start_at')
            ->get();

        // ====== YEAR OPTIONS (ambil dari trips) ======
        $years = Trip::query()
            ->selectRaw('YEAR(start_at) as y')
            ->distinct()
            ->orderByDesc('y')
            ->pluck('y');

        // default kalau belum ada data trips sama sekali
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        // ====== PIVOT YEAR ======
        $pivotYear = (int) $request->get('pivot_year', now()->year);

        // ====== REKAP KENDARAAN PER BULAN (HITUNG JUMLAH TRIP) ======
        $rekapKendaraan = DB::table('cars as c')
            ->leftJoin('trips as t', function ($join) use ($pivotYear) {
                $join->on('c.id', '=', 't.car_id')
                    ->whereYear('t.start_at', '=', $pivotYear)
                    ->whereIn('t.status', ['approved', 'ongoing', 'done']);
                // silakan sesuaikan status yang dihitung
            })
            ->selectRaw("
            c.id,
            c.name as kendaraan,
            c.plate_number as plat,

            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=1  THEN 1 ELSE 0 END),0) as jan,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=2  THEN 1 ELSE 0 END),0) as feb,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=3  THEN 1 ELSE 0 END),0) as mar,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=4  THEN 1 ELSE 0 END),0) as apr,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=5  THEN 1 ELSE 0 END),0) as mei,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=6  THEN 1 ELSE 0 END),0) as jun,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=7  THEN 1 ELSE 0 END),0) as jul,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=8  THEN 1 ELSE 0 END),0) as agu,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=9  THEN 1 ELSE 0 END),0) as sep,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=10 THEN 1 ELSE 0 END),0) as okt,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=11 THEN 1 ELSE 0 END),0) as nov,
            COALESCE(SUM(CASE WHEN MONTH(t.start_at)=12 THEN 1 ELSE 0 END),0) as des,

            COALESCE(COUNT(t.id),0) as total
        ")
            ->groupBy('c.id', 'c.name', 'c.plate_number')
            ->orderByDesc('total')     // kendaraan paling sering dipinjam di atas
            ->orderBy('c.name')
            ->get();

        // ====== AJAX: kirim partial saja ======
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.pivot-cars', [
                    'rekapKendaraan' => $rekapKendaraan,
                    'pivotYear' => $pivotYear,
                ])->render()
            ]);
        }

        // ====== RETURN VIEW DASHBOARD ======
        return view('admin.dashboard', compact(
            'cars',
            'drivers',
            'trips',
            'years',
            'pivotYear',
            'rekapKendaraan'
        ));
    }
}
