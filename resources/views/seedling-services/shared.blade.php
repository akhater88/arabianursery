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
                                <th>توقع استلام </th>
                                <th>حجز اشتال</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($seedlings as $seedling)
                                    @php
                                        $seedlingAge = $seedling->created_at->diffInDays(\Carbon\Carbon::now());
                                        $handedPeriod = $seedling->germination_period - $seedlingAge;
                                        $handedDate = \Carbon\Carbon::now()->addDays($handedPeriod)->format('d-m-Y');
                                    @endphp
                                    @if($handedPeriod > -20 )
                                    <tr>
                                        <td>{{$seedling->id}}</td>
                                        <td>{{$seedling->nursery->name}}</td>
                                        <td>{{$seedling->nursery->nurseryUsers[0]->mobile_number}}</td>
                                        <td>{{$seedling->tray_count - $seedling->seedling_purchase_requests_sum_tray_count}}</td>
                                        <td style="min-width:170px">{{"{$seedling->seedType->name} - {$seedling->seed_class}"}}</td>
                                        <td>
                                            @if($handedPeriod >= 4 )
                                             بعد: {{$handedPeriod}} يوم
                                        <br/>
                                            بتاريخ:  {{ $handedDate }}
                                            @elseif($handedPeriod >= -20)
                                                جاهز للتسليم
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                @if(($seedling->tray_count - $seedling->seedling_purchase_requests_sum_tray_count) > 0)
                                                <a class="btn btn-info open-share-with"
                                                   data-id="{{$seedling->id}}"
                                                   data-name="{{"{$seedling->seedType->name} - {$seedling->seed_class}"}}"
                                                   data-nursery-name = "{{$seedling->nursery->name}}"
                                                   data-tray-count="{{$seedling->tray_count - $seedling->seedling_purchase_requests_sum_tray_count}}"
                                                   data-toggle="modal" data-target="#requestSeedlingModal" href="#">
                                                    <i class="fas  fa-truck-moving"></i>
                                                </a>
                                                @else
                                                    <a class="btn btn-info open-share-with disabled" disabled="" href="#">
                                                        <i class="fas fa-truck-pickup"></i>
                                                        مباع
                                                    </a>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="requestSeedlingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">طلب حجز اشتال من مشتل  <span id="nursery_name"></span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id='share-with-errors' class="alert alert-danger" style="display: none">
                                    </div>
                                    <form id="reserve_form">
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
                                            <input id='tray-count' type="number" min=0 step="10" max="" name="tray_count"
                                                   value=""
                                                   class="form-control">
                                            <small id="trays-remaining" class="form-text text-muted"></small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                            <button type="submit" id="reserve_button" class="btn btn-primary" form="reserve_form">حجز الاشتال</button>
                                        </div>
                                    </form>
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
            var nurseryName = $(this).data('nursery-name');
            $(".modal-body #tray-count").val( trayCount );
            $(".modal-body #tray-count").attr('max', trayCount );
            $(".modal-body #seedling-name").val( name );
            $(".modal-body #seedling-service-id").val( seedlingServiceId );
            $(".modal-content #nursery_name").html(nurseryName);
            $('#share_with').val(null).trigger('change');
            $('#share_nurseries').val([]);
            $('#share_nurseries').trigger('change');

        });

        $('#reserve_form').on('submit', function (e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{route('seedling-services.reserve.request')}}",
                data: $(this).serialize(),
                success: function (data) {
                    $('#requestSeedlingModal').modal('hide');

                    document.getElementById('alert-success').style.display = 'block'
                    document.getElementById('alert-success').innerText = 'تم ارسال طلب حجزالاشتال بنجاح'
                    document.getElementById("reserve_form").reset();
                    location.reload()
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

    </script>
@endsection
