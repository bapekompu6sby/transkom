<?php

namespace App\Http\Controllers;

use App\Models\Car;

class LayoutsUserController extends Controller
{
    public function dashboard()
    {
        $cars = Car::where('status', 'available')->latest()->get();
        return view('user.dashboard', compact('cars'));
    }
}
