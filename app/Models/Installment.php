<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Installment extends Model
{
    protected $fillable = ['invoice_number','invoice_date', 'amount', 'type', 'nursery_id'];
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
}
