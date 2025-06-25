<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
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

            if ($user) {
                // Jika user sudah ada, langsung login
                Auth::login($user);
            } else {
                // Jika user belum ada, buat user baru
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()), // Buat password acak
                    'role' => 'pending', // Atau 'user', tergantung alur aplikasi Anda
                    'google_id' => $googleUser->getId(),
                ]);

                Auth::login($newUser);

                // Notify all admins about the new user registration
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new NewUserRegisteredNotification($newUser));
            }

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }
}
