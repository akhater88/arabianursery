<?php

namespace App\Http\Controllers;

use App\Models\AgriculturalSupplyStoreUser;
use Illuminate\Http\Request;

class AgriculturalSupplyStoreUserController extends Controller
{
    public function search(Request $request)
    {
        $agricultural_supply_store_users_query = AgriculturalSupplyStoreUser::query()->limit(7);

        if ($request->q) {
            $agricultural_supply_store_users_query->where('name', 'like', "%{$request->q}%")
                ->orWhere('mobile_number', 'like', "%{$request->q}%");
        }

        return [
            'results' => $agricultural_supply_store_users_query->get()->map(fn($agricultural_supply_store_user) => [
                'id' => $agricultural_supply_store_user->id,
                'text' => $agricultural_supply_store_user->optionName
            ])];
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'agricultural_supply_store_user_name' => ['required', 'string', 'max:255'],
            'agricultural_supply_store_user_mobile_number' => ['required', 'string', 'max:255', 'unique:' . AgriculturalSupplyStoreUser::class . ',mobile_number'],
        ]);

        return $request->user()->addedAgriculturalSupplyStoreUsers()->create([
            'name' => $request->agricultural_supply_store_user_name,
            'mobile_number' => $request->agricultural_supply_store_user_mobile_number
        ]);
    }
}
