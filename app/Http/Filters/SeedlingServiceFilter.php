<?php

namespace App\Http\Filters;

class SeedlingServiceFilter extends QueryFilter
{
    public function farmUserName($value){
        $this->query->whereRelation('farmUser', 'name', 'like', '%' . trim($value) . '%');
    }

    public function phoneNumber($value){
        $this->query->whereRelation('farmUser', 'mobile_number', 'like', '%' . trim($value) . '%');
    }

    public function germinationDate($value){
        $this->query->whereDate("created_at", $value);
    }
}
