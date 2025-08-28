<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'venue';
    protected $fillable = ['venue_name', 'city_id', 'location', 'layout'];

    // Venue.php (Model)
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
