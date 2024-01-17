<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Installment extends Model
{
    protected $fillable = ['invoice_number','invoice_date', 'amount'];
    /**
     * Get the parent Installmentable model seedling service or Seedling Purchase
     */
    public function installmentable(): MorphTo
    {
        return $this->morphTo();
    }
}
