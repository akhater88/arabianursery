<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NurseryWarehouseEntity extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'cash' => 'object',
    ];

    public function agriculturalSupplyStoreUser(): belongsTo
    {
        return $this->belongsTo(AgriculturalSupplyStoreUser::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entity_type', 'entity_id');
    }

    /**
     * Get all of the seedling service's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }
}
