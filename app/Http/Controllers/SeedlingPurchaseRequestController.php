<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeedlingPurchaseRequest;
use App\Http\Requests\UpdateSeedlingPurchaseRequest;
use App\Models\SeedlingPurchaseRequest;

class SeedlingPurchaseRequestController extends Controller
{
    public function create()
    {
        return view('seedling-purchase-requests/create-or-edit', [
            'page_title' => 'طلب شراء أشتال',
            'seedling_purchase_request' => null,
        ]);
    }

    public function store(StoreSeedlingPurchaseRequest $request)
    {
        $request->user()->seedlingPurchaseRequests()->create([
            "farm_user_id" => $request->farm_user,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        return redirect()->back();
    }

    public function edit(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        return view('seedling-purchase-requests/create-or-edit', [
            'page_title' => 'تعديل طلب شراء أشتال',
            'seedling_purchase_request' => $seedling_purchase_request,
        ]);
    }

    public function update(SeedlingPurchaseRequest $seedling_purchase_request, UpdateSeedlingPurchaseRequest $request)
    {
        $seedling_purchase_request->update([
            "farm_user_id" => $request->farm_user,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        return redirect()->back();
    }
}
