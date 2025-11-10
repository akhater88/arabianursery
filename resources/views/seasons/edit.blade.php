@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-white">
                <form method="POST" action="{{ route('seasons.update', $season) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <h4 class="card-title mb-3">تعديل الموسم</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">اسم الموسم</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $season->name) }}" class="form-control" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12 col-md-6">
                                <label for="start_date">تاريخ البداية</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', optional($season->start_date)->format('Y-m-d')) }}" class="form-control" required>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="end_date">تاريخ النهاية</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', optional($season->end_date)->format('Y-m-d')) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $season->description) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('seasons.index') }}" class="btn btn-outline-secondary ml-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
