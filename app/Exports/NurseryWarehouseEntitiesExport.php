<?php

namespace App\Exports;

use App\Models\NurseryWarehouseEntity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NurseryWarehouseEntitiesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return NurseryWarehouseEntity::with(['agriculturalSupplyStoreUser', 'entity'])->get();
    }

    public function headings(): array
    {
        return [
            'الرقم التعريفي',
            'اسم المزود',
            'رقم هاتف المزود',
            'الكمية',
            'النوع',
        ];
    }

    /**
     * @param NurseryWarehouseEntity $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->agriculturalSupplyStoreUser->name,
            $row->agriculturalSupplyStoreUser->mobile_number,
            $row->quantity,
            $row->entity->name,
        ];
    }
}
