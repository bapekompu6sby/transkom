<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'trips';

    protected $fillable = [
        'car_id',
        'nip',
        'trip_type',
        'user_id',
        'driver_required',
        'driver_id',
        'requester_name',
        'destination',
        'status',
        'notes',
        'notes_cancel',
        'start_at',
        'end_at',
        'requester_position',
        'organization_name',
        'purpose',
        'participant_count',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];


    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
