<?php

namespace App\Http\Controllers;

use App\Models\NurseryWarehouseEntity;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;

class NurseryUserDashboardController extends Controller
{
    public function index()
    {
        return view('home', [
            'page_title' => 'الرئيسية',
            'seedling_service_count' => SeedlingService::count(),
            'seedling_purchase_request_count' => SeedlingPurchaseRequest::count(),
            'warehouse_entity_count' => NurseryWarehouseEntity::count(),
            'nursery_seeds_sales_count' => NurseryWarehouseEntity::count(),
            'up_coming_seeds_instalments' => '',
            'up_coming_seedling_instalments' => '',
        ]);
    }
}
