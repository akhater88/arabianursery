<?php

namespace App\Http\Requests;

use App\Enums\SeedlingServiceStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeedlingServiceRequest extends FormRequest
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
            "tray_count" => ['required', 'integer', 'gt:0'],
            "germination_rate" => ['nullable', 'integer', 'min:0', 'max:100'],
            "germination_period" => ['required', 'integer', 'min:0', 'max:100'],
            "greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "tunnel_greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "price_per_tray" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'], // for exactly 2 digits: regex:/^(?:[1-9]\d+|\d)(?:\.\d\d)?$/
            "additional_cost" => ['nullable', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "status" => ['required', Rule::enum(SeedlingServiceStatuses::class)],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => ['nullable', 'required_with:cash_amount'],
            "cash_amount" => ['nullable', 'required_with:cash_invoice_number'],
            'installments' =>  ['nullable'],
            'installments.*.invoice_number' => ['nullable'], //, Rule::requiredIf(request('payment_type') == 'installments')
            'installments.*.amount' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'date'],
        ];
    }
}
