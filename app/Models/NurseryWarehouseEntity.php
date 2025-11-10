<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\HasSeasons;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NurseryWarehouseEntity extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasSeasons;

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

    //  ----------    Relationships    ----------
    public function seedType(): belongsTo
    {
        return $this->belongsTo(SeedType::class, 'entity_id', 'id');
    }

    public function nursery(): belongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    public function seedsSales()
    {
        return $this->hasMany(NurserySeedsSale::class,'nursery_warehouse_entities_id', 'id');
    }

    /**
     * Get all of the seedling service's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    //  ----------    Accessor & Mutators    ----------
    public function optionName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->seedType->name} - {$this->entity_sub_type} - {$this->created_at->format('Y-m-d')}"
        );
    }
}
