<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Driver extends Authenticatable
{
    protected $table = 'drivers';
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'status',
        'notes',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
