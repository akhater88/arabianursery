@php use App\Models\FarmUser;use App\Models\SeedlingService;use App\Models\SeedType; @endphp
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
                <form method="POST" role="form" action="{{route('seedling-services.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row col-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" required id="customRadioInline1" class="custom-control-input"
                                       name="type"
                                       {{ old('type') == SeedlingService::TYPE_FARMER ? 'checked' : '' }}
                                       onclick="displayFarmUserInput()" value="{{SeedlingService::TYPE_FARMER}}">
                                <label class="custom-control-label" for="customRadioInline1">إختر عميل</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline mb-3">
                                <input type="radio" id="customRadioInline2" class="custom-control-input" name="type"
                                       {{ old('type') == SeedlingService::TYPE_PERSONAL ? 'checked' : '' }}
                                       onclick="hideFarmUserInput()" value="{{SeedlingService::TYPE_PERSONAL}}">
                                <label class="custom-control-label" for="customRadioInline2">أشتال خاصة مشتل</label>
                            </div>
                        </div>

                        <div class="form-row mb-3" id="farm-user-dev" style="display: none">
                            <div class="col-12 col-sm-4">
                                <label for="farm-user-select">اسم أو رقم العميل</label>
                                <div class="input-group mb-2">
                                    <select disabled class="form-control select2" id='farm-user-select' name="farm_user"
                                            style="width: 70%;">
                                        @if(old('farm_user'))
                                            <option selected value="{{ old('farm_user') }}">
                                                {{ FarmUser::find(old('farm_user'))?->optionName }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="input-group-prepend ">
                                        <div class="btn btn-info" data-toggle="modal" data-target="#addFarmUserModal"><i
                                                class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="tray-count">عدد الصواني</label>
                                <input id='tray-count' type="number" min=0 step="1" name="tray_count"
                                       value="{{ old('tray_count') }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <div class="input-group mb-2">
                                    <select class="form-control select2" id='seed-type' name='seed_type'
                                            required style="width: 70%;">
                                        @if(old('seed_type'))
                                            <option selected value="{{ old('seed_type') }}">
                                                {{ SeedType::find(old('seed_type'))?->name }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="input-group-prepend ">
                                        <div class="btn btn-info" data-toggle="modal" data-target="#addSeedTypeModal"><i
                                                class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-class">الصنف</label>
                                <input id='seed-class' type="text" name='seed_class' value="{{ old('seed_class') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' type="number" min=0 step="1" name="seed_count"
                                       value="{{ old('seed_count') }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="germination-rate">نسبة الإنبات</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 max="100" step="1" name="germination_rate"
                                           value="{{ old('germination_rate') }}"
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
                                           value="{{ old('germination_period') }}"
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
                                       value="{{ old('greenhouse_number') }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="tunnel-greenhouse-number">رقم القوس</label>
                                <input id='tunnel-greenhouse-number' type="number" min=0 step="1"
                                       name="tunnel_greenhouse_number"
                                       value="{{ old('tunnel_greenhouse_number') }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="status">الحالة</label>
                                <select class="form-control select2" required id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" {{old('status') == $status ? 'selected' : ''}}>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price-per-tray">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price_per_tray" class="form-control"
                                           value="{{ old('price_per_tray') }}"
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
                                           value="{{ old('additional_cost') }}"
                                           id="additional-cost">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view')

                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                                إضافة
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!---------------------- Modals ---------------------->

    <!-- Add Farm User Modal -->
    <div class="modal fade" id="addFarmUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">إضافة عميل جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id='add-farm-user-errors' class="alert alert-danger" style="display: none">
                    </div>
                    <form id="add-farm-user-form">
                        @csrf
                        <div class="form-group">
                            <label for="farm-user-name">الاسم</label>
                            <input required type="text" class="form-control" id="farm-user-name" name="farm_user_name">
                        </div>
                        <div class="form-group">
                            <label for="farm-user-mobile-number">رقم الموبايل</label>
                            <input required type="text" class="form-control" name="farm_user_mobile_number"
                                   id="farm-user-mobile-number">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary" form="add-farm-user-form">إضافة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Seed Type Modal -->
    <div class="modal fade" id="addSeedTypeModal" tabindex="-1" aria-labelledby="addSeedTypeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSeedTypeModalLabel">إضافة نوع جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id='add-seed-type-errors' class="alert alert-danger" style="display: none">
                    </div>
                    <form id="add-seed-type-form">
                        @csrf
                        <div class="form-group">
                            <label for="seed-type-name">الاسم</label>
                            <input required type="text" class="form-control" name="seed_type_name" id="seed-type-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary" form="add-seed-type-form">إضافة</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#add-farm-user-form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{route('farmer.quick-store')}}",
                data: $(this).serialize(),
                success: function (data) {
                    $('#addFarmUserModal').modal('hide');

                    document.getElementById('alert-success').style.display = 'block'
                    document.getElementById('alert-success').innerText = 'تم إضافة عميل بنجاح'
                    document.getElementById("add-farm-user-form").reset();

                    if ($('#farm-user-select').find("option[value='" + data.id + "']").length) {
                        $('#farm-user-select').val(data.id).trigger('change');
                    } else {
                        const newOption = new Option(`${data.name} (${data.mobile_number})`, data.id, true, true);
                        $('#farm-user-select').append(newOption).trigger('change');
                    }
                },
                error: (response) => {
                    showErrors(response, 'add-farm-user-errors')
                }
            });
        });

        $('#add-seed-type-form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{route('seed-types.store')}}",
                data: $(this).serialize(),
                success: function (data) {
                    $('#addSeedTypeModal').modal('hide');

                    document.getElementById('alert-success').style.display = 'block'
                    document.getElementById('alert-success').innerText = 'تم إضافة النوع بنجاح'
                    document.getElementById("add-seed-type-form").reset();

                    if ($('#seed-type').find("option[value='" + data.id + "']").length) {
                        $('#seed-type').val(data.id).trigger('change');
                    } else {
                        const newOption = new Option(data.name, data.id, true, true);
                        $('#seed-type').append(newOption).trigger('change');
                    }
                },
                error: (response) => {
                    showErrors(response, 'add-seed-type-errors')
                }
            });
        });

        function showErrors(response, divId) {
            let errors = '<ul>'

            Object.values(response.responseJSON.errors).forEach(error => {
                errors += `<li> ${error[0]} </li>`
            })

            errors += '</ul>'

            document.getElementById(divId).style.display = 'block';
            document.getElementById(divId).innerHTML = errors;
        }
    </script>

    <script>
        $('#seed-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('seed-types.search')}}",
                dataType: 'json',
            }
        })

        $('#farm-user-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('farmer.search')}}",
                dataType: 'json',
            }
        })

        $('#status').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })
    </script>

    <script>
        function displayFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'block'
            document.getElementById('farm-user-select').disabled = false
            document.getElementById('farm-user-select').required = true
        }

        function hideFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'none'
            document.getElementById('farm-user-select').disabled = true
            document.getElementById('farm-user-select').required = false
        }

        if ({{old('type') == SeedlingService::TYPE_FARMER ? 'true' : 'false'}}) {
            displayFarmUserInput()
        }
    </script>

    @include('components.payments.script')
@endsection
