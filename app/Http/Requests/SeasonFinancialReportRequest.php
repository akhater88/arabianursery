<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeasonFinancialReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('nursery-admin') ?? false;
    }

    public function rules(): array
    {
        $rule = Rule::exists('seasons', 'id');

        if ($nurseryId = optional($this->user()?->nursery)->getKey()) {
            $rule->where('nursery_id', $nurseryId);
        }

        return [
            'season_id' => [
                'nullable',
                'integer',
                $rule,
            ],
        ];
    }
}
