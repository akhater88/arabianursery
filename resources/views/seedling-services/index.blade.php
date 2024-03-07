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
                                            @if(!$seedling_service->reserved)
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
                                            @else
                                                تم الحجز من مشتل:{{ $seedling_service->reservedFromNursery?->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('seedling-services.show', $seedling_service->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @hasrole('nursery-admin')
                                                @if(!$seedling_service->reserved)
                                                    <a class="btn btn-info" href="{{route('seedling-services.edit', $seedling_service->id)}}">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                @endif
                                                @if($seedling_service->type == SeedlingService::TYPE_PERSONAL && !$seedling_service->reserved)
                                                <a class="btn btn-info open-share-with"
                                                   data-id="{{$seedling_service->id}}"
                                                   data-name="{{"{$seedling_service->seedType->name} - {$seedling_service->seed_class}"}}"
                                                   data-tray-count="{{$seedling_service->tray_count}}"
                                                   data-toggle="modal" data-target="#shareSeedlingModal" href="#">
                                                    <i class="fas fa-share"></i>
                                                    @if($seedling_service->share_with_farmers || $seedling_service->share_with_nurseries)
                                                        <i class="fas fa-pen"></i>
                                                    @endif
                                                </a>
                                                @endif
                                                @if(!$seedling_service->reserved)
                                                <form class="d-inline" id="delete-{{$seedling_service->id}}-form" method="post" action="{{route('seedling-services.destroy', $seedling_service->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="delete-{{$seedling_service->id}}-btn" title="حذف" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
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
                    <div class="modal fade" id="shareSeedlingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">عرض اشتال للبيع</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id='share-with-errors' class="alert alert-danger" style="display: none">
                                    </div>
                                    <form id="share_with_form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="share_with">اشتال</label>
                                            <div class="input-group mb-2">
                                                <input id='seedling-name' disabled type="text" name='seed_class' value="..."
                                                class="form-control" >
                                                <input type="hidden" id="seedling-service-id" name="seedling_service_id" class="seedling_service_id">
                                                <input type="hidden" id="trays_remaining" name="trays_remaining" class="trays_remaining">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tray-count">عدد الصواني</label>
                                            <input id='tray-count' type="number" min=0 step="10" name="tray_count"
                                                   value="..."
                                                   disabled
                                                   class="form-control">
                                            <small id="trays-remaining" class="form-text text-muted"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="share_with">مشاركة مع</label>
                                            <div class="input-group mb-2">
                                                <select class="form-control select2" name="share_with" id="share_with" style="width: 100%;">
                                                    <option value="all">الكل</option>
                                                    <option value="farmers">المزارعون</option>
                                                    <option value="nurseries">المشاتل</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="nursery-select-dev">
                                            <label for="share_nurseries">تحديد المشاتل</label>
                                            <div class="input-group mb-2">
                                                <select class="form-control select2" multiple="multiple" name="share_nurseries[]" id="share_nurseries" style="width: 100%;">
                                                    @foreach($nurseries as $key => $name)
                                                        <option value="{{$key}}">{{$name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                    <button type="submit" id="share_button" class="btn btn-primary" form="share_with_form">مشاركة الاشتال</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let seedlingServiceTrayCount = null;
        let inputTrayCount = 0
        const seedlingServicePurchaseRequest = null;

        $(document).on("click", ".open-share-with", async function () {
            $('#alert-success').hide();
            var trayCount= $(this).data('tray-count');
            var name = $(this).data('name');
            var seedlingServiceId = $(this).data('id');
            $(".modal-body #tray-count").val( trayCount );
            $(".modal-body #seedling-name").val( name );
            $(".modal-body #seedling-service-id").val( seedlingServiceId );
            $('#share_with').val(null).trigger('change');
            $('#share_nurseries').val([]);
            $('#share_nurseries').trigger('change');
            await updateSeedlingServiceTrayCount(seedlingServiceId)
            setRemainingTrays(0)

        });

        $('#share_with').change(function(){
            if( $(this).val() == 'farmers'){
                document.getElementById('nursery-select-dev').style.display = 'none';
                document.getElementById('share_nurseries').disabled = true;
                document.getElementById('share_nurseries').required = false;

            } else {
                document.getElementById('nursery-select-dev').style.display = 'block';
                document.getElementById('share_nurseries').disabled = false;
                document.getElementById('share_nurseries').required = true;
            }
        })

        $('#share_with').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#share_nurseries').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        function setRemainingTrays(value) {
            inputTrayCount = value

            if(seedlingServiceTrayCount !== null) {
                let remaining = seedlingServiceTrayCount - value;
                remaining = remaining > 0 ? remaining : 0
                if(remaining == 0){
                    document.getElementById('share_button').innerText = 'الاشتال غير متوفرة';
                    document.getElementById('share_button').setAttribute('disabled', 'disabled');
                }

                $('#trays_remaining').val(remaining);
                document.getElementById('trays-remaining').innerText = `المتبقي: ${remaining}`
            }
        }

        async function updateSeedlingServiceTrayCount(seedlingServiceId) {
            let response = await axios.get(`{{route('seedling-services.get', '')}}/${seedlingServiceId}`);

            let nursuriesId = response.data.shared_with_nurseries.map(a => a.id.toString());

            if(response.data.share_with_farmers){
                $('#share_with').val('farmers').trigger('change');
            }
            if(response.data.share_with_nurseries){
                $('#share_with').val('nurseries').trigger('change');
                $('#share_nurseries').val(nursuriesId);
                $('#share_nurseries').trigger('change');
            }

            if(response.data.share_with_nurseries && response.data.share_with_farmers){
                $('#share_with').val('all').trigger('change');
                $('#share_nurseries').val(nursuriesId);
                $('#share_nurseries').trigger('change');
            }

            seedlingServiceTrayCount = response.data.tray_count - response.data.seedling_purchase_requests.reduce((sum, request) => {

                if(seedlingServicePurchaseRequest != null && seedlingServicePurchaseRequest.id === request.id){
                    return sum
                }

                return sum + request.tray_count
            }, 0)
        }

    </script>
    <script>
        $('#share_with_form').on('submit', function (e) {
            e.preventDefault();

            var seedlingServiceId = $(".modal-body #seedling-service-id").val();
            $.ajax({
                type: "POST",
                url: "{{route('seedling-services.share','')}}/"+seedlingServiceId,
                data: $(this).serialize(),
                success: function (data) {
                    $('#shareSeedlingModal').modal('hide');

                    document.getElementById('alert-success').style.display = 'block'
                    document.getElementById('alert-success').innerText = 'تم مشاركة الاشتال بنجاح'
                    document.getElementById("share_with_form").reset();
                    location.reload();
                },
                error: (response) => {
                    showErrors(response, 'share-with-errors')
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

        $('#share_with').change(function(){
            if( $(this).val() == 'farmers'){
                document.getElementById('nursery-select-dev').style.display = 'none';
                document.getElementById('share_nurseries').disabled = true;
                document.getElementById('share_nurseries').required = false;

            } else {
                document.getElementById('nursery-select-dev').style.display = 'block';
                document.getElementById('share_nurseries').disabled = false;
                document.getElementById('share_nurseries').required = true;
            }
        })


    </script>
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
