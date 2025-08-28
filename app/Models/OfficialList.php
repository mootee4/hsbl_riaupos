<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficialList extends Model
{
    use HasFactory;
    protected $connection = 'mysql_event';

    protected $table = 'official_list';

    protected $primaryKey = 'official_id';

    protected $fillable = [
        'team_id',
        'nik',
        'name',
        'birth_date',
        'gender',
        'email',
        'phone',
        'school',
        'height',
        'weight',
        'tshirt_size',
        'shoes_size',
        'instagram',
        'tiktok',
        'formal_photo',
        'license_photo',
        'identity_card'
    ];

    // Relasi ke tim
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id');
    }
}
