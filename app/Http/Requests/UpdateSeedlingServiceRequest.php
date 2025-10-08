<?php

namespace App\Http\Requests;

use App\Enums\SeedlingServiceStatuses;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
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
        $seedling_service = request()->route()->parameter('seedling_service');

        return [
            "tray_count" => ['required', 'integer', 'gt:0'],
            "germination_rate" => ['nullable', 'integer', 'min:0', 'max:100'],
            "germination_period" => ['required', 'integer', 'min:0', 'max:100'],
            "greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "tunnel_greenhouse_number" => ['nullable', 'integer', 'min:0'],
            "price_per_tray" => ['required', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'], // for exactly 2 digits: regex:/^(?:[1-9]\d+|\d)(?:\.\d\d)?$/
            "additional_cost" => ['nullable', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "discount_amount" => ['nullable', 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            "status" => ['required', Rule::enum(SeedlingServiceStatuses::class)],
            "payment_type" => ['required', 'in:cash,installments'],
            "cash_invoice_number" => ['nullable', 'required_with:cash_amount'],
            "cash_amount" => ['nullable', 'required_with:cash_invoice_number'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['nullable', 'string', function (string $attribute, mixed $value, Closure $fail) use($seedling_service) {
                if (Storage::fileMissing("tmp/uploads/{$value}") && Storage::fileMissing("seedling-services/{$seedling_service->id}/{$value}")) {
                    $fail("The {$attribute} is invalid.");
                }
            }],
            'installments' =>  ['nullable'],
            'installments.*.invoice_number' => ['nullable'], //, Rule::requiredIf(request('payment_type') == 'installments')
            'installments.*.amount' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'numeric', 'regex:/^\d*\.{0,1}\d{0,2}$/'],
            'installments.*.invoice_date' => ['nullable', Rule::requiredIf(request('payment_type') == 'installments'), 'date'],
            'season_id' => ['nullable', 'exists:seasons,id'],
        ];
    }
}
