<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $page_title ?? 'لوحة المعلومات' }}</h1>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-primary float-right rounded-circle" href="{{ url()->previous() }}">
                <i class="fas fa-arrow-circle-left"></i>
            </a>
        </div>
    </div>
</div>
