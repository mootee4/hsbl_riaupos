<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'sponsor_name',
        'category',
        'logo',
        'sponsors_web',
        'sponsor_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sponsor) {
            // Jika sponsor_code belum diisi, generate otomatis
            if (empty($sponsor->sponsor_code)) {
                // Ambil sponsor terakhir berdasarkan id descending
                $lastSponsor = Sponsor::orderBy('id', 'desc')->first();

                if (!$lastSponsor) {
                    $newNumber = 1;
                } else {
                    $lastCode = $lastSponsor->sponsor_code;
                    // Ambil angka dari kode terakhir, misal "SP-005" jadi 5
                    $lastNumber = (int) str_replace('SP-', '', $lastCode);
                    $newNumber = $lastNumber + 1;
                }

                // Format kode baru dengan 3 digit angka
                $sponsor->sponsor_code = 'SP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
