<?php

namespace App\Models;

use App\Enums\NurserySeedsSaleStatuses;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class NurserySeedsSale extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => NurserySeedsSaleStatuses::class,
        'cash' => 'object'
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

    public function images(): hasMany
    {
        return $this->hasMany(SeedlingServiceImage::class);
    }

    /**
     * Get all of the seeds sales installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
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

    //  ----------    Tools    ----------


}
