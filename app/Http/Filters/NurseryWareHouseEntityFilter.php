<?php

namespace App\Http\Filters;

class NurseryWareHouseEntityFilter extends QueryFilter
{
    public function agriculturalSupplyStoreUserName($value){
        $this->query->whereRelation('agriculturalSupplyStoreUser', 'name', 'like', '%' . trim($value) . '%');
    }

    public function agriculturalSupplyStoreUserPhoneNumber($value){
        $this->query->whereRelation('agriculturalSupplyStoreUser', 'mobile_number', 'like', '%' . trim($value) . '%');
    }

    public function date($value){
        $this->query->whereDate("created_at", $value);
    }
}
