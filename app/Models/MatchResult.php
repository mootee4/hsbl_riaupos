<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    use HasFactory;

    protected $table = 'match_results';

    protected $fillable = [
        'match_date',
        'competition',
        'competition_type',
        'phase',
        'team1_id',
        'team2_id',
        'score_1',
        'score_2',
        'scoresheet',
    ];

    public function team1()
    {
        return $this->belongsTo(School::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(School::class, 'team2_id');
    }

    public function phaseData()
    {
        return $this->belongsTo(AddData::class, 'phase', 'phase');
    }

    public function competitionTypeData()
    {
        return $this->belongsTo(AddData::class, 'competition_type', 'competition_type');
    }
}
