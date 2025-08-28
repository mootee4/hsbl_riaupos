<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data untuk admin
        User::create([
            'name' => 'Admin 1 HSBL',
            'email' => 'admin1RPHSBL@sbl.id',
            'password' => Hash::make('@adminHSBL_RIAUPOS(1)First_2025'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin 2 HSBL',
            'email' => 'admin2RPHSBL@sbl.id',
            'password' => Hash::make('@adminHSBL_RIAUPOSSecond(2)_2025'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin 3 HSBL',
            'email' => 'admin3RPHSBL@sbl.id',
            'password' => Hash::make('@adminHSBL_RIAUPOSThird_2025(3)'),
            'role' => 'admin',
        ]);
    }
}
