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

    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <form method="POST" role="form" action="{{ route('nursery-operators.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="name">الاسم</label>
                                <input id='name' type="text" name='name' value="{{ old('name') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="email">الايميل</label>
                                <input type="text" name="email" required  class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="mobile_number">رقم الموبيل</label>
                                <input type="text" name="mobile_number" required  class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="password">الرقم السري</label>
                                <input type="password" name="password" required  class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="password_confirmation">تاكيد الرقم السري</label>
                                <input type="password" name="password_confirmation" required  class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                                إضافة
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


