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
                        <label for="phone_number">رقم الهاتف</label>
                        <input type="text" name="phone_number"
                               value="{{ request('phone_number') }}"
                               class="form-control" id="phone_number">
                    </div>

                    <div class="col-12 col-sm-4">
                        <label for="germination-date">تاريخ التشتيل</label>
                        <input type="date" name="germination_date"
                               class="form-control"
                               value="{{ request('germination_date') }}"
                               id="germination-date">
                    </div>
                </div>

                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('seedling-purchase-requests.index')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <a href="{{route('seedling-purchase-requests.create')}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i>
                            أضف طلب شراء
                        </a>

                        <a href="{{route('seedling-purchase-requests.export')}}" class="btn btn-primary">
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
                                <th>اسم العميل</th>
                                <th>رقم الهاتف</th>
                                <th>عدد الصواني</th>
                                <th>النوع - الصنف</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($seedling_purchase_requests as $seedling_purchase_request)
                                    <tr>
                                        <td>{{$seedling_purchase_request->id}}</td>
                                        <td>{{$seedling_purchase_request->farmUser->name}}</td>
                                        <td>{{$seedling_purchase_request->farmUser->mobile_number}}</td>
                                        <td>{{$seedling_purchase_request->tray_count}}</td>
                                        <td style="min-width:170px">{{"{$seedling_purchase_request->seedlingService->seedType->name} - {$seedling_purchase_request->seedlingService->seed_class}"}}</td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('seedling-purchase-requests.show', $seedling_purchase_request->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-info" href="{{route('seedling-purchase-requests.edit', $seedling_purchase_request->id)}}">
                                                    <i class="fa fa-pencil fas fa-edit fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$seedling_purchase_request->id}}-form" method="post" action="{{route('seedling-purchase-requests.destroy', $seedling_purchase_request->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$seedling_purchase_request->id}}-btn" title="حذف" class="btn btn-danger">
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
                        {{$seedling_purchase_requests}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @foreach($seedling_purchase_requests as $seedling_purchase_request)
            $("#delete-{{$seedling_purchase_request->id}}-btn").click(async function (e) {
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
                    $('#delete-{{$seedling_purchase_request->id}}-form').submit()
                }
            });
        @endforeach
    </script>

    <script>
    </script>
@endsection
