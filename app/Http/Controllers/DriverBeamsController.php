<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\PushNotifications\PushNotifications;

class DriverBeamsController extends Controller
{
    public function auth(Request $request)
    {
        $driver = $request->user('driver');

        $beams = new PushNotifications([
            'instanceId' => config('services.beams.instance_id'),
            'secretKey'  => config('services.beams.secret_key'),
        ]);

        return response()->json(
            $beams->generateToken('driver:' . $driver->id)
        );
    }
}
