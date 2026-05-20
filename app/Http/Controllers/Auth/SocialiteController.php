<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Activitylog\Facades\LogActivity;

class SocialiteController extends Controller
{
    /**
     * Redirect to the provider's authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the provider.
     */
    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Unable to authenticate with ' . ucfirst($provider) . '. Please try again.');
        }

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            // Check if user exists with same email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link social account to existing user
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            } elseif (config('rbac.socialite.auto_register')) {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'email_verified_at' => now(),
                ]);

                // Assign default role
                $defaultRole = config('rbac.socialite.default_role', 'user');
                $role = Role::where('slug', $defaultRole)->first();
                if ($role) {
                    $user->assignRole($defaultRole);
                }

                activity('auth')
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties(['provider' => $provider])
                    ->log('User registered via ' . $provider);
            } else {
                return redirect()->route('login')
                    ->with('error', 'Auto-registration is disabled. Please register first.');
            }
        } else {
            // Update avatar
            $user->update([
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['provider' => $provider])
            ->log('User logged in via ' . $provider);

        return redirect()->intended('/dashboard');
    }

    /**
     * Validate that the provider is supported.
     */
    protected function validateProvider(string $provider): void
    {
        $providers = config('rbac.socialite.providers', []);

        if (!in_array($provider, $providers)) {
            abort(404, 'Social provider not supported.');
        }
    }
}
