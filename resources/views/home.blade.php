@extends('layouts.dashboard')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-black-50">أهلا بك في مشتل: {{ Auth::guard()->user()->nursery->name }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$seedling_service_count}}</h3>

                    <p>خدمة تشتيل الزارعين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <a href="{{route('seedling-services.index')}}" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$seedling_purchase_request_count}}</h3>

                    <p>مبيعات اشتال</p>
                </div>
                <div class="icon">
                    <i class="fas fa-seedling" style="color: #8cba92;"></i>
                </div>
                <a href="{{route('seedling-purchase-requests.index')}}" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="small-box bg-gradient-teal">
                <div class="inner">
                    <h3>{{$nursery_seeds_sales_count}}</h3>

                    <p>مبيعات بذور</p>
                </div>
                <div class="icon">
                    <i class="fas fa-allergies"></i>
                </div>
                <a href="{{route('nursery-seeds-sales.index')}}" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="small-box bg-gradient-olive">
                <div class="inner">
                    <h3>{{$warehouse_entity_count}}</h3>

                    <p>المخزن</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="{{route('warehouse-entities.index')}}" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">دفعات للتحصيل</h3>
            <div class="card-tools">
                {{$collection_installments_sum}} JOD
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th>الخدمة</th>
                    <th>الدفعة</th>
                    <th>التاريخ التحصيل</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($collection_installments))
                    @foreach($collection_installments as $collection_installment)
                        @if(!is_null($collection_installment->installmentable->farmUser))
                        <tr>
                            <td>
                                <img src="dist/img/default-150x150.png" alt="Product 1" class="img-circle img-size-32 mr-2">

                                {{$collection_installment->installmentable->farmUser?->name}}

                            </td>
                            <td>
                                @switch($collection_installment->installmentable_type)
                                    @case('App\Models\SeedlingService')
                                        خدمة تشتيل
                                        <a href="{{route('seedling-services.edit', $collection_installment->installmentable_id)}}">
                                            ({{$collection_installment->installmentable->seedType->name}} - {{$collection_installment->installmentable->seed_class}})
                                        </a>
                                        @break

                                    @case('App\Models\SeedlingPurchaseRequest')
                                        خدمة بيع اشتال
                                        <a href="{{route('seedling-purchase-requests.edit', $collection_installment->installmentable_id)}}">
                                            ({{$collection_installment->installmentable->seedlingService->seedType->name}} - {{$collection_installment->installmentable->seedlingService->seed_class}})
                                        </a>
                                        @break
                                    @case('App\Models\NurserySeedsSale')
                                        خدمة بيع بذور
                                        <a href="{{route('nursery-seeds-sales.edit', $collection_installment->installmentable_id)}}">
                                            ({{$collection_installment->installmentable->seedType->name}} - {{$collection_installment->installmentable->seed_class}})
                                        </a>
                                        @break
                                @endswitch
                                        </td>
                            <td>{{$collection_installment->amount}} JOD</td>
                            <td>
                                {{$collection_installment->invoice_date}}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">دفعات مستحقة على مخزن المشتل</h3>
                    <div class="card-tools">
                        {{$due_installments_sum}} JOD
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>اسم المورد</th>
                            <th>الخدمة</th>
                            <th>الدفعة</th>
                            <th>التاريخ الاستحقاق</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($due_installments))
                            @foreach($due_installments as $due_installment)
                                @if(!is_null($due_installment->installmentable->agriculturalSupplyStoreUser))
                                    <tr>
                                        <td>
                                            <img src="dist/img/default-150x150.png" alt="Product 1" class="img-circle img-size-32 mr-2">

                                            {{$due_installment->installmentable->agriculturalSupplyStoreUser?->name}}

                                        </td>
                                        <td>
                                            @switch($due_installment->installmentable_type)
                                                @case('App\Models\NurseryWarehouseEntity')
                                                    شراء بذور
                                                    <a href="{{route('warehouse-entities.edit', $due_installment->installmentable_id)}}">
                                                        ({{$due_installment->installmentable->entity->name}})
                                                    </a>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{$due_installment->amount}} JOD</td>
                                        <td>
                                            {{$due_installment->invoice_date}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <div class="row">
            <a href="{{ route('seedling-services.create') }}" style="width: 60px; height: 53px; display: none;" class="others btn btn-success rounded-circle mr-2">أشتال</a>
            <a href="{{ route('seedling-purchase-requests.create') }}" style="width: 60px; height: 53px; display: none;" class="others btn btn-success rounded-circle">طلب</a>
        </div>
        <div class="row mt-1">
            <button onclick="display()" style="width: 60px; height: 53px" class="btn btn-info rounded-circle mr-2">+</button>
            <a href="{{ route('warehouse-entities.create') }}" style="width: 60px; height: 53px; display: none;" class="others btn btn-success rounded-circle">مخزن</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let shouldBeDisplayed = false;
        function display() {
            shouldBeDisplayed = !shouldBeDisplayed;
            if(shouldBeDisplayed){
                document.querySelectorAll('.others').forEach(button => button.style.display = 'inline-block')
            } else {
                document.querySelectorAll('.others').forEach(button => button.style.display = 'none')
            }
        }
    </script>
@endsection
