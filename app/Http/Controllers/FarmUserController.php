<?php

namespace App\Http\Controllers;

use App\Models\FarmUser;
use App\Models\Nursery;
use Illuminate\Http\Request;

class FarmUserController extends Controller
{
    public function search(Request $request)
    {

        $nursery   = Nursery::find($request->user()->nursery_id);
        $farm_users_query = $nursery->farmUsers();
        if ($request->q) {
            $farm_users_query->where('name', 'like', "%{$request->q}%")
                ->orWhere('mobile_number', 'like', "%{$request->q}%");
        }

        return [
            'results' => $farm_users_query->get()->map(fn($farm_user) => [
                'id' => $farm_user->id,
                'text' => "{$farm_user->name} ({$farm_user->mobile_number})"
            ])];
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'farm_user_name' => ['required', 'string', 'max:255'],
            'farm_user_mobile_number' => ['required', 'string', 'max:255'],
        ]);

        $farmUser = FarmUser::where('mobile_number',$request->farm_user_mobile_number)->first();

        if(!$farmUser){
            $farmUser = $request->user()->addedFarmUsers()->create([
                'name' => $request->farm_user_name,
                'mobile_number' => $request->farm_user_mobile_number
            ]);
        }

        $nursery = Nursery::find($request->user()->nursery_id);
        $nursery->farmUsers()->attach($farmUser);

        return $farmUser;
    }
}
