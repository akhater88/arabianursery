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
                            <label for="farm-user-name">اسم العميل</label>
                            <input id='farm-user-name' type="text" name="farm_user_name"
                                   value="{{ request('farm_user_name') }}"
                                   class="form-control">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="farm-user-phone-number">رقم الهاتف</label>
                            <input type="text" name="farm_user_phone_number"
                                   value="{{ request('farm_user_phone_number') }}"
                                   class="form-control" id="farm-user-phone-number">
                        </div>

                    </div>

                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('nursery-seeds-sales.index')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <a href="{{route('nursery-seeds-sales.create')}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i>
                            أضف بيع بذور
                        </a>
                        @hasrole('nursery-admin')
                        <a href="{{route('nursery-seeds-sales.export')}}" class="btn btn-primary">
                            <i class="fas fa-file-excel"></i>
                            تصدير
                        </a>
                        @endhasrole
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-12 table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم التعريفي</th>
                                <th>اسم العميل</th>
                                <th>رقم الهاتف</th>
                                <th>عدد البذور</th>
                                <th>النوع - الصنف</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($nursery_seeds_sales as $nursery_seeds_sale)
                                    <tr>
                                        <td>{{$nursery_seeds_sale->id}}</td>
                                        <td>{{$nursery_seeds_sale->farmUser?->name}}</td>
                                        <td>{{$nursery_seeds_sale->farmUser?->mobile_number}}</td>
                                        <td>{{$nursery_seeds_sale->seed_count}}</td>
                                        <td style="min-width:170px">{{"{$nursery_seeds_sale->nurseryWarehouseEntityService->option_name}"}}</td>
                                        <td>
                                            <form id="seedling-service-{{$nursery_seeds_sale->id}}-status-form">
                                                <select class="form-control" required id='status-{{$nursery_seeds_sale->id}}' name='status'
                                                        style="min-width:170px">
                                                    @foreach($statuses as $status)
                                                        <option value="{{$status}}" @selected(old('status', $nursery_seeds_sale->status->value) == $status)>
                                                            {{$status}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
{{--                                                <a class="btn btn-primary" href="{{route('nursery-seeds-sales.show', $nursery_seeds_sale->id)}}">--}}
{{--                                                    <i class="fas fa-eye"></i>--}}
{{--                                                </a>--}}
                                                @hasrole('nursery-admin')
                                                <a class="btn btn-info" href="{{route('nursery-seeds-sales.edit', $nursery_seeds_sale->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$nursery_seeds_sale->id}}-form" method="post" action="{{route('nursery-seeds-sales.destroy', $nursery_seeds_sale->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$nursery_seeds_sale->id}}-btn" title="حذف" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endhasrole
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mt-2 mt-sm-0">
                        {{$nursery_seeds_sales}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @foreach($nursery_seeds_sales as $nursery_seeds_sale)
            $("#status-{{$nursery_seeds_sale->id}}").change(async function (e) {
            const result = await Swal.fire({
                    title: "هل انت متأكد؟",
                    text: `هل انت متأكد من تغيير الحالة إلى ${e.target.value} {{$nursery_seeds_sale->farmUser?->name ? "للعميل {$nursery_seeds_sale->farmUser->name}" : ""}}؟`,
                    type: "question",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: "#6A9944",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إلغاء",
                });

                if (result?.value) {
                    await axios.put(`{{route('nursery-seeds-sales.update-status', $nursery_seeds_sale->id)}}`, {
                        status: e.target.value
                    });
                }
            });

        $("#delete-{{$nursery_seeds_sale->id}}-btn").click(async function (e) {
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
                $('#delete-{{$nursery_seeds_sale->id}}-form').submit()
            }
        });
        @endforeach
    </script>

    <script>
    </script>
@endsection
