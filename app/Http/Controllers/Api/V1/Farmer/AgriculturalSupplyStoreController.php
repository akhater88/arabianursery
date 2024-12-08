<?php

namespace App\Http\Controllers\Api\V1\Farmer;

use App\Models\AgriculturalSupplyStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AgriculturalSupplyStoreController extends Controller
{
    /**
     * Retrieve all supply stores.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all supply stores with pagination (optional)
        $stores = AgriculturalSupplyStore::select(
            'id',
            'name',
            'description',
            'image_path',
            'address',
            DB::raw('ST_X(location) as latitude'), // Extract latitude
            DB::raw('ST_Y(location) as longitude') // Extract longitude
        )->get();

        // Transform the location field to a readable format
//        $stores->transform(function ($store) {
//            $store->location = [
//                'latitude' => $store->location->getLat(), // Assuming a package like spatial/pointcasting
//                'longitude' => $store->location->getLng()
//            ];
//            return $store;
//        });

        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }
}
