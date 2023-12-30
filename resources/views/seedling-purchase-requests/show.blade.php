@php use App\Models\FarmUser;use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <form method="POST" role="form" action="{{$seedling_purchase_request ? route('seedling-purchase-requests.update', $seedling_purchase_request->id) : route('seedling-purchase-requests.store')}}">
                    @csrf

                    @if($seedling_purchase_request)
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seedling-service-select">من أشتال</label>
                                <select class="form-control select2" id='seedling-service-select' name='seedling_service'
                                        disabled style="width: 100%;">
                                        <option selected value="{{ $seedling_purchase_request->seedling_service_id }}">
                                            {{ $seedling_purchase_request->seedlingService->option_name }}
                                        </option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="tray-count">عدد الصواني</label>
                                <input id='tray-count' type="number" min=0 step="1" name="tray_count"
                                       value="{{ $seedling_purchase_request->tray_count }}"
                                       disabled
                                       class="form-control">
                                    <small id="trays-remaining" class="form-text text-muted"></small>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="farm-user-select">اسم أو رقم العميل</label>
                                <select class="form-control select2" id='farm-user-select' name="farm_user"
                                        disabled style="width: 100%;">
                                        <option selected value="{{ $seedling_purchase_request->farm_user_id }}">
                                            {{ $seedling_purchase_request->farmUser->optionName }}
                                        </option>
                                </select>
                            </div>

                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price-per-tray">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price_per_tray" class="form-control"
                                           value="{{ $seedling_purchase_request->price_per_tray }}"
                                           disabled
                                           id="price-per-tray">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار لكل صينية</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $seedling_purchase_request, 'is_view_only' => true])
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#seedling-service-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

        $('#farm-user-select').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

    </script>

    <script>
        let seedlingServiceTrayCount = null;
        let inputTrayCount = 0
        const seedlingServicePurchaseRequest = @json($seedling_purchase_request);

        $(async () => {
            if(seedlingServicePurchaseRequest != null) {
                await updateSeedlingServiceTrayCount(seedlingServicePurchaseRequest.seedling_service_id)
                setRemainingTrays(seedlingServicePurchaseRequest.tray_count)
            }
        })

        function setRemainingTrays(value) {
            inputTrayCount = value

            if(seedlingServiceTrayCount !== null) {
                let remaining = seedlingServiceTrayCount - value;
                remaining = remaining > 0 ? remaining : 0
                document.getElementById('trays-remaining').innerText = `المتبقي: ${remaining}`
            }
        }

        async function updateSeedlingServiceTrayCount(seedlingServiceId) {
            let response = await axios.get(`{{route('seedling-services.get', '')}}/${seedlingServiceId}`);

            seedlingServiceTrayCount = response.data.tray_count - response.data.seedling_purchase_requests.reduce((sum, request) => {
                if(seedlingServicePurchaseRequest != null && seedlingServicePurchaseRequest.id === request.id){
                    return sum
                }

                return sum + request.tray_count
            }, 0)
        }
    </script>

    @include('components.payments.script', ['model' => $seedling_purchase_request, 'is_view_only' => true])
@endsection
