<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmUser extends Model
{
    use HasFactory;

    public function farm(): belongsTo
    {
        return $this->belongsTo(Farm::class);
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
