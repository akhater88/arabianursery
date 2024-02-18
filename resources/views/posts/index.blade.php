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
                            <a href="{{route('admin.posts.create')}}" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                أضف خدمة منشور
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>عنوان</th>
                                    <th>الكاتب</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{$post->id}}</td>
                                        <td>{{$post->title}}</td>
                                        <td>{{$post->author_name}}</td>
                                        <td>
                                            <div class="col-12" style="min-width:170px">
{{--                                                <a class="btn btn-primary" href="{{route('seedling-services.show', $seedling_service->id)}}">--}}
{{--                                                    <i class="fas fa-eye"></i>--}}
{{--                                                </a>--}}
                                                <a class="btn btn-info" href="{{route('posts.edit', $post->id)}}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
{{--                                                <form class="d-inline" id="delete-{{$seedling_service->id}}-form" method="post" action="{{route('seedling-services.destroy', $seedling_service->id)}}" style="padding: 0">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('delete')--}}
{{--                                                    <button type="submit" id="delete-{{$seedling_service->id}}-btn" title="حذف" class="btn btn-danger">--}}
{{--                                                        <i class="fa fa-trash"></i>--}}
{{--                                                    </button>--}}
{{--                                                </form>--}}
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
                @foreach($posts as $post)
                $("#delete-{{$post->id}}-btn").click(async function (e) {
                    e.preventDefault();

                    const result = await Swal.fire({
                        title: "هل انت متأكد؟",
                        text: `هل انت متأكد من الحذف؟`,
                        type: "question",
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonColor: "#6A9944",
                        confirmButtonText: "تأكيد",
                        cancelButtonText: "إلغاء",
                    });

                    if(result?.value) {
                        $('#delete-{{$post->id}}-form').submit()
                    }
                });
                @endforeach
            </script>

            <script>
            </script>
@endsection
