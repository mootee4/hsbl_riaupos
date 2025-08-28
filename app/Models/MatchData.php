<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchData extends Model
{
    protected $table = 'match_data';

    protected $fillable = [
        'upload_date',
        'main_title',
        'caption',
        'layout_image',
        'status',
        'series_name',    // tambahkan ini
    ];
}

