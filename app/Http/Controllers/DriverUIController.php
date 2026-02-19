<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DriverUIController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-driver');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'login' => ['required', 'string'],
    //         'password' => ['required', 'string'],
    //     ]);

    //     $loginInput = $request->input('login');
    //     $password   = $request->input('password');

    //     // Deteksi apakah input email atau name
    //     $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
    //         ? 'email'
    //         : 'name';

    //     $credentials = [
    //         $field   => $loginInput,
    //         'password' => $password,
    //     ];

    //     if (auth()->guard('driver')->attempt($credentials)) {
    //         $request->session()->regenerate();

    //         return redirect()->intended('/driver/dashboard');
    //     }

    //     return back()->withErrors([
    //         'login' => 'Email/Nama atau password salah.',
    //     ])->onlyInput('login');
    // }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
        ]);

        $loginInput = $request->input('login');

        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        $driver = \App\Models\Driver::where($field, $loginInput)->first();

        if ($driver) {
            auth()->guard('driver')->login($driver);

            $request->session()->regenerate();

            return redirect()->intended('/driver/dashboard');
        }

        return back()->withErrors([
            'login' => 'Driver tidak ditemukan.',
        ])->onlyInput('login');
    }


    public function dashboard()
    {
        $driver = Auth::guard('driver')->user();
        $now = now(); // Laravel helper, udah Carbon

        $trips = Trip::with(['car', 'user', 'driver'])
            ->where('driver_id', $driver->id)
            ->where('status', 'approved')
            ->where('end_at', '>=', $now)   // <- penting: belum berakhir
            ->orderBy('start_at')
            ->get();

        return view('driver.dashboard', compact('driver', 'trips', 'now'));
    }
}
