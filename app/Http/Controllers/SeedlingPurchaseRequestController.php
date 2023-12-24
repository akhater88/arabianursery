<?php

namespace App\Http\Controllers;

use App\Models\FarmUser;
use App\Models\SeedlingService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeedlingPurchaseRequestController extends Controller
{
    public function create()
    {
        return view('seedling-purchase-requests/create', [
            'page_title' => 'طلب شراء أشتال'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "seedling_service" => ['required', Rule::exists(SeedlingService::class, 'id')->where(function (Builder $query) {
                return $query->where('type', SeedlingService::TYPE_PERSONAL);
            })],
            "farm_user" => ['required', 'exists:' . FarmUser::class . ',id'],
            "tray_count" => ['required', 'integer', 'gt:0'],
            "price_per_tray" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'], // for exactly 2 digits: regex:/^(?:[1-9]\d+|\d)(?:\.\d\d)?$/
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => [Rule::requiredIf($request->payment_type == 'cash')],
            "cash_amount" => [Rule::requiredIf($request->payment_type == 'cash')],
            'installments' =>  [Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.invoice_number' => ['nullable', Rule::requiredIf($request->payment_type == 'installments')],
            'installments.*.amount' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf($request->payment_type == 'installments'), 'date'],
        ]);

        $request->user()->seedlingPurchaseRequests()->create([
            "farm_user_id" => $request->farm_user,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            "cash" => $request->payment_type == 'cash' ? json_encode(['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount]) : null,
            'installments' => $request->payment_type == 'installments' ? json_encode(collect($request->installments)->values()) : null,
        ]);

        return redirect()->back();
    }
}
