<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> {{ $page_title ?? 'لوحة التحكم' }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href= {{asset("plugins/fontawesome-free/css/all.min.css")}}>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href= {{asset("dist/css/adminlte.min.css")}}>
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href= {{asset("plugins/overlayScrollbars/css/OverlayScrollbars.min.css")}}>

    <!-- DataTables -->
    <link rel="stylesheet" href={{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}>
    <link rel="stylesheet" href={{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}>
    <link rel="stylesheet" href={{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}>

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href={{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}>

    <!-- Select2 -->
    <link rel="stylesheet" href={{asset('plugins/select2/css/select2.min.css')}}>
    <link rel="stylesheet" href={{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}>

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Dropzone JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />

    <link rel="stylesheet" href={{asset('plugins/summernote/summernote-bs4.css')}} />


    <!-- SweetAlert2 -->
    <link rel="stylesheet" href={{asset('plugins/sweetalert2/sweetalert2.min.css')}}>

    <!-- Bootstrap 4 RTL -->
{{--      <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">--}}
    <link
        rel="stylesheet"
        href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"
        integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"
        crossorigin="anonymous" />

{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">--}}
{{--    public/plugins/bootstrap-rtl/css/bootstrap.min.css--}}
{{--    <link rel="stylesheet" href={{asset('plugins/bootstrap-rtl/css/bootstrap.min.css')}}>--}}
{{--    <link rel="stylesheet" href={{asset('plugins/bootstrap-rtl/css/bootstrap-grid.min.css')}}>--}}
{{--    <link rel="stylesheet" href={{asset('plugins/bootstrap-rtl/css/bootstrap-reboot.min.css')}}>--}}

    <!-- Custom style for RTL -->
    <link rel="stylesheet" href={{asset("dist/css/custom.css")}}>
    <script src="https://cdn.amplitude.com/libs/analytics-browser-2.7.3-min.js.gz"></script><script src="https://cdn.amplitude.com/libs/plugin-session-replay-browser-1.2.3-min.js.gz"></script><script src="https://cdn.amplitude.com/libs/plugin-autocapture-browser-0.9.0-min.js.gz"></script><script>window.amplitude.add(window.sessionReplay.plugin({sampleRate: 1})).promise.then(function() {window.amplitude.add(window.amplitudeAutocapturePlugin.plugin());window.amplitude.init('e1b607506616fb36070161d483b20845');});</script>
    @vite(['resources/js/app.js'])

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
        @include('partials.navbar')
    <!-- /.navbar -->
    @if( \Auth::guard('nursery_web')->check() )
    <!-- Main Sidebar Container -->
        @include('partials.sidebar')
    <!-- /.Main Sidebar Container -->
    @elseif(\Auth::guard('admin')->check())
        @include('partials.admin.sidebar')
    @endif

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            @include('partials.breadcrumb')
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
        @include('partials.footer')
    <!-- /.Footer -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src={{asset("plugins/jquery/jquery.min.js")}}></script>
<!-- jQuery UI 1.11.4 -->
<script src={{asset("plugins/jquery-ui/jquery-ui.min.js")}}></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 rtl -->
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<!-- Bootstrap 4 -->
<script src={{asset("plugins/bootstrap/js/bootstrap.bundle.min.js")}}></script>

<!-- DataTables  & Plugins -->
<script src={{asset("plugins/datatables/jquery.dataTables.min.js")}}></script>
<script src={{asset("plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}></script>
<script src={{asset("plugins/datatables-responsive/js/dataTables.responsive.min.js")}}></script>
<script src={{asset("plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}></script>
<script src={{asset("plugins/datatables-buttons/js/dataTables.buttons.min.js")}}></script>
<script src={{asset("plugins/datatables-buttons/js/buttons.bootstrap4.min.js")}}></script>
<script src={{asset("plugins/jszip/jszip.min.js")}}></script>
<script src={{asset("plugins/pdfmake/pdfmake.min.js")}}></script>
<script src={{asset("plugins/pdfmake/vfs_fonts.js")}}></script>
<script src={{asset("plugins/datatables-buttons/js/buttons.html5.min.js")}}></script>
<script src={{asset("plugins/datatables-buttons/js/buttons.print.min.js")}}></script>
<script src={{asset("plugins/datatables-buttons/js/buttons.colVis.min.js")}}></script>

<!-- Select2 -->
<script src={{asset("plugins/select2/js/select2.min.js")}}></script>

<!-- SweetAlert2 -->
<script src={{asset("plugins/sweetalert2/sweetalert2.all.min.js")}}></script>

<!-- overlayScrollbars -->
<script src={{asset("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js")}}></script>

<!-- AdminLTE App -->
<script src={{asset("dist/js/adminlte.js")}}></script>

@yield('scripts')

</body>
</html>
