<?php

namespace App\Http\Controllers\GoogleController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
           

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'siswa',
                    'password' => bcrypt('default_password'),
                    'avatar' => $googleUser->getAvatar(), // pastikan ini ada
                ]);
            } else {
                // Kalau user sudah ada, bisa update avatar juga
                $user->update([
                    'avatar' => $googleUser->getAvatar(), // pastikan ini dieksekusi
                ]);
            }

            Auth::login($user);

            return redirect()->route('siswa_interface.login_siswa');
        } catch (\Exception $e) {
            // Debug log error-nya

            return redirect()->route('login.siswa.form')->with('error', 'Gagal login dengan Google.');
        }
    }
}
