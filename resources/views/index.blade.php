<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vòng Nhật Hòa</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('admin_asset/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('admin_asset/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('admin_asset/images/favicon.png') }}" />
    @vite('resources/js/app.js')
  </head>
  <body class="with-welcome-text">
    <div id="app" class="container-scroller">
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('admin_asset/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin_asset/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('admin_asset/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('admin_asset/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('admin_asset/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin_asset/js/template.js') }}"></script>
    <script src="{{ asset('admin_asset/js/settings.js') }}"></script>
    <script src="{{ asset('admin_asset/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin_asset/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('admin_asset/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- <script src="{{ asset('admin_asset/js/dashboard.js') }}" defer></script> -->
    <!-- <script src="{{ asset('admin_asset/js/Chart.roundedBarCharts.js') }}"></script> -->
    <!-- End custom js for this page-->
    <script>
      const admin_asset = "{{ asset('admin_asset') }}";
    </script>
  </body>
</html>