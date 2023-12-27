<?php

namespace App\Models;

use App\Enums\SeedlingServiceStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeedlingService extends Model
{
    use HasFactory, SoftDeletes;

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

    public function seedlingPurchaseRequests(): hasMany
    {
        return $this->hasMany(SeedlingPurchaseRequest::class);
    }


    /* -------------- Scopes -------------- */
    public function scopePersonal($query)
    {
        return $query->where('type', self::TYPE_PERSONAL);
    }

    //  ----------    Accessor & Mutators    ----------
    public function optionName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->seedType->name} - {$this->seed_class} - {$this->created_at->format('Y-m-d')}"
        );
    }
}
