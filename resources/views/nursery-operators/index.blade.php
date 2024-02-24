@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="">
                            <a href="{{route('nursery-operators.create')}}" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                أضف مشغل مشتل
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>الاسم</th>
                                    <th>الايميل</th>
                                    <th>رقم الهاتف</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($operators as $operator)
                                    <tr>
                                        <td>{{$operator->id}}</td>
                                        <td>{{$operator->name}}</td>
                                        <td>{{$operator->email}}</td>
                                        <td>{{$operator->country_code.$operator->mobile_number}}</td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
                                                <a class="btn btn-info" href="{{route('nursery-operators.edit', $operator->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form class="d-inline" id="delete-{{$operator->id}}-form" method="post" action="{{route('nursery-operators.destroy', $operator->id)}}" style="padding: 0">
                                                    @csrf
                                                    @method('put')
                                                    @if($operator->status == 1)
                                                        <button type="submit" id="delete-{{$operator->id}}-btn" title="إيقاف" class="btn btn-danger">
                                                            <i class="fa fa-stop-circle"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit" id="delete-{{$operator->id}}-btn" title="تفعيل" class="btn btn-success">
                                                            <i class="fa fa-play-circle"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')

            <script>
                @foreach($operators as $operator)
                $("#delete-{{$operator->id}}-btn").click(async function (e) {
                    e.preventDefault();

                    const result = await Swal.fire({
                        title: "هل انت متأكد؟",
                        text: @if($operator->status == 1) `هل انت متأكد من ايقاف المشغل؟` @else 'هل انت متأكد من تفعيل المشغل؟' @endif,
                        type: "question",
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonColor: "#6A9944",
                        confirmButtonText: "تأكيد",
                        cancelButtonText: "إلغاء",
                    });

                    if(result?.value) {
                        $('#delete-{{$operator->id}}-form').submit()
                    }
                });
                @endforeach
            </script>

            <script>
            </script>
@endsection
