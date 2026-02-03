<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $historys = Trip::with(['car', 'driver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.history', compact('historys'));
    }
}
