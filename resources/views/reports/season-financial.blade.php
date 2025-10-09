@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <div class="card-body">
                    <h4 class="card-title mb-4">التقرير المالي الموسمي</h4>

                    @if($seasons->isEmpty())
                        <div class="alert alert-info mb-0">
                            لم يتم إنشاء أي موسم بعد. أضف موسمًا لعرض التقارير المالية المرتبطة به.
                        </div>
                    @else
                        <form method="GET" action="{{ route('nursery-reports') }}" class="row g-3 align-items-end mb-4">
                            <x-season-select
                                :seasons="$seasons"
                                :selected="$seasonFilter"
                                class="col-sm-6 col-md-4"
                                :include-placeholder="false"
                                :include-all-option="true"
                            />

                            <div class="col-sm-3 col-md-2 mt-3 mt-sm-0">
                                <button type="submit" class="btn btn-primary btn-block">تحديث التقرير</button>
                            </div>
                        </form>

                        @if($showingAllSeasons || $selectedSeason)
                            @if($showingAllSeasons)
                                <div class="mb-4">
                                    <h6 class="text-muted">نطاق التقرير</h6>
                                    <p class="mb-0">جميع المواسم والسجلات غير المرتبطة بأي موسم.</p>
                                </div>
                            @else
                                <div class="mb-4">
                                    <h6 class="text-muted">الموسم الحالي</h6>
                                    <p class="mb-0">
                                        {{ $selectedSeason->name }}
                                        ({{ optional($selectedSeason->start_date)->format('Y-m-d') }} - {{ optional($selectedSeason->end_date)->format('Y-m-d') }})
                                    </p>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h6 class="text-muted">إجمالي المبيعات</h6>
                                            <h3 class="mb-0">{{ number_format($totals['sales'], 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h6 class="text-muted">التحصيل النقدي</h6>
                                            <h3 class="mb-0">{{ number_format($totals['cash'], 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h6 class="text-muted">أقساط محصلة</h6>
                                            <h3 class="mb-0">{{ number_format($totals['installments_paid'], 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <h6 class="text-muted">أقساط قيد التحصيل</h6>
                                            <h3 class="mb-0">{{ number_format($totals['installments_pending'], 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">تفاصيل الأقساط حسب المزارع</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>المزارع</th>
                                            <th>المبلغ المحصل</th>
                                            <th>المبلغ المتبقي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($farmerInstallments as $row)
                                            <tr>
                                                <td>{{ $row['farmer']?->name ?? '—' }}</td>
                                                <td>{{ number_format($row['paid'], 2) }}</td>
                                                <td>{{ number_format($row['pending'], 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">لا توجد أقساط مرتبطة بالاختيار الحالي.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                يرجى اختيار موسم لعرض بياناته المالية.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#season-id').select2({
            theme: 'bootstrap4',
            dir: 'rtl'
        });
    </script>
@endsection
