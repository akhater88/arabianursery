<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriculturalSupplyStoreUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //  ----------    Accessor & Mutators    ----------
    public function optionName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->name} ({$this->mobile_number})"
        );
    }
}
