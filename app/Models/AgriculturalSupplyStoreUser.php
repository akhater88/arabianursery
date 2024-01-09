<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgriculturalSupplyStoreUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //  ----------    Relationships    ----------
    public function nurseryWarehouseEntities(): hasMany
    {
        return $this->hasMany(NurseryWarehouseEntity::class);
    }

    public function nurserySeedsSales(): hasMany
    {
        return $this->hasMany(NurserySeedsSale::class);
    }

    //  ----------    Accessor & Mutators    ----------
    public function optionName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->name} ({$this->mobile_number})"
        );
    }
}
