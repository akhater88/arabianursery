<?php

namespace App\Http\Requests;

use App\Enums\SeedlingServiceStatuses;
use App\Models\FarmUser;
use App\Models\SeedlingService;
use App\Models\SeedType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeedlingServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "type" => ['required', Rule::in([SeedlingService::TYPE_FARMER, SeedlingService::TYPE_PERSONAL])],
            "farm_user" => ['nullable', Rule::requiredIf(request('type') == SeedlingService::TYPE_FARMER), 'exists:' . FarmUser::class . ',id'],
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
            "discount_amount" => ['nullable', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "status" => ['required', Rule::enum(SeedlingServiceStatuses::class)],
            "payment_type" => ['nullable', 'in:cash,installments'],
            "cash_invoice_number" => ['nullable', 'required_with:cash_amount'],
            "cash_amount" => ['nullable', 'required_with:cash_invoice_number'],
            'installments' =>  ['nullable'],
            'installments.*.invoice_number' => ['nullable'], //Rule::requiredIf(request('payment_type') == 'installments')
            'installments.*.amount' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'date'],
            'season_id' => ['nullable', 'exists:seasons,id'],
        ];
    }
}
