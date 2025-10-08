<?php

namespace App\Http\Requests;

use App\Models\AgriculturalSupplyStoreUser;
use App\Models\EntityType;
use App\Models\SeedType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNurseryWarehouseEntityRequest extends FormRequest
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
            "agricultural_supply_store_user" => ['required', 'exists:' . AgriculturalSupplyStoreUser::class . ',id'],
            'entity_type' => ['required', 'exists:' . EntityType::class . ',id'],
            "seed_type" => ['required', 'exists:' . SeedType::class . ',id'],
            "received_at" => ['required'],
            "quantity" => ['required', 'integer', 'gt:0'],
            "price" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => ['nullable', 'required_with:cash_amount'],
            "cash_amount" => ['nullable', 'required_with:cash_invoice_number'],
            "comment" => ['nullable'],
            'installments' =>  ['nullable'],
            'installments.*.invoice_number' => ['nullable'],
            'installments.*.amount' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'date'],
            'season_id' => ['nullable', 'exists:seasons,id'],
        ];
    }
}
