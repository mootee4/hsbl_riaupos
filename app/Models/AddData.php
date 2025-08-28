<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddData extends Model
{
    protected $fillable = [
        'season_name',
        'series_name',
        'competition',
        'competition_type', // ✅ ini ditambahkan
        'phase'
    ];
}
