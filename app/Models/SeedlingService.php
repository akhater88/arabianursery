<?php

namespace App\Models;

use App\Enums\SeedlingServiceStatuses;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SeedlingService extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    const TYPE_PERSONAL = 1;
    const TYPE_FARMER = 2;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => SeedlingServiceStatuses::class,
        'cash' => 'object',
        'installments' => 'array',
    ];

    //  ----------    Relationships    ----------
    public function seedType(): belongsTo
    {
        return $this->belongsTo(SeedType::class);
    }

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

    public function agriculturalSupplyStoreUser(): belongsTo
    {
        return $this->belongsTo(AgriculturalSupplyStoreUser::class);
    }
}
