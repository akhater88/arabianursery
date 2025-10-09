<?php

namespace App\Http\Requests;

use App\Models\Season;
use Illuminate\Foundation\Http\FormRequest;

class SeasonFinancialReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('nursery-admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'season_id' => [
                'nullable',
                function (string $attribute, $value, callable $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    if ($value === 'all') {
                        return;
                    }

                    if (! is_numeric($value)) {
                        $fail(trans('validation.integer', ['attribute' => $attribute]));

                        return;
                    }

                    $nurseryId = optional($this->user()?->nursery)->getKey();

                    $exists = Season::query()
                        ->whereKey((int) $value)
                        ->when($nurseryId, fn ($query) => $query->where('nursery_id', $nurseryId))
                        ->exists();

                    if (! $exists) {
                        $fail(trans('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
        ];
    }
}
