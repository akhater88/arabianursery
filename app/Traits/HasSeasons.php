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
        return $this->morphToMany(Season::class, 'seasonable')->withTimestamps();
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
}
