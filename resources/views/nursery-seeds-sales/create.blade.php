@php use App\Models\FarmUser;use App\Models\NurseryWarehouseEntity;use App\Models\SeedType; @endphp
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
                <form method="POST" role="form" action="{{route('nursery-seeds-sales.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-row mb-3" id="farm-user-dev">
                            <div class="col-12 col-sm-4">
                                <label for="farm-user-select">اسم أو رقم العميل</label>
                                <div class="input-group mb-2">
                                    <select  class="form-control select2" id='farm-user-select' name="farm_user"
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
                            <div class="col-12 col-sm-4">
                                <label for="warehouse-seeds-select">من بذور المخزن</label>
                                <select class="form-control select2" id='warehouse-seeds-select' name='warehouse_seeds'
                                        required style="width: 100%;">
                                    @if(old('warehouse_seeds'))
                                        <option selected value="{{ old('warehouse_seeds') }}">
                                            {{ NurseryWarehouseEntity::whereId(old('warehouse_seeds'))->first()?->option_name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' type="number" min=0 step="1" name="seed_count"
                                       value="{{ old('seed_count') }}"
                                       required
                                       onchange="setRemainingSeeds(this.value)"
                                       class="form-control">
                                <small id="seed-remaining" class="form-text text-muted"></small>
                            </div>

                        </div>

                        <div class="form-row mb-3">


                            <div class="col-12 col-sm-4">
                                <label for="status">الحالة</label>
                                <select class="form-control select2" required id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" @selected(old('status') == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price" class="form-control"
                                           value="{{ old('price') }}"
                                           required
                                           id="price">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @include('components.payments.view', ['model' => null, 'is_view_only' => false])

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
        $('#warehouse-seeds-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('warehouse-entities.search')}}",
                dataType: 'json',
            }
        })

        let seedEntityCount = null;
        let inputEntityCount = 0


        $('#warehouse-seeds-select').on('select2:select', async (e) => {
            try {
                await updateSeedsSalesCount(e.params.data.id)
                setRemainingSeeds(inputEntityCount)
            } catch (e) {
                throw e;
            }
        })

        function setRemainingSeeds(value) {
            inputEntityCount = value
            console.log('Here AM I  '+seedEntityCount);
            if(seedEntityCount !== null) {
                let remaining = seedEntityCount - value;
                //remaining = remaining > 0 ? remaining : 0
                document.getElementById('seed-remaining').innerText = `المتبقي: ${remaining}`
            }
        }

        async function updateSeedsSalesCount(warehouseEntityId) {
            let response = await axios.get(`{{route('warehouse-entities.get', '')}}/${warehouseEntityId}`);
            console.log(response.data)

            seedEntityCount = response.data.quantity - response.data.seeds_sales.reduce((sum, request) => {

                return sum + request.seed_count
            }, 0)

            console.log(seedEntityCount)
        }
    </script>

    <script>

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



    </script>

    @include('components.payments.script', ['model' => null, 'is_view_only' => false])
@endsection
