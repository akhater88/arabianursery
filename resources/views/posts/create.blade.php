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
                <form method="POST" role="form" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="title">العنوان</label>
                                <input id='title' type="text" name='title' value="{{ old('title') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="image">صورة المنشور</label>
                                <input id='image' type="file" name="image" required class="form-control">
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="author_name">الكاتب</label>
                                <input type="text" name="author_name" placeholder="Author Name" required  class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-12 col-sm-12">
                                <label for="description"> المنشور</label>
                                <textarea id="description" name="description" placeholder="Description" required class="form-control"></textarea>
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

@section('scripts')
    <script src={{asset("plugins/summernote/summernote-bs4.min.js")}}></script>

    <script>
        $(function () {
            // Summernote
            $('#description').summernote()
        })

    </script>
@endsection

