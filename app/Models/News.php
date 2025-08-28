<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'media_news';

    protected $fillable = [
        'series',
        'title',
        'posted_by',
        'image',
        'content',
        'status',      // ✅ Tambahkan agar bisa disimpan dari form
        // 'news_code' tidak dimasukkan agar hanya diisi otomatis
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate automatic news_code jika belum diisi.
     */
    protected static function booted()
    {
        static::creating(function ($news) {
            if (empty($news->news_code)) {
                $last = self::latest('id')->first();
                $num  = 1;

                if ($last && preg_match('/NW-(\d{3})/', $last->news_code, $matches)) {
                    $num = intval($matches[1]) + 1;
                }

                $news->news_code = sprintf('NW-%03d', $num);
            }
        });
    }
}
