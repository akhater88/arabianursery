<?php

namespace App\Http\Controllers\Api\V1\Farmer;
use App\Models\FarmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'token_type' => 'Bearer',
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
