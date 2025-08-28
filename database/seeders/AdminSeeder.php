<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin 1 HSBL',
                'email' => 'Admin1RPHSBL@sbl.id',
                'password' => '@adminHSBL_RIAUPOS(1)First_2025',
            ],
            [
                'name' => 'Admin 2 HSBL',
                'email' => 'admin2RPHSBL@sbl.id',
                'password' => '@adminHSBL_RIAUPOSSecond(2)_2025',
            ],
            [
                'name' => 'Admin 3 HSBL',
                'email' => 'admin3RPHSBL@sbl.id',
                'password' => '@adminHSBL_RIAUPOSThird_2025(3)',
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'role' => 'admin',
                ]
            );
        }
    }
}
