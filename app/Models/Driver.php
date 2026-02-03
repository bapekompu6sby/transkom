<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    protected $fillable = [
        'name',
        'phone_number',
        'email',
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
