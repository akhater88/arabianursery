<?php

namespace App\Http\Filters;

use App\Models\SeedlingService;

class SeedlingServiceFilter extends QueryFilter
{
    public function farmUserName($value)
    {
        $this->query->whereRelation('farmUser', 'name', 'like', '%' . trim($value) . '%');
    }

    public function farmUserPhoneNumber($value)
    {
        $this->query->whereRelation('farmUser', 'mobile_number', 'like', '%' . trim($value) . '%');
    }

    public function germinationDate($value)
    {
        $this->query->whereDate("created_at", $value);
    }

    public function isPersonalType($value)
    {
        $this->query->where('type', SeedlingService::TYPE_PERSONAL);
    }
}
