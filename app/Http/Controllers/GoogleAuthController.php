<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->getId())->first();

            if (! $user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if (! $user) {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'role' => 'user',
                        'password' => null,
                    ]);
                } else {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Throwable $exception) {
            Log::error('Google sign-in failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            $message = 'Login Google gagal. Periksa konfigurasi OAuth dan coba lagi.';

            if ($exception instanceof ClientException && $exception->getResponse()) {
                $googleError = json_decode((string) $exception->getResponse()->getBody(), true);

                if (($googleError['error'] ?? null) === 'invalid_client') {
                    $message = 'Client secret Google OAuth tidak valid. Perbarui kredensial Google OAuth di file .env.';
                }
            }

            return redirect()->route('login')->withErrors([
                'email' => $message,
            ]);
        }
    }
}
