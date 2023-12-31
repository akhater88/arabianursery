<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SeedType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nurseryWarehouseEntities(): MorphMany
    {
        return $this->morphMany(NurseryWarehouseEntity::class, 'entity');
    }
}
