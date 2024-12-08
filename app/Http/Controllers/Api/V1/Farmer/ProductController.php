<?php

namespace App\Http\Controllers\Api\V1\Farmer;
use App\Models\AgriculturalSupplyStore;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Retrieve all products for a specific store.
     *
     * @param int $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($storeId)
    {
        $store = AgriculturalSupplyStore::with('products')->find($storeId);

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $store->products,
        ]);
    }
}

