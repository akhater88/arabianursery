<?php

namespace App\Traits;

use App\Http\Filters\QueryFilter;

trait Filterable
{
    public function scopeFilterBy($query, QueryFilter $filters)
    {
        $filters->apply($query);
    }
}
