<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SeedlingServiceImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //  ----------    Accessor & Mutators    ----------

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Storage::url($this->path)
        );
    }
}
