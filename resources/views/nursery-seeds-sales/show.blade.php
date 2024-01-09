@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <form>
                    <div class="card-body">

                            <div class="form-row mb-3" id="farm-user-dev">
                                <div class="col-12 col-sm-4">
                                    <label for="farm-user-select">اسم أو رقم العميل</label>
                                    <select disabled class="form-control select2" id='farm-user-select' name="farm_user"
                                            style="width: 100%;">
                                            <option selected value="{{ $nursery_seeds_sale->farm_user_id }}">
                                                {{ $nursery_seeds_sale->farmUser->optionName }}
                                            </option>
                                    </select>
                                </div>
                            </div>

                        <div class="form-row mb-3">

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <select class="form-control select2" id='seed-type' name='seed_type'
                                        disabled style="width: 100%;">
                                        <option selected value="{{$nursery_seeds_sale->seed_type_id }}">
                                            {{ $nursery_seeds_sale->seedType->name }}
                                        </option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-class">الصنف</label>
                                <input id='seed-class' disabled type="text" name='seed_class' value="{{ $nursery_seeds_sale->seed_class }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' disabled type="number" min=0 step="1" name="seed_count"
                                       value="{{ $nursery_seeds_sale->seed_count }}"
                                       required
                                       class="form-control">
                            </div>


                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="status">الحالة</label>
                                <select class="form-control select2" disabled id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" @selected($nursery_seeds_sale->status->value == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price-per-tray">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price_per_tray" class="form-control"
                                           value="{{ $nursery_seeds_sale->price }}"
                                           disabled
                                           id="price-per-tray">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>


                        </div>



                        @include('components.payments.view', ['model' => $nursery_seeds_sale, 'is_view_only' => true])

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


    @include('components.payments.script', ['model' => $nursery_seeds_sale, 'is_view_only' => true])
@endsection
