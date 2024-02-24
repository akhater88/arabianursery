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

                        <div class="col-12 col-sm-4">
                            <label for="germination-date">تاريخ التشتيل</label>
                            <input type="date" name="germination_date"
                                   class="form-control"
                                   value="{{ request('germination_date') }}"
                                   id="germination-date">
                        </div>
                    </div>

                    <div class="form-row mb-3 col-12 col-sm-4 pr-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_personal_type"
                                   @checked(request('is_personal_type'))
                                   class="custom-control-input" id="is-personal-type">
                            <label class="custom-control-label" for="is-personal-type">أشتال خاصة مشتل</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('seedling-services.index')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <a href="{{route('seedling-services.create')}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i>
                            أضف خدمة تشتيل
                        </a>
                        @hasrole('nursery-admin')
                        <a href="{{route('seedling-services.export')}}" class="btn btn-primary">
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
                                <th>عدد الصواني</th>
                                <th>النوع - الصنف</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($seedling_services as $seedling_service)
                                    <tr>
                                        <td>{{$seedling_service->id}}</td>
                                        <td>{{$seedling_service->farmUser?->name}}</td>
                                        <td>{{$seedling_service->farmUser?->mobile_number}}</td>
                                        <td>{{$seedling_service->tray_count}}</td>
                                        <td style="min-width:170px">{{"{$seedling_service->seedType->name} - {$seedling_service->seed_class}"}}</td>
                                        <td>
                                            <form id="seedling-service-{{$seedling_service->id}}-status-form">
                                                <select class="form-control" required id='status-{{$seedling_service->id}}' name='status'
                                                        style="min-width:170px">
                                                    @foreach($statuses as $status)
                                                        <option value="{{$status}}" @selected(old('status', $seedling_service->status->value) == $status)>
                                                            {{$status}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('seedling-services.show', $seedling_service->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @hasrole('nursery-admin')
                                                <a class="btn btn-info" href="{{route('seedling-services.edit', $seedling_service->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$seedling_service->id}}-form" method="post" action="{{route('seedling-services.destroy', $seedling_service->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$seedling_service->id}}-btn" title="حذف" class="btn btn-danger">
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
                        {{$seedling_services}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#is-personal-type').click(function (e){
            if(e.target.checked){
                disableFarmUserInputs();
            } else {
                enableFarmUserInputs();
            }
        })

        @if(request('is_personal_type'))
            disableFarmUserInputs();
        @endif

        function disableFarmUserInputs() {
            $('#farm-user-name').val( '' );
            $('#farm-user-name').prop( "disabled", true );

            $('#farm-user-phone-number').val( '' );
            $('#farm-user-phone-number').prop( "disabled", true );
        }

        function enableFarmUserInputs() {
            $('#farm-user-name').prop( "disabled", false );
            $('#farm-user-phone-number').prop( "disabled", false );
        }
    </script>
    <script>
        @foreach($seedling_services as $seedling_service)
            $("#status-{{$seedling_service->id}}").change(async function (e) {
            const result = await Swal.fire({
                    title: "هل انت متأكد؟",
                    text: `هل انت متأكد من تغيير الحالة إلى ${e.target.value} {{$seedling_service->farmUser?->name ? "للعميل {$seedling_service->farmUser->name}" : ""}}؟`,
                    type: "question",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: "#6A9944",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إلغاء",
                });

                if (result?.value) {
                    await axios.put(`{{route('seedling-services.update-status', $seedling_service->id)}}`, {
                        status: e.target.value
                    });
                }
            });

        $("#delete-{{$seedling_service->id}}-btn").click(async function (e) {
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
                $('#delete-{{$seedling_service->id}}-form').submit()
            }
        });
        @endforeach
    </script>

    <script>
    </script>
@endsection
