<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    // login
    public function redirect($provider)
    {
        // redirect to the provider's authentication page
        return Socialite::driver($provider)->redirect();
    }

    // callback
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // check if the user is already registered, if not create a new user
            $user = $this->findOrCreateUser($socialUser, $provider);
            Auth::login($user, true);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Login Failed: ' . $e->getMessage());
        }
    }

    // find or create user
    private function findOrCreateUser($socialUser, $provider)
    {
        // check if user already exists in database
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            return $socialAccount->user;
        }

        // create a new user
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => null, // no need for social login
                'email_verified_at' => Carbon::now(),
            ]);
        }


        // create social account relationship
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
            'expired_at' => Carbon::now()->addSeconds($socialUser->expiresIn),
        ]);
        return $user;
    }
}
