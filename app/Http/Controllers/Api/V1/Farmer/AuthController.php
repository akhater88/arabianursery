<?php

namespace App\Http\Controllers\Api\V1\Farmer;
use App\Models\Farm;
use App\Models\FarmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'password' => 'required',
        ]);

        $farmUser = FarmUser::where('mobile_number', $request->mobile_number)->first();

        if ($farmUser && Hash::check($request->password, $farmUser->password)) {
            $tokenResult = $farmUser->createToken('Farmer User Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'token' => $tokenResult->accessToken,
                "farmer_info" => $farmUser,
                'token_type' => 'Bearer',
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function farmRegister(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'farm_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . FarmUser::class],
            //'country_code' => ['required', 'string', 'max:5'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . FarmUser::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $farmUser = FarmUser::withTrashed()->where('mobile_number',$request->mobile_number)->first();

        if(!$farmUser){
            $farm = Farm::create([
                'name' => $request->farm_name,
            ]);

            FarmUser::create([
                'name' => $request->first_name.' '.$request->last_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'farm_id' => $farm->id,
                'country_code' => $request->country_code,
            ]);
        } else {
            if($farmUser->deleted_at != null ){
                $farmUser->restore();
            } else {
                $farm = Farm::create([
                    'name' => $request->farm_name,
                ]);
                $farmUser->farm_id = $farm->id;
            }
            $farmUser->name =  $request->first_name.' '.$request->last_name;
            $farmUser->email = $request->email;
            $farmUser->password = Hash::make($request->password);
            $farmUser->country_code =  $request->country_code;
            $farmUser->save();
        }

        return response()->json([
            'message' => 'Successful Created'
        ], 200);
    }

    public function updateFcmToken(Request $request){
        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return response()->json([
            'message' => 'Successful Updated'
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255','unique:' . FarmUser::class.',mobile_number,'.$user->id],
            'country_code' => ['required', 'string', 'max:5'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . FarmUser::class.',email,'.$user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_number = $request->mobile_number;
        $user->country_code = $request->country_code;
        $user->save();
        return response()->json([
            'message' => 'Successful Updated'
        ], 200);
    }

    public function changePassword(Request $request){
        $farmUser = $request->user();
        $request->validate([
            'confirm_password' => ['required'],
            'password' => ['required', 'same:confirm_password', Rules\Password::defaults()]
        ]);

        $farmUser->password = Hash::make($request->password);
        $farmUser->save();
        return response()->json([
            'message' => 'Successful Updated'
        ], 200);
    }

    public function removeAccount(Request $request){
        $farmUser = $request->user();
        $farmUser->delete();
    }
}
