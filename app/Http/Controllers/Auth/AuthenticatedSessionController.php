<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');;
            }

            if ($user->role === 'user') {
                return redirect()->route('user.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');;
            }

            // fallback (role tidak dikenal)
            Auth::logout();
            return redirect()->route('auth.login-user');
        }

        // 👇 Jika belum login, tampilkan halaman login
        return view('auth.login-user');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $loginAs = $request->login_as;

        if ($user->role !== $loginAs) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'login' => 'Anda tidak punya akses login di sini.',
            ]);
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->route('user.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');;
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
