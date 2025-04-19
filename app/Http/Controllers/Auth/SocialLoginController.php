<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SocialAccount;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialLoginController extends Controller
{
    /**
     * Redirect to provider's authentication page
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     */
    public function callback(string $provider): RedirectResponse
    {

        try {
            $socialUser = Socialite::driver($provider)->user();

            if (Auth::check()) {
                return $this->linkSocialAccount(Auth::user(), $socialUser, $provider);
            }

            $user = $this->findOrCreateUser($socialUser, $provider);
            Auth::login($user, true);

            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            Log::error('Social login failed: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'social' => 'Login failed. Please try another method.'
            ]);
        }
    }

    /**
     * Delete social account
     */
    public function destroy(SocialAccount $socialAccount): RedirectResponse
    {
        if ($socialAccount->is_primary) {
            return back()->withErrors([
                'social' => 'Cannot unlink primary login method'
            ]);
        }

        $socialAccount->delete();
        return back()->with('status', 'Account unlinked successfully');
    }

    /**
     * Find or create user
     */
    private function findOrCreateUser($socialUser, string $provider): User
    {
        // Check for existing social account
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            return $socialAccount->user;
        }

        // Find user by email or create new
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => null,
                'email_verified_at' => now(),
                'avatar' => $this->getAvatarUrl($socialUser),
            ]);
        }

        // Create social account (mark as primary if first social login)
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
            'expired_at' => Carbon::now()->addSeconds($socialUser->expiresIn),
            'is_primary' => !$user->socialAccounts()->exists(),
        ]);

        return $user;
    }

    /**
     * Link additional social account
     */
    private function linkSocialAccount(User $user, $socialUser, string $provider): RedirectResponse
    {
        // Check if already linked to any user
        $existingAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($existingAccount) {
            if ($existingAccount->user_id !== $user->id) {
                return back()->withErrors([
                    'social' => 'This account is already linked to another user'
                ]);
            }
            return redirect()->back()->with('info', 'Account already linked');
        }

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
            'expired_at' => Carbon::now()->addSeconds($socialUser->expiresIn),
            'is_primary' => false,
        ]);

        return redirect()->back()->with('status', 'Account linked successfully');
    }

    /**
     * Get avatar URL from social provider
     */
    private function getAvatarUrl($socialUser): ?string
    {
        try {
            return $socialUser->getAvatar() ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
