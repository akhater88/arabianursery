<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NurseryUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $social_user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            // message for unsuccessful signing
            return redirect()->route('login');
        }

        $user = NurseryUser::where('provider', $provider)->where('provider_id', $social_user->getId())->first();

        if(!$user && $social_user->getEmail() && NurseryUser::where('email', $social_user->getEmail())->exists()){
            // message for already exiting email
            return redirect()->route('login');
        }

        if (!$user) {
            $user = NurseryUser::create([
                'name' => $social_user->getName(),
                'email' => $social_user->getEmail() ?? null,
                'status' => NurseryUser::STATUS_INCOMPLETE,
                'provider' => $provider,
                'provider_id' => $social_user->getId(),
            ]);
        }

        Auth::guard('nursery_web')->login($user);

        request()->session()->regenerate();

        if ($user->isInCompleted()) {
            return redirect()->route('nursery.create-complete-registration');
        }

        return redirect()->intended(route('dashboard'));
    }
}
