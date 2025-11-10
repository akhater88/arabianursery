<?php

namespace App\Models;

use App\Traits\HasSeasons;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Installment extends Model
{
    use HasSeasons;
    protected $fillable = ['invoice_number','invoice_date', 'amount', 'type', 'nursery_id', 'farm_user_id', 'farm_user_id_type'];
    /**
     * Get the parent Installmentable model seedling service or Seedling Purchase
     */
    public function installmentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function nursery(): belongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    public function farmUser(): belongsTo
    {
        return $this->belongsTo(FarmUser::class)->withTrashed();
    }
}
