@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-white">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3">
                        <h4 class="card-title mb-0">قائمة المواسم</h4>
                        <a href="{{ route('seasons.create') }}" class="btn btn-primary mt-2 mt-sm-0">
                            إضافة موسم جديد
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($seasons as $season)
                                    <tr>
                                        <td>{{ $season->name }}</td>
                                        <td>{{ optional($season->start_date)->format('Y-m-d') }}</td>
                                        <td>{{ optional($season->end_date)->format('Y-m-d') }}</td>
                                        <td class="text-left text-sm-right">{{ $season->description }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('seasons.edit', $season) }}" class="btn btn-sm btn-info">تعديل</a>
                                                <form method="POST" action="{{ route('seasons.destroy', $season) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموسم؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">لم يتم إضافة أي موسم بعد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $seasons->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
