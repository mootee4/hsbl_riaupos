<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images'; // nama tabel di database, biasanya jamak

    protected $fillable = [
        'title', 
        'path', 
        'published', 
        'created_at', 
        'updated_at'
    ];
}
