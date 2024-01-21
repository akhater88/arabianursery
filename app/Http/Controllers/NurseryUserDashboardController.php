<?php

namespace App\Http\Controllers;

use App\Models\Nursery;
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

        $nursery = Nursery::find($user->nursery->id);

        $instalments = $nursery->installments()->where('invoice_number', '=', null)->get()->groupBy('type');
        $collectionInstallments = collect([]);
        $collectionInstallmentsSum = 0;
        if(isset($instalments['Collection'])){
            $collectionInstallments = $instalments['Collection']->sortBy('invoice_date');
            $collectionInstallmentsSum = $instalments['Collection']->sum('amount');
        }
        $dueInstallments = collect([]);
        $dueInstalmentsSum = 0;
        if(isset($instalments['Due'])){
            $dueInstallments = $instalments['Due']->sortBy('invoice_date');
            $dueInstalmentsSum = $instalments['Due']->sum('amount');
        }

        return view('home', [
            'page_title' => 'الرئيسية',
            'seedling_service_count' => SeedlingService::where('nursery_id', $user->nursery->id)->count(),
            'seedling_purchase_request_count' => SeedlingPurchaseRequest::where('nursery_user_id', $user->id)->count(),
            'warehouse_entity_count' => NurseryWarehouseEntity::where('nursery_id', $user->nursery->id)->count(),
            'nursery_seeds_sales_count' => NurserySeedsSale::where('nursery_id', $user->nursery->id)->count(),
            'up_coming_seeds_instalments' => '',
            'up_coming_seedling_instalments' => '',
            'collection_installments' => $collectionInstallments,
            'collection_installments_sum' => $collectionInstallmentsSum,
            'due_installments' => $dueInstallments,
            'due_installments_sum' => $dueInstalmentsSum,

        ]);
    }
}
