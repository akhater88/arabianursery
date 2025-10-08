<?php

namespace App\Models;

use App\Traits\HasSeasons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasSeasons;

    protected $fillable = [
        'store_id',
        'name',
        'image',
        'description',
        'price',
    ];

    public function store()
    {
        return $this->belongsTo(AgriculturalSupplyStore::class, 'store_id');
    }
}
