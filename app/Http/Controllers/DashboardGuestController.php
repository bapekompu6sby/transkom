<?php

namespace App\Http\Controllers;

use App\Models\Car;

class DashboardGuestController extends Controller
{
    public function index()
    {
        $cars = Car::where('status', 'available')->get();
        return view('dashboard', compact('cars'));
    }
}
