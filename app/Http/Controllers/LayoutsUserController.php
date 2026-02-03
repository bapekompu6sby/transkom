<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class LayoutsUserController extends Controller
{
    public function dashboard()
    {
        $cars = Car::query()->latest()->get();
        return view('user.dashboard', compact('cars'));
    }
}
