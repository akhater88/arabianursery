<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriculturalSupplyStore extends Model
{
    use HasFactory;
    protected $table = 'agricultural_supply_stores';

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'address',
        'location'
    ];
}
