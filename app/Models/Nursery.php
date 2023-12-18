<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nursery extends Model
{
    use HasFactory;

    public function nurseryUsers(): hasMany
    {
        return $this->hasMany(NurseryUser::class);
    }

    public function seedlingServices(): hasMany
    {
        return $this->hasMany(SeedlingService::class);
    }

    public function seedlingPurchaseRequests(): hasMany
    {
        return $this->hasMany(SeedlingPurchaseRequest::class);
    }
}
