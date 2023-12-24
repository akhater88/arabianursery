<?php

namespace App\Http\Controllers;

use App\Enums\SeedlingServiceStatuses;
use App\Models\SeedlingService;
use App\Models\SeedType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeedlingServiceController extends Controller
{
    public function create()
    {
        return view('seedling-services/create', [
            'page_title' => 'إضافة خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "type" => ['required', Rule::in([SeedlingService::TYPE_FARMER, SeedlingService::TYPE_PERSONAL])],
            "farm_user" => ['nullable', Rule::requiredIf($request->type == SeedlingService::TYPE_FARMER)],
            "tray_count" => ['required', 'integer', 'gt:0'],
            "seed_type" => ['required', 'exists:' . SeedType::class . ',id'],
            "seed_class" => ['nullable', 'string', 'max:50'],
            "seed_count" => ['required', 'integer', 'digits_between:1,7'],
            "germination_rate" => ['nullable', 'integer', 'min:0', 'max:100'],
            "germination_period" => ['required', 'integer', 'min:0', 'max:100'],
            "greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "tunnel_greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "price_per_tray" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'], // for exactly 2 digits: regex:/^(?:[1-9]\d+|\d)(?:\.\d\d)?$/
            "additional_cost" => ['nullable', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "status" => ['required', Rule::enum(SeedlingServiceStatuses::class)],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => [Rule::requiredIf($request->payment_type == 'cash')],
            "cash_amount" => [Rule::requiredIf($request->payment_type == 'cash')],
            'installments' =>  [Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.invoice_number' => ['nullable', Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.amount' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'date'],
        ]);

        $request->user()->seedlingServices()->create([
            "type"=> $request->type,
            "farm_user_id"=> $request->type == SeedlingService::TYPE_FARMER ? $request->farm_user : null,
            "tray_count"=> $request->tray_count,
            "seed_type_id"=> $request->seed_type,
            "nursery_id"=> $request->user()->nursery->id,
            "seed_class"=> $request->seed_class,
            "seed_count"=> $request->seed_count,
            "germination_rate"=> $request->germination_rate,
            "germination_period"=> $request->germination_period,
            "greenhouse_number"=> $request->greenhouse_number,
            "tunnel_greenhouse_number"=> $request->tunnel_greenhouse_number,
            "price_per_tray"=> $request->price_per_tray,
            "additional_cost"=> $request->additional_cost,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? json_encode(['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount ]) : null,
            'installments'=> $request->payment_type == 'installments' ? json_encode(collect($request->installments)->values()) : null,
        ]);

        return redirect()->back();
    }
}
