<?php

namespace App\Http\Controllers;

use App\Models\AgriculturalSupplyStoreUser;
use App\Models\EntityType;
use App\Models\SeedType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NurseryWarehouseEntityController extends Controller
{
    public function create()
    {
        return view('warehouse-entities/create', [
            'page_title' => 'طلب إدخال إلى المخزن',
            'entity_types' => EntityType::get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "agricultural_supply_store_user" => ['required', 'exists:' . AgriculturalSupplyStoreUser::class . ',id'],
            'entity_type' => ['required', 'exists:' . EntityType::class . ',id'],
            "seed_type" => ['required', 'exists:' . SeedType::class . ',id'],
            "quantity" => ['required', 'integer', 'gt:0'],
            "price" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => [Rule::requiredIf($request->payment_type == 'cash')],
            "cash_amount" => [Rule::requiredIf($request->payment_type == 'cash')],
            'installments' => [Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.invoice_number' => ['nullable', Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.amount' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'date'],
        ]);

        $request->user()->warehouseEntities()->create([
            "agricultural_supply_store_user_id" => $request->agricultural_supply_store_user,
            "entity_type_id" => $request->entity_type,
            "quantity" => $request->quantity,
            "price" => $request->price,
            'entity_type' => SeedType::class,
            'entity_id' => $request->seed_type,
            "seed_type_id" => $request->seed_type,
            "nursery_id" => $request->user()->nursery->id,
            "cash" => $request->payment_type == 'cash' ? json_encode(['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount]) : null,
            'installments' => $request->payment_type == 'installments' ? json_encode(collect($request->installments)->values()) : null,
        ]);

        return redirect()->back();
    }
}
