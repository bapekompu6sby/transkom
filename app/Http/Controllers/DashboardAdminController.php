<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $cars = Car::all();

        return view('admin.dashboard', compact('cars'));
    }
}
