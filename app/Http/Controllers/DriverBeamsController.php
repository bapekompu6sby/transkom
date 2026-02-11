<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\PushNotifications\PushNotifications;

class DriverBeamsController extends Controller
{
    public function auth(Request $request)
    {
        // Beams mengirim body: { user_id: 'driver:123' } biasanya
        $request->validate([
            'user_id' => ['required', 'string'],
        ]);

        $driver = Auth::guard('driver')->user();
        $expectedUserId = 'driver:' . (int) $driver->id;

        // Pastikan user_id yang diminta = user login sekarang (anti spoof)
        abort_unless($request->user_id === $expectedUserId, 403);

        $beamsClient = new \Pusher\PushNotifications\PushNotifications([
            'instanceId' => config('services.beams.instance_id'),
            'secretKey'  => config('services.beams.secret_key'),
        ]);

        // Generate token untuk userId tsb
        return response()->json(
            $beamsClient->generateToken($expectedUserId)
        );
    }
}
