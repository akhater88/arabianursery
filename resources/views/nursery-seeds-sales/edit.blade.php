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
                <form id="nursery-seeds-sale-form" method="POST" role="form" action="{{route('nursery-seeds-sales.update', $nursery_seeds_sale->id)}}">
                    @method('PUT')
                    @csrf

                    <div class="card-body">
                            <div class="form-row mb-3" id="farm-user-dev" >
                                <div class="col-12 col-sm-4">
                                    <label for="farm-user-select">اسم أو رقم العميل</label>
                                    <select class="form-control select2" id='farm-user-select' name="farm_user"
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
                                <select class="form-control select2" required id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" @selected(old('status', $nursery_seeds_sale?->status->value) == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price" class="form-control"
                                           value="{{ old('price', $nursery_seeds_sale) }}"
                                           required
                                           id="price">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $nursery_seeds_sale, 'is_view_only' => false])

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

    @include('components.payments.script', ['model' => $nursery_seeds_sale, 'is_view_only' => false])
@endsection
