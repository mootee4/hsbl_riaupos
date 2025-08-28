<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $table = 'awards'; // pastikan nama tabel cocok

    protected $fillable = [
        'award_type',
        'category',
    ];

    public $timestamps = false; // karena kamu nggak pakai created_at & updated_at
}
