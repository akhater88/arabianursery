<?php

namespace App\Models;

use App\Models\Nursery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

class Season extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'nursery_id',
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the nursery that owns the season.
     */
    public function nursery(): BelongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    /**
     * Get the models that are associated with this season.
     */
    public function seasonables(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'seasonable');
    }

    /**
     * Scope the query to seasons that belong to the provided nursery.
     */
    public function scopeForNursery(Builder $query, Nursery|int $nursery): Builder
    {
        $nurseryId = $nursery instanceof Nursery ? $nursery->getKey() : $nursery;

        return $query->where('nursery_id', $nurseryId);
    }

    /**
     * Determine if the season is active for a given date.
     */
    public function isActive(Carbon|string|null $date = null): bool
    {
        $date = match (true) {
            $date instanceof Carbon => $date,
            is_string($date) => Carbon::parse($date),
            default => now(),
        };

        return $this->start_date->lte($date) && $this->end_date->gte($date);
    }
}
