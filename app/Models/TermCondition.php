<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TermCondition extends Model
{
    protected $table = 'term_conditions';

    protected $fillable = [
        'event_name',
        'year',
        'file_path',
    ];

    /**
     * URL untuk mengunduh file
     */
    public function getDownloadUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
