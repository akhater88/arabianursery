@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    <div id='alert-success' class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
    </div>
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
                        @hasrole('nursery-admin')
                        <a href="{{route('seedling-purchase-requests.export')}}" class="btn btn-primary">
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
                                <th>النوع - الصنف</th>
                                <th>اسم العميل</th>
                                <th>رقم الهاتف</th>
                                <th>عدد الصواني</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($seedling_purchase_requests as $seedling_purchase_request)
                                    <tr>
                                        <td style="min-width:170px">{{"{$seedling_purchase_request->seedlingService->seedType->name} - {$seedling_purchase_request->seedlingService->seed_class}"}}</td>
                                        <td>
                                            @if($seedling_purchase_request->requestedbyUser::class =='App\Models\Nursery')
                                                مشتل:
                                            @else
                                                مزارع:
                                            @endif
                                                {{$seedling_purchase_request->requestedbyUser->name}}
                                        </td>
                                        <td>
                                            @if($seedling_purchase_request->requestedbyUser::class =='App\Models\Nursery')
                                                مشتل: {{$seedling_purchase_request->requestedbyUser->nurseryUsers[0]->mobile_number}}
                                            @else
                                                مزارع:  {{$seedling_purchase_request->farmUser->mobile_number}}
                                            @endif

                                        </td>
                                        <td>{{$seedling_purchase_request->tray_count}}</td>
                                        <td>
                                            @if($seedling_purchase_request->status != 1)
                                            <select class="form-control request_status" required data-seedling-purchase-request-id="{{$seedling_purchase_request->id}}" name='status'
                                                    style="min-width:170px">
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{$key}}" @selected(old('status', $seedling_purchase_request->status) == $key) >
                                                        {{$status}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @else
                                                تم الحجز
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('seedling-purchase-requests.show', $seedling_purchase_request->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @hasrole('nursery-admin')
                                                <a class="btn btn-info" href="{{route('seedling-purchase-requests.edit', $seedling_purchase_request->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$seedling_purchase_request->id}}-form" method="post" action="{{route('seedling-purchase-requests.destroy', $seedling_purchase_request->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$seedling_purchase_request->id}}-btn" title="حذف" class="btn btn-danger">
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
                        {{$seedling_purchase_requests}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // $('.request_status').select2({
        //     theme: 'bootstrap4',
        //     dir: 'rtl',
        // })
        @foreach($seedling_purchase_requests as $seedling_purchase_request)
            $(".request_status").change(async function (e) {
                e.preventDefault();

                const result = await Swal.fire({
                    title: "طلب تعديل",
                    text: `هل انت متأكد من تعديل حالة الطلب؟`,
                    type: "question",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: "#6A9944",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إلغاء",
                });

                var seedling_purchase_request_id= $(this).data('seedling-purchase-request-id');
                var status = $(this).val();
                var csrf = $('[name="_token"]').val();


                if(result?.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('seedling-purchase-request.status-update','')}}/"+seedling_purchase_request_id,
                        data: {
                            status : status,
                            _token: csrf
                        },
                        success: function (data) {
                            document.getElementById('alert-success').style.display = 'block'
                            document.getElementById('alert-success').innerText = 'تم تعديل حالة الاشتال بنجاح'
                            location.reload();
                        },
                        error: (response) => {
                            showErrors(response, 'update-with-errors')
                        }
                    });
                }
            });
        @endforeach
    </script>

    <script>
    </script>
@endsection
