<?php

namespace App\Exports;

use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeedlingPurchaseRequestsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return SeedlingPurchaseRequest::with(['farmUser', 'seedlingService'])->get();
    }

    public function headings(): array
    {
        return [
            'الرقم التعريفي',
            'اسم العميل',
            'رقم الهاتف',
            'عدد الصواني',
            'النوع - الصنف',
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
            "{$row->seedlingService->seedType->name} - {$row->seedlingService->seed_class}",
        ];
    }
}
