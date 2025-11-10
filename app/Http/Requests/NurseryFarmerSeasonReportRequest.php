<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NurseryFarmerSeasonReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('nursery-admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'season_id' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value === null || $value === '') {
                    return;
                }

                if ($value === 'all') {
                    return;
                }

                $seasonId = (int) $value;
                $nursery = $this->user()?->nursery;

                if (! $nursery || ! $nursery->definedSeasons()->whereKey($seasonId)->exists()) {
                    $fail(__('الموسم المحدد غير صالح.'));
                }
            }],
        ];
    }
}
