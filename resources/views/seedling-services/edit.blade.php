@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id='alert-success' class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <form method="POST" role="form" action="{{route('seedling-services.update', $seedling_service->id)}}">
                    @method('PUT')
                    @csrf

                    <div class="card-body">
                        <div class="row col-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" required id="customRadioInline1" class="custom-control-input"
                                       name="type"
                                       disabled
                                       @checked($seedling_service->type == SeedlingService::TYPE_FARMER)
                                       value="{{SeedlingService::TYPE_FARMER}}">
                                <label class="custom-control-label" for="customRadioInline1">إختر عميل</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline mb-3">
                                <input type="radio" id="customRadioInline2" class="custom-control-input" name="type"
                                       disabled
                                       @checked($seedling_service->type == SeedlingService::TYPE_PERSONAL)
                                       value="{{SeedlingService::TYPE_PERSONAL}}">
                                <label class="custom-control-label" for="customRadioInline2">أشتال خاصة مشتل</label>
                            </div>
                        </div>

                        @if(!is_null($seedling_service->farm_user_id))
                            <div class="form-row mb-3" id="farm-user-dev" style="display: none">
                                <div class="col-12 col-sm-4">
                                    <label for="farm-user-select">اسم أو رقم العميل</label>
                                    <select disabled class="form-control select2" id='farm-user-select' name="farm_user"
                                            style="width: 100%;">
                                            <option selected value="{{ $seedling_service->farm_user_id }}">
                                                {{ $seedling_service->farmUser->optionName }}
                                            </option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="tray-count">عدد الصواني</label>
                                <input id='tray-count' type="number" min=0 step="1" name="tray_count"
                                       value="{{ old('tray_count', $seedling_service) }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <select class="form-control select2" id='seed-type' name='seed_type'
                                        disabled style="width: 100%;">
                                        <option selected value="{{$seedling_service->seed_type_id }}">
                                            {{ $seedling_service->seedType->name }}
                                        </option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-class">الصنف</label>
                                <input id='seed-class' disabled type="text" name='seed_class' value="{{ $seedling_service->seed_class }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' disabled type="number" min=0 step="1" name="seed_count"
                                       value="{{ $seedling_service->seed_count }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="germination-rate">نسبة الإنبات</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 max="100" step="1" name="germination_rate"
                                           value="{{ old('germination_rate', $seedling_service) }}"
                                           class="form-control" id="germination-rate">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="germination-period">مدة التشتيل</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 max="100" step="1" name="germination_period"
                                           class="form-control"
                                           value="{{ old('germination_period', $seedling_service) }}"
                                           required
                                           id="germination-period">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">بالأيام</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="greenhouse-number">رقم البيت</label>
                                <input id='greenhouse-number' type="number" min=0 step="1" name="greenhouse_number"
                                       value="{{ old('greenhouse_number', $seedling_service) }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="tunnel-greenhouse-number">رقم القوس</label>
                                <input id='tunnel-greenhouse-number' type="number" min=0 step="1"
                                       name="tunnel_greenhouse_number"
                                       value="{{ old('tunnel_greenhouse_number', $seedling_service) }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="status">الحالة</label>
                                <select class="form-control select2" required id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" @selected(old('status', $seedling_service?->status->value) == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price-per-tray">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price_per_tray" class="form-control"
                                           value="{{ old('price_per_tray', $seedling_service) }}"
                                           required
                                           id="price-per-tray">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار لكل صينية</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="additional-cost">تكاليف إضافية</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="additional_cost" class="form-control"
                                           value="{{ old('additional_cost', $seedling_service) }}"
                                           id="additional-cost">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $seedling_service])

                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                                تعديل
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#seed-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#farm-user-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#status').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })
    </script>

    <script>
        function displayFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'block'
        }

        function hideFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'none'
        }

        if ({{$seedling_service->type == SeedlingService::TYPE_FARMER ? 'true' : 'false'}}) {
            displayFarmUserInput()
        }
    </script>

    @include('components.payments.script', ['model' => $seedling_service])
@endsection
