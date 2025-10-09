<?php

namespace App\Traits;

use App\Models\Season;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasSeasons
{
    /**
     * Associate the model with many seasons.
     */
    public function seasons(): MorphToMany
    {
        $relation = $this->morphToMany(Season::class, 'seasonable')->withTimestamps();

        $nurseryId = $this->seasonNurseryId();

        if ($nurseryId) {
            $relation->where('seasons.nursery_id', $nurseryId);
        }

        return $relation;
    }

    /**
     * Limit the query to models that belong to the provided season.
     */
    public function scopeInSeason(Builder $query, Season|int $season): Builder
    {
        $seasonId = $season instanceof Season ? $season->getKey() : $season;

        return $query->whereHas('seasons', function (Builder $relation) use ($seasonId) {
            $relation->where('seasons.id', $seasonId);
        });
    }

    /**
     * Fetch the season that is currently active for the model.
     */
    public function currentSeason(): ?Season
    {
        $today = now()->toDateString();

        return $this->seasons()
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('start_date')
            ->first();
    }

    protected function seasonNurseryId(): ?int
    {
        if (method_exists($this, 'getAttribute')) {
            $nurseryId = $this->getAttribute('nursery_id');

            if (! is_null($nurseryId)) {
                return (int) $nurseryId;
            }
        }

        if (method_exists($this, 'nursery')) {
            $nursery = $this->getRelationValue('nursery') ?? $this->nursery;

            return $nursery?->getKey();
        }

        return null;
    }
}
