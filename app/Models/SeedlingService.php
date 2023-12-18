<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeedlingService extends Model
{
    use HasFactory;

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
}
