@php
    use App\Models\NurserySeedsSale;
    use App\Models\NurseryWarehouseEntity;
    use App\Models\SeedlingPurchaseRequest;
    use App\Models\SeedlingService;
    use Illuminate\Support\Carbon;

    $seasonLabel = $selectedSeason
        ? $selectedSeason->name . ' (' . optional($selectedSeason->start_date)->format('Y-m-d') . ' - ' . optional($selectedSeason->end_date)->format('Y-m-d') . ')'
        : 'كل المواسم';
@endphp

@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <h4 class="mb-1">تقرير مالي للعميل حسب الموسم</h4>
                        <p class="mb-0 text-muted">
                            {{ $farmer->name }} · {{ $farmer->country_code }}{{ $farmer->mobile_number }}
                        </p>
                        @if($farmer->farm)
                            <p class="mb-0 text-muted">{{ $farmer->farm->name }}</p>
                        @endif
                        <p class="mb-0 text-muted">{{ $seasonLabel }}</p>
                    </div>
                    <div class="no-print mt-3 mt-md-0">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            طباعة التقرير
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-3 no-print">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <x-season-select
                            class="col-12 col-md-6"
                            :seasons="$seasons"
                            :selected="$seasonFilter"
                            name="season_id"
                            id="report-season-id"
                            :include-placeholder="false"
                            :include-all-option="true"
                            all-option-label="كل المواسم"
                            all-option-value="all"
                        />
                        <div class="col-12 col-md-3 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary btn-block">تحديث التقرير</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">إجمالي قيمة الأقساط</h6>
                            <h3 class="mb-0">{{ number_format($totals['overall'], 2) }} ر.س</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">المحصّل</h6>
                            <h3 class="mb-0 text-success">{{ number_format($totals['collected'], 2) }} ر.س</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">المتبقي</h6>
                            <h3 class="mb-0 text-danger">{{ number_format($totals['pending'], 2) }} ر.س</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($seasonFilter === 'all' && $seasonBreakdown->isNotEmpty())
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">ملخص المواسم</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th>الموسم</th>
                                    <th>إجمالي الأقساط</th>
                                    <th>المحصّل</th>
                                    <th>المتبقي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($seasonBreakdown as $seasonData)
                                    <tr>
                                        <td>{{ $seasonData['name'] ?? '—' }}</td>
                                        <td>{{ number_format($seasonData['overall'] ?? 0, 2) }} ر.س</td>
                                        <td class="text-success">{{ number_format($seasonData['collected'] ?? 0, 2) }} ر.س</td>
                                        <td class="text-danger">{{ number_format($seasonData['pending'] ?? 0, 2) }} ر.س</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">تفاصيل الأقساط</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>تاريخ التحصيل</th>
                                <th>نوع القسط</th>
                                <th>السجل المرتبط</th>
                                <th>الموسم</th>
                                <th>الحالة</th>
                                <th>المبلغ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($installments as $installment)
                                @php
                                    $record = $installment->installmentable;
                                    $recordLabel = '—';

                                    if ($record instanceof SeedlingService) {
                                        $recordLabel = 'خدمة تشتيل #' . $record->id;
                                    } elseif ($record instanceof NurserySeedsSale) {
                                        $recordLabel = 'بيع بذور #' . $record->id;
                                    } elseif ($record instanceof SeedlingPurchaseRequest) {
                                        $recordLabel = 'طلب شراء شتلات #' . $record->id;
                                    } elseif ($record instanceof NurseryWarehouseEntity) {
                                        $recordLabel = 'إدخال مخزون #' . $record->id;
                                    }

                                    $collectionDate = $installment->invoice_date
                                        ? Carbon::parse($installment->invoice_date)->format('Y-m-d')
                                        : '—';

                                    $statusLabel = $installment->invoice_number ? 'محصّل' : 'غير محصّل';

                                    $typeLabel = match ($installment->type) {
                                        'Collection' => 'تحصيل',
                                        'Payment' => 'دفع',
                                        default => $installment->type,
                                    };

                                    $seasonNames = $installment->getRelationValue('allSeasons')
                                        ? $installment->getRelationValue('allSeasons')->pluck('name')->implode(', ')
                                        : '';
                                @endphp
                                <tr>
                                    <td>{{ $installment->invoice_number ?? '—' }}</td>
                                    <td>{{ $collectionDate }}</td>
                                    <td>{{ $typeLabel }}</td>
                                    <td>{{ $recordLabel }}</td>
                                    <td>{{ $seasonNames ?: '—' }}</td>
                                    <td class="{{ $installment->invoice_number ? 'text-success' : 'text-danger' }}">{{ $statusLabel }}</td>
                                    <td>{{ number_format($installment->amount, 2) }} ر.س</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد أقساط مرتبطة بهذا العميل ضمن النطاق المحدد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }

            html,
            body {
                font-size: 12px;
                background: #fff;
                margin: 0;
                padding: 0;
                width: 100% !important;
            }

            .no-print,
            .main-header,
            .main-sidebar,
            .main-footer,
            .content-header,
            .control-sidebar {
                display: none !important;
            }

            .wrapper,
            .content-wrapper,
            .content,
            .container-fluid,
            .row,
            [class^="col-"],
            [class*=" col-"] {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .card {
                border: none;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .card-body {
                padding: 1rem !important;
            }

            h3 {
                font-size: 18px !important;
            }

            h4,
            h5,
            h6 {
                font-size: 16px !important;
            }

            table {
                width: 100% !important;
            }

            table thead th,
            table tbody td {
                padding: 0.5rem !important;
                font-size: 11px !important;
            }
        }
    </style>
@endpush
