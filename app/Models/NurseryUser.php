<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NurseryUser extends Authenticatable
{
    use HasFactory;

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_INCOMPLETE = 2;

    protected $guarded = ['id'];


    //  ----------    Relationships    ----------
    public function nursery(): belongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    public function seedlingServices(): hasMany
    {
        return $this->hasMany(SeedlingService::class);
    }

    public function seedlingPurchaseRequests(): hasMany
    {
        return $this->hasMany(SeedlingPurchaseRequest::class);
    }


    //  ----------   Tools    ----------
    public function isEnabled(): bool
    {
        return $this->status === self::STATUS_ENABLED;
    }

    public function isInCompleted(): bool
    {
        return $this->status === self::STATUS_INCOMPLETE;
    }
}
