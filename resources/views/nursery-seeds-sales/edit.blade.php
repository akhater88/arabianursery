@php use App\Models\NurseryWarehouseEntity; @endphp
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
                            <div class="col-12 col-sm-4">
                                <label for="warehouse-seeds-select">من بذور المخزن</label>
                                <select class="form-control select2" id='warehouse-seeds-select' name='warehouse_seeds'
                                        required style="width: 100%;">
                                        <option selected value="{{  $nursery_seeds_sale->nursery_warehouse_entities_id }}">
                                            {{ NurseryWarehouseEntity::whereId($nursery_seeds_sale->nursery_warehouse_entities_id)->first()?->option_name }}
                                        </option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' type="number" min=0 step="1" name="seed_count"
                                       value="{{ $nursery_seeds_sale->seed_count }}"
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
                                            value="{{$status}}" @selected(old('status', $nursery_seeds_sale?->status->value) == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <div class="col-12 col-sm-4">
                                <label for="sold-date">تاريخ بيع البذور</label>
                                <input type="date" name="sold_at"
                                       class="form-control"
                                       value="{{ old('sold_at', $nursery_seeds_sale->sold_at) }}"
                                       id="sold-date">
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
        $('#warehouse-seeds-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
            ajax: {
                url: "{{route('warehouse-entities.search')}}",
                dataType: 'json',
            }
        })

        let seedEntityCount = null;
        let inputEntityCount = $('#seed-count').val()
        $(document).ready(async function () {
            await updateSeedsSalesCount({{$nursery_seeds_sale->nursery_warehouse_entities_id}});
            setRemainingSeeds(inputEntityCount);
        });

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
            if(seedEntityCount !== null) {
                let remaining = seedEntityCount - value;
                //remaining = remaining > 0 ? remaining : 0
                document.getElementById('seed-remaining').innerText = `المتبقي: ${remaining}`
            }
        }

        async function updateSeedsSalesCount(warehouseEntityId) {
            let response = await axios.get(`{{route('warehouse-entities.get', '')}}/${warehouseEntityId}`);

            seedEntityCount = response.data.quantity - response.data.seeds_sales.reduce((sum, request) => {

                return sum + request.seed_count
            }, 0)


            if($('#warehouse-seeds-select').val() == {{$nursery_seeds_sale->nursery_warehouse_entities_id}}) {
                seedEntityCount += {{$nursery_seeds_sale->seed_count}}
            }


        }
    </script>

    @include('components.payments.script', ['model' => $nursery_seeds_sale, 'is_view_only' => false])
@endsection
