<?php

namespace App\Http\Controllers;

use App\Models\FarmUser;
use Illuminate\Http\Request;

class FarmUserController extends Controller
{
    public function search(Request $request)
    {
        $farm_users_query = FarmUser::query()->limit(7);

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
            'farm_user_mobile_number' => ['required', 'string', 'max:255', 'unique:' . FarmUser::class . ',mobile_number'],
        ]);

        return $request->user()->addedFarmUsers()->create([
            'name' => $request->farm_user_name,
            'mobile_number' => $request->farm_user_mobile_number
        ]);
    }
}
