@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-row mb-3">
                    <div class="col-12 col-sm-4">
                        <label for="farm-user-name">اسم المزود</label>
                        <input id='farm-user-name' type="text" name="agricultural_supply_store_user_name"
                               value="{{ request('agricultural_supply_store_user_name') }}"
                               class="form-control">
                    </div>

                    <div class="col-12 col-sm-4">
                        <label for="phone_number">رقم هاتف المزود</label>
                        <input type="text" name="agricultural_supply_store_user_phone_number"
                               value="{{ request('agricultural_supply_store_user_phone_number') }}"
                               class="form-control" id="phone_number">
                    </div>

                    <div class="col-12 col-sm-4">
                        <label for="germination-date">تاريخ</label>
                        <input type="date" name="date"
                               class="form-control"
                               value="{{ request('date') }}"
                               id="germination-date">
                    </div>
                </div>

                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('warehouse-entities.index')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <a href="{{route('warehouse-entities.create')}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i>
                            أضف مدخل إلى المخزن
                        </a>

                        <a href="{{route('warehouse-entities.export')}}" class="btn btn-primary">
                            <i class="fas fa-file-excel"></i>
                            تصدير
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-12 table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم التعريفي</th>
                                <th>اسم المزود</th>
                                <th>رقم هاتف المزود</th>
                                <th>الكمية</th>
                                <th>النوع</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($nursery_warehouse_entities as $nursery_warehouse_entity)
                                    <tr>
                                        <td>{{$nursery_warehouse_entity->id}}</td>
                                        <td>{{$nursery_warehouse_entity->agriculturalSupplyStoreUser->name}}</td>
                                        <td>{{$nursery_warehouse_entity->agriculturalSupplyStoreUser->mobile_number}}</td>
                                        <td>{{$nursery_warehouse_entity->quantity}}</td>
                                        <td>{{$nursery_warehouse_entity->entity->name}}</td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('warehouse-entities.show', $nursery_warehouse_entity->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-info" href="{{route('warehouse-entities.edit', $nursery_warehouse_entity->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$nursery_warehouse_entity->id}}-form" method="post" action="{{route('warehouse-entities.destroy', $nursery_warehouse_entity->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$nursery_warehouse_entity->id}}-btn" title="حذف" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mt-2 mt-sm-0">
                        {{$nursery_warehouse_entities}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @foreach($nursery_warehouse_entities as $nursery_warehouse_entity)
            $("#delete-{{$nursery_warehouse_entity->id}}-btn").click(async function (e) {
                e.preventDefault();

                const result = await Swal.fire({
                    title: "هل انت متأكد؟",
                    text: `هل انت متأكد من الحذف؟`,
                    type: "question",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: "#6A9944",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إلغاء",
                });

                if(result?.value) {
                    $('#delete-{{$nursery_warehouse_entity->id}}-form').submit()
                }
            });
        @endforeach
    </script>

    <script>
    </script>
@endsection
