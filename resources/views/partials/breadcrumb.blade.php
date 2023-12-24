<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $page_title ?? 'لوحة المعلومات' }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">الرئيسية</a></li>
                <li class="breadcrumb-item active">{{ $page_title ?? 'لوحة المعلومات' }}</li>
            </ol>
        </div>
    </div>
</div>
