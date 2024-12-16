<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Nursery extends Model
{
    use HasFactory, HasSpatial;

    protected $guarded = ['id'];

    protected $casts = [
        'location' => Point::class,
    ];



    public function nurseryUsers(): hasMany
    {
        return $this->hasMany(NurseryUser::class);
    }

    public function seedlingServices(): hasMany
    {
        return $this->hasMany(SeedlingService::class);
    }

    public function reservedSeedlingServices(): hasMany
    {
        return $this->hasMany(SeedlingService::class,'reserved_from','id');
    }

    public function nurserySeedsSales(): hasMany
    {
        return $this->hasMany(NurserySeedsSale::class);
    }

    public function seedlingPurchaseRequests(): hasMany
    {
        return $this->hasMany(SeedlingPurchaseRequest::class);
    }

    public function seedlingPurchaseRequestsRequestedBy(): MorphMany
    {
        return $this->morphMany(SeedlingPurchaseRequest::class, 'requestedby','requestedby_type');
    }

    public function nurseryWarehouseEntities(): hasMany
    {
        return $this->hasMany(NurseryWarehouseEntity::class);
    }

    public function farmUsers(): BelongsToMany
    {
        return $this->belongsToMany(FarmUser::class, 'nursery_farm_user', 'nursery_id', 'farm_user_id');
    }

    public function installments(): hasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * The seedling that shared to the nurseries.
     */
    public function seedlingsShared(): BelongsToMany
    {
        return $this->belongsToMany(SeedlingService::class,'seedling_shared_with_nurseries', 'nursery_id', 'seedling_id')->withTimestamps();
    }
}
