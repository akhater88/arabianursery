@php use App\Models\SeedlingService; @endphp
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
                <form id="seedling-service-form" method="POST" role="form" action="{{route('seedling-services.update', $seedling_service->id)}}">
                    @method('PUT')
                    @csrf

                    <div class="card-body">
                        <div class="row col-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" required id="customRadioInline1" class="custom-control-input"
                                       name="type"
                                       disabled
                                       @checked($seedling_service->type == SeedlingService::TYPE_FARMER)
                                       value="{{SeedlingService::TYPE_FARMER}}">
                                <label class="custom-control-label" for="customRadioInline1">إختر عميل</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline mb-3">
                                <input type="radio" id="customRadioInline2" class="custom-control-input" name="type"
                                       disabled
                                       @checked($seedling_service->type == SeedlingService::TYPE_PERSONAL)
                                       value="{{SeedlingService::TYPE_PERSONAL}}">
                                <label class="custom-control-label" for="customRadioInline2">أشتال خاصة مشتل</label>
                            </div>
                        </div>

                        @if(!is_null($seedling_service->farm_user_id))
                            <div class="form-row mb-3" id="farm-user-dev" style="display: none">
                                <div class="col-12 col-sm-4">
                                    <label for="farm-user-select">اسم أو رقم العميل</label>
                                    <select disabled class="form-control select2" id='farm-user-select' name="farm_user"
                                            style="width: 100%;">
                                            <option selected value="{{ $seedling_service->farm_user_id }}">
                                                {{ $seedling_service->farmUser->optionName }}
                                            </option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="tray-count">عدد الصواني</label>
                                <input id='tray-count' type="number" min=0 step="1" name="tray_count"
                                       value="{{ old('tray_count', $seedling_service) }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-type">نوع البذار</label>
                                <select class="form-control select2" id='seed-type' name='seed_type'
                                        disabled style="width: 100%;">
                                        <option selected value="{{$seedling_service->seed_type_id }}">
                                            {{ $seedling_service->seedType->name }}
                                        </option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="seed-class">الصنف</label>
                                <input id='seed-class' disabled type="text" name='seed_class' value="{{ $seedling_service->seed_class }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="seed-count">عدد البذور</label>
                                <input id='seed-count' disabled type="number" min=0 step="1" name="seed_count"
                                       value="{{ $seedling_service->seed_count }}"
                                       required
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="germination-rate">نسبة الإنبات</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 max="100" step="1" name="germination_rate"
                                           value="{{ old('germination_rate', $seedling_service) }}"
                                           class="form-control" id="germination-rate">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="germination-period">مدة التشتيل</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 max="100" step="1" name="germination_period"
                                           class="form-control"
                                           value="{{ old('germination_period', $seedling_service) }}"
                                           required
                                           id="germination-period">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">بالأيام</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="greenhouse-number">رقم البيت</label>
                                <input id='greenhouse-number' type="number" min=0 step="1" name="greenhouse_number"
                                       value="{{ old('greenhouse_number', $seedling_service) }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="tunnel-greenhouse-number">رقم القوس</label>
                                <input id='tunnel-greenhouse-number' type="number" min=0 step="1"
                                       name="tunnel_greenhouse_number"
                                       value="{{ old('tunnel_greenhouse_number', $seedling_service) }}"
                                       class="form-control">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="status">الحالة</label>
                                <select class="form-control select2" required id='status' name='status'
                                        style="width: 100%;">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status}}" @selected(old('status', $seedling_service?->status->value) == $status)>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="price-per-tray">السعر</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="price_per_tray" class="form-control"
                                           value="{{ old('price_per_tray', $seedling_service) }}"
                                           required
                                           id="price-per-tray">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار لكل صينية</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="additional-cost">تكاليف إضافية</label>
                                <div class="input-group mb-2">
                                    <input type="number" min=0 step="0.01" name="additional_cost" class="form-control"
                                           value="{{ old('additional_cost', $seedling_service) }}"
                                           id="additional-cost">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">دينار</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12">
                                <label for="document">الصور</label>
                                <div class="needsclick dropzone" id="document-dropzone">
                                </div>

                                <div id='errors-div' class="alert alert-danger" style="display: none">
                                </div>
                            </div>
                        </div>

                        @include('components.payments.view', ['model' => $seedling_service, 'is_view_only' => false])

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route('seedling-services.store-media') }}',
            maxFilesize: 2, // MB
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png",
            addRemoveLinks: true,
            dictFileTooBig: `حجم الصورة أكبر من ٢ ميغا`,
            dictRemoveFile: "احذف",
            dictDefaultMessage: 'أضف صور هنا',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('#seedling-service-form').append('<input type="hidden" name="images[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }

                $('#seedling-service-form').find('input[name="images[]"][value="' + name + '"]').remove()
            },
            error(file, response) {
                if (file.previewElement) {
                    file.previewElement.classList.add("dz-error");
                    if (typeof response !== "string" && response.message) {
                        response = response.message;
                    }
                    for (let node of file.previewElement.querySelectorAll(
                        "[data-dz-errormessage]"
                    )) {
                        node.textContent = response;
                    }

                    document.getElementById('errors-div').innerText = response;
                    document.getElementById('errors-div').style.display = 'block';
                }
            },
            init: function () {
                this.on('maxfilesreached', function(files) {
                    this.removeEventListeners();
                    files.slice(this.options.maxFiles).forEach(file => this.removeFile(file))

                    this.element.style.cursor = "not-allowed";
                    this.hiddenFileInput.style.cursor = "not-allowed";
                    this.hiddenFileInput.disabled = true;
                });

                this.on('removedfile', function (file) {
                    if(this.files.length < this.options.maxFiles) {
                        this.setupEventListeners();

                        this.element.style.cursor = "pointer";
                        this.hiddenFileInput.style.cursor = "pointer";
                        this.hiddenFileInput.disabled = false;
                    }
                });

                @if(isset($seedling_service) && $seedling_service->images)
                    @foreach($seedling_service->images as $seedling_service_image)
                    {
                        let image = @json($seedling_service_image);

                        image = {
                            ...image,
                            dataURL: "{{$seedling_service_image->url}}",
                            processing: true,
                            accepted: true,
                            status: 'success',
                            size: 1000,
                        }

                        this.files.push(image)

                        this.emit('addedfile', image)
                        this.emit("processing", image);
                        this.emit("success", image, image, false);
                        this.emit("complete", image);

                        this.createThumbnailFromUrl(image,
                            this.options.thumbnailWidth,
                            this.options.thumbnailHeight,
                            this.options.thumbnailMethod,
                            true,
                             (thumbnail) => {
                                this.emit('thumbnail', image, thumbnail);
                            });

                        uploadedDocumentMap[image.name] = image.name
                    }
                    @endforeach

                    if(this.files.length === this.options.maxFiles){
                        this.emit('maxfilesreached', images)
                    }
                @endif
            }
        }
    </script>

    <script>
        $('#seed-type').select2({
            theme: 'bootstrap4',
            dir: 'rtl',
        })

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
        function displayFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'block'
        }

        function hideFarmUserInput() {
            document.getElementById('farm-user-dev').style.display = 'none'
        }

        if ({{$seedling_service->type == SeedlingService::TYPE_FARMER ? 'true' : 'false'}}) {
            displayFarmUserInput()
        }
    </script>

    @include('components.payments.script', ['model' => $seedling_service, 'is_view_only' => false])
@endsection
