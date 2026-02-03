<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'cars';

    protected $fillable = [
        'name',
        'image_url',
        'year',
        'color',
        'plate_number',
        'nup',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
