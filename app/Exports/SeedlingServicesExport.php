<?php

namespace App\Exports;

use App\Models\SeedlingService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeedlingServicesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return SeedlingService::with(['farmUser', 'seedType'])->get();
    }

    public function headings(): array
    {
        return [
            'الرقم التعريفي',
            'اسم العميل',
            'رقم الهاتف',
            'عدد الصواني',
            'النوع - الصنف',
            'الحالة',
        ];
    }

    /**
     * @param SeedlingService $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->farmUser?->name,
            $row->farmUser?->mobile_number,
            $row->tray_count,
            "{$row->seedType->name} - {$row->seed_class}",
            $row->status->value,
        ];
    }
}
