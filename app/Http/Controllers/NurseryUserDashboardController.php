<?php

namespace App\Http\Controllers;

use App\Models\NurserySeedsSale;
use App\Models\NurseryWarehouseEntity;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use Illuminate\Support\Facades\Auth;

class NurseryUserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('home', [
            'page_title' => 'الرئيسية',
            'seedling_service_count' => SeedlingService::where('nursery_id', $user->nursery->id)->count(),
            'seedling_purchase_request_count' => SeedlingPurchaseRequest::where('nursery_user_id', $user->id)->count(),
            'warehouse_entity_count' => NurseryWarehouseEntity::where('nursery_id', $user->nursery->id)->count(),
            'nursery_seeds_sales_count' => NurserySeedsSale::where('nursery_id', $user->nursery->id)->count(),
            'up_coming_seeds_instalments' => '',
            'up_coming_seedling_instalments' => '',
        ]);
    }
}
