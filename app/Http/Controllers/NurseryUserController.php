<?php

namespace App\Http\Controllers;

use App\Models\Nursery;
use App\Models\NurseryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use MatanYadaev\EloquentSpatial\Objects\Point;

class NurseryUserController extends Controller
{
    public function getCompleteRegistration(Request $request)
    {
        $user = $request->user('nursery_web');

        return view('auth.register', [
            'is_complete_action' => true,
            'nursery_user_name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function storeCompleteRegistration(Request $request)
    {
        $user = $request->user('nursery_web');

        $request->validate([
            'nursery_name' => ['required', 'string', 'max:255'],
            'nursery_user_name' => ['required', 'string', 'max:255'],
            'email' => is_null($user->email) ? ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . NurseryUser::class] : [],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . NurseryUser::class],
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

        $request->user()->update([
            'name' => $request->nursery_user_name,
            'email' => $user->email ?? $request->email,
            'country_code' => '+962',
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'nursery_id' => $nursery->id,
            'status' => NurseryUser::STATUS_ENABLED
        ]);

        return redirect()->route('dashboard');
    }
}
