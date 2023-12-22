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

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
        @include('partials.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
        @include('partials.sidebar')
    <!-- /.Main Sidebar Container -->


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

<!-- overlayScrollbars -->
<script src={{asset("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js")}}></script>
<!-- AdminLTE App -->
<script src={{asset("dist/js/adminlte.js")}}></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src={{asset("dist/js/pages/dashboard.js")}}></script>
<!-- AdminLTE for demo purposes -->
<script src={{asset("dist/js/demo.js")}}></script>
@yield('scripts')

</body>
</html>
