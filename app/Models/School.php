<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['school_name', 'city_id', 'category_name', 'type'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}