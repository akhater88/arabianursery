<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) ($user && $user->hasRole('nursery-admin'));
    }

    public function rules(): array
    {
        $nurseryId = $this->user()?->nursery?->getKey();

        $uniqueNameRule = Rule::unique('seasons', 'name');

        if ($nurseryId) {
            $uniqueNameRule->where(fn ($query) => $query->where('nursery_id', $nurseryId));
        }

        return [
            'name' => ['required', 'string', 'max:255', $uniqueNameRule],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
