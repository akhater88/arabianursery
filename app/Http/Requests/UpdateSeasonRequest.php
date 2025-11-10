<?php

namespace App\Http\Requests;

use App\Models\Season;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $season = $this->route('season');

        if (! $user || ! $user->hasRole('nursery-admin')) {
            return false;
        }

        if (! $season instanceof Season) {
            return false;
        }

        return $season->nursery_id === $user->nursery?->getKey();
    }

    public function rules(): array
    {
        $season = $this->route('season');
        $nurseryId = $this->user()?->nursery?->getKey();

        $uniqueNameRule = Rule::unique('seasons', 'name');

        if ($season instanceof Season) {
            $uniqueNameRule->ignore($season->getKey());
        }

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
