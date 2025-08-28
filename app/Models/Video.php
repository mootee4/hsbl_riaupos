<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'media_videos'; // ✅ Wajib agar Laravel tahu nama tabel kustom

    protected $fillable = [
        'video_code', 'title', 'thumbnail', 'description',
        'youtube_link', 'slug', 'type', 'status'
    ];
}
