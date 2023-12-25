@extends('layouts.dashboard')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-black-50">أهلا بك في مشتل: {{ Auth::guard()->user()->nursery->name }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-12">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>150</h3>

                    <p>خدمة تشتيل الزارعين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tractor"></i>
                </div>
                <a href="#" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-12">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>100</h3>

                    <p>طلبات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list-ul"></i>
                </div>
                <a href="#" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-12">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>44</h3>

                    <p>المخزن</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="#" class="small-box-footer">اضغط هنا <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
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
