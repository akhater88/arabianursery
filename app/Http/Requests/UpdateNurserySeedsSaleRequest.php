<?php

namespace App\Http\Requests;

use App\Enums\NurserySeedsSaleStatuses;
use App\Models\FarmUser;
use App\Models\NurseryWarehouseEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNurserySeedsSaleRequest extends FormRequest
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
        $nusery_seeds_sale = request()->route()->parameter('nursery_seeds_sale');

        return [
            "farm_user" => ['required', 'exists:' . FarmUser::class . ',id'],
            "seed_count" => ['required', 'integer', 'digits_between:1,7'],
            "sold_at" => ['required'],
            'warehouse_seeds' => ['required', 'exists:' . NurseryWarehouseEntity::class . ',id'],
            "price" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'], // for exactly 2 digits: regex:/^(?:[1-9]\d+|\d)(?:\.\d\d)?$/
            "status" => ['required', Rule::enum(NurserySeedsSaleStatuses::class)],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => ['nullable', 'required_with:cash_amount'],
            "cash_amount" => ['nullable', 'required_with:cash_invoice_number'],
            'installments' =>  ['nullable'],
            "comment" => ['nullable'],
            'installments.*.invoice_number' => ['nullable'], //, Rule::requiredIf(request('payment_type') == 'installments')
            'installments.*.amount' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'date'],
        ];
    }
}
