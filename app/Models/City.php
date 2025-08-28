<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venue;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['city_name'];

    // Relasi ke School
    public function schools()
    {
        return $this->hasMany(School::class);
    }

    // Relasi ke Venue
    public function venues()
    {
        return $this->hasMany(Venue::class, 'city_id');
    }
}
