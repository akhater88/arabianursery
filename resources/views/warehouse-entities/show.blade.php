@php use App\Models\AgriculturalSupplyStoreUser;use App\Models\SeedType; @endphp
@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <form>
                    <div class="card-body">
                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="agricultural-supply-store-user">اسم أو رقم المزود</label>
                                <select class="form-control select2" id='agricultural-supply-store-user'
                                        name="agricultural_supply_store_user"
                                        disabled
                                        style="width: 100%;">
                                        <option selected value="{{ $nursery_warehouse_entity->agricultural_supply_store_user_id }}">
                                            {{ $nursery_warehouse_entity->agriculturalSupplyStoreUser->optionName }}
                                        </option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="entity-type">نوع المدخل</label>
                                <select class="form-control select2" disabled id='entity-type' name='entity_type'
                                        style="width: 100%;">
                                    @foreach($entity_types as $entity_type)
                                        <option value="{{$entity_type->id}}" @selected($nursery_warehouse_entity->entity_type_id == $entity_type->id)>
                                            {{$entity_type->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <select class="form-control select2" id='seed-type' name='seed_type'
                                        disabled style="width: 100%;">
                                        <option selected value="{{ $nursery_warehouse_entity->entity_id }}">
                                            {{ $nursery_warehouse_entity->entity->name }}
                                        </option>
                                </select>
                            </div>

                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="quantity">الكمية</label>
                                <input id='quantity' type="number" min=0 step="1" name="quantity"
                                       value="{{ $nursery_warehouse_entity->quantity }}"
                                       disabled
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="price">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price" class="form-control"
                                           value="{{ $nursery_warehouse_entity->price }}"
                                           disabled
                                           id="price">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $nursery_warehouse_entity, 'is_view_only' => true])
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#agricultural-supply-store-user').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#seed-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#entity-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })
    </script>

    @include('components.payments.script', ['model' => $nursery_warehouse_entity, 'is_view_only' => true])
@endsection
