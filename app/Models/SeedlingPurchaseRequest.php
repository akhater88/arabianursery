<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeedlingPurchaseRequest extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'cash' => 'object',
    ];

    public function nursery(): belongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    public function nurseryUser(): belongsTo
    {
        return $this->belongsTo(NurseryUser::class);
    }

    public function farmUser(): belongsTo
    {
        return $this->belongsTo(FarmUser::class);
    }

    public function seedlingService(): belongsTo
    {
        return $this->belongsTo(SeedlingService::class);
    }

    /**
     * Get all of the Seedling Purchase's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }
}
