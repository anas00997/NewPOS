<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if ($request->previous_url) {
            session(['previous_url' => $request->previous_url]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $findUser = User::where('google_id', $user->id)->first();
            if ($findUser) {
                Auth::login($findUser);
            } else {
                $findUserEmail = User::where('email', $user->email)->first();
                if ($findUserEmail) {
                    $findUserEmail->update([
                        'google_id' => $user->id,
                    ]);
                    $userToLogin = $findUserEmail;
                } else {
                    $userToLogin = User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'google_id' => $user->id,
                        // Use a random bcrypt-hashed password; users should login via Google or reset password
                        'password' => bcrypt(Str::random(32)),
                        'username' => uniqid(),
                        'is_google_registered' => true
                    ]);
                }
                Auth::login($userToLogin);
            }

            if (auth()->user()->is_suspended == 1) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is temporarily suspended');
            }

            // Removed invalid controller instantiation and method call.
            // If you need to record a trusted device, inject a service or use a helper here.

            if (session('previous_url')) {
                $url = session('previous_url');
                session()->forget('previous_url');

                return redirect($url);
            }
            return redirect('/');
        } catch (Exception $e) {
            // return $e->getMessage();
            return back()->with('error', 'Something went wrong');
        }
    }
}
