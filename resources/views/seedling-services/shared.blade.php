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
                            <label for="nursery_name">اسم المشتل</label>
                            <input id='nursery_name' type="text" name="nursery_name"
                                   value="{{ request('nursery_name') }}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('shared-seedlings')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="col-12 table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم التعريفي</th>
                                <th>اسم المشتل</th>
                                <th>رقم الهاتف</th>
                                <th>عدد الصواني المتاحة</th>
                                <th>النوع - الصنف</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($seedlings as $seedling)
                                    <tr>
                                        <td>{{$seedling->id}}</td>
                                        <td>{{$seedling->nursery->name}}</td>
                                        <td>{{$seedling->nursery->nurseryUsers[0]->mobile_number}}</td>
                                        <td>{{$seedling->tray_count - $seedling->seedling_purchase_requests_sum_tray_count}}</td>
                                        <td style="min-width:170px">{{"{$seedling->seedType->name} - {$seedling->seed_class}"}}</td>
                                        <td>
                                            {{$seedling->status}}
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-primary" href="{{route('seedling-services.show', $seedling->id)}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endsection
