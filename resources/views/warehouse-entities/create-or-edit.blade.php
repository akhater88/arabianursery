@php use App\Models\AgriculturalSupplyStoreUser;use App\Models\SeedType; @endphp
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
                <form method="POST" role="form" action="{{$nursery_warehouse_entity ? route('warehouse-entities.update', $nursery_warehouse_entity->id) : route('warehouse-entities.store')}}">
                    @csrf

                    @if($nursery_warehouse_entity)
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="agricultural-supply-store-user">اسم أو رقم المزود</label>
                                <div class="input-group mb-2">
                                    <select class="form-control select2" id='agricultural-supply-store-user'
                                            name="agricultural_supply_store_user"
                                            style="width: 70%;">
                                        @if(old('agricultural_supply_store_user', $nursery_warehouse_entity?->agricultural_supply_store_user_id))
                                            <option selected value="{{ old('agricultural_supply_store_user', $nursery_warehouse_entity?->agricultural_supply_store_user_id) }}">
                                                {{ AgriculturalSupplyStoreUser::find(old('agricultural_supply_store_user', $nursery_warehouse_entity?->agricultural_supply_store_user_id))?->optionName }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="input-group-prepend ">
                                        <div class="btn btn-info" data-toggle="modal" data-target="#addAgriculturalSupplyStoreUser"><i
                                                class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="entity-type">نوع المدخل</label>
                                <select class="form-control select2" required id='entity-type' name='entity_type'
                                        style="width: 100%;">
                                    @foreach($entity_types as $entity_type)
                                        <option value="{{$entity_type->id}}" @selected(old('entity_type', $nursery_warehouse_entity?->entity_type_id) == $entity_type->id)>
                                            {{$entity_type->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <div class="input-group mb-2">
                                    <select class="form-control select2" id='seed-type' name='seed_type'
                                            required style="width: 70%;">
                                        @if(old('seed_type', $nursery_warehouse_entity?->entity_id))
                                            <option selected value="{{ old('seed_type', $nursery_warehouse_entity?->entity_id) }}">
                                                {{ SeedType::find(old('seed_type', $nursery_warehouse_entity?->entity_id))?->name }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="input-group-prepend ">
                                        <div class="btn btn-info" data-toggle="modal" data-target="#addSeedTypeModal"><i
                                                class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="quantity">الكمية</label>
                                <input id='quantity' type="number" min=0 step="1" name="quantity"
                                       value="{{ old('quantity', $nursery_warehouse_entity) }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="price">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price" class="form-control"
                                           value="{{ old('price', $nursery_warehouse_entity) }}"
                                           required
                                           id="price">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $nursery_warehouse_entity])

                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                                {{ $nursery_warehouse_entity ? 'تعديل' : 'إضافة' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!---------------------- Modals ---------------------->

    <!-- Add Agricultural Supply Store User Modal -->
    <div class="modal fade" id="addAgriculturalSupplyStoreUser" tabindex="-1" aria-labelledby="addAgriculturalSupplyStoreUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgriculturalSupplyStoreUserLabel">إضافة مزود جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id='add-agricultural-supply-store-user-errors' class="alert alert-danger" style="display: none">
                    </div>
                    <form id="add-agricultural-supply-store-user-form">
                        @csrf
                        <div class="form-group">
                            <label for="agricultural-supply-store-user-name">الاسم</label>
                            <input required type="text" class="form-control" id="agricultural-supply-store-user-name" name="agricultural_supply_store_user_name">
                        </div>
                        <div class="form-group">
                            <label for="agricultural-supply-store-user-mobile-number">رقم الموبايل</label>
                            <input required type="text" class="form-control" name="agricultural_supply_store_user_mobile_number"
                                   id="agricultural-supply-store-user-mobile-number">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary" form="add-agricultural-supply-store-user-form">إضافة</button>
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
        $('#add-agricultural-supply-store-user-form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{route('agricultural-supply-store-user.quick-store')}}",
                data: $(this).serialize(),
                success: function (data) {
                    $('#addAgriculturalSupplyStoreUser').modal('hide');

                    document.getElementById('alert-success').style.display = 'block'
                    document.getElementById('alert-success').innerText = 'تم إضافة المزود بنجاح'
                    document.getElementById("add-agricultural-supply-store-user-form").reset();

                    if ($('#agricultural-supply-store-user').find("option[value='" + data.id + "']").length) {
                        $('#agricultural-supply-store-user').val(data.id).trigger('change');
                    } else {
                        const newOption = new Option(`${data.name} (${data.mobile_number})`, data.id, true, true);
                        $('#agricultural-supply-store-user').append(newOption).trigger('change');
                    }
                },
                error: (response) => {
                    showErrors(response, 'add-agricultural-supply-store-user-errors')
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
        $('#agricultural-supply-store-user').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('agricultural-supply-store-user.search')}}",
                dataType: 'json',
            }
        })

        $('#seed-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('seed-types.search')}}",
                dataType: 'json',
            }
        })

        $('#entity-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })
    </script>

    @include('components.payments.script', ['model' => $nursery_warehouse_entity])
@endsection
