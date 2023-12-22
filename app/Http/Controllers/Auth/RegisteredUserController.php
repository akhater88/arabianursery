<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Nursery;
use App\Models\NurseryUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use MatanYadaev\EloquentSpatial\Objects\Point;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'is_complete_action' => false
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nursery_name' => ['required', 'string', 'max:255'],
            'nursery_user_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . NurseryUser::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . NurseryUser::class],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-90,90'],
            'nursery_address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $nursery = Nursery::create([
            'name' => $request->nursery_name,
            'location' => new Point($request->lat, $request->lng),
            'address' => $request->nursery_address,
        ]);

        $user = NurseryUser::create([
            'name' => $request->nursery_user_name,
            'email' => $request->email,
            'country_code' => '+962',
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'nursery_id' => $nursery->id
        ]);

        event(new Registered($user));

        Auth::guard('nursery_web')->login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
