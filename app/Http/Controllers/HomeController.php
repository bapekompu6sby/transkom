<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('dashboard'); // belum login
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'user') {
            return redirect()->route('user.dashboard');
        }

        // fallback kalau role tidak dikenali
        return abort(403);
    }
}
