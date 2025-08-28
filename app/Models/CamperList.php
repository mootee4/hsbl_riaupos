<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PlayerList;
use App\Models\AddData;

class CamperList extends Model
{
    protected $table = 'camper_list';

    protected $fillable = [
        'player_id',
        'season_id',
        'selected_by',
        'camper_status',
    ];

    // Relasi ke player_list
    public function player()
    {
        return $this->belongsTo(PlayerList::class, 'player_id', 'id');
    }

    // Relasi ke add_data untuk season_name
    public function season()
    {
        return $this->belongsTo(\App\Models\AddData::class, 'season_id', 'id');
    }

}
