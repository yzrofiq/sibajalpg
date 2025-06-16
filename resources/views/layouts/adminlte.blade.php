<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SiBAJA')</title>

  <link rel="apple-touch-icon" sizes="180x180" href="{{ url('logo.png') }}"/>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ url('32x32.png') }}"/>
  <link rel="icon" type="image/png" sizes="16x16" href="{{ url('16x16.png') }}"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select 2 -->
  <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ url('plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">

  @stack('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  @include('components.preloader')

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link text-danger" href="{{ route('logout') }}">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <span class="brand-text font-weight-light">SIBAJA</span>
    </a>

    @include('components.sidebar')
  </aside>


  <div class="content-wrapper">
    @yield('content', '')
  </div>
  
  
  <footer class="main-footer">
    <div class="d-block d-md-flex justify-content-between">
      <p>
        &copy; {{ date('Y') }}. <a href="https://bpbjprovlampung.id/">Biro Pengadaan Barang dan Jasa Provinsi Lampung</a>
      </p>
      <div><b>Time</b> <span id="time">{{ date('Y-m-d H:i:s') }}</span>. <b>Server Status</b> <span class="text-success">Good</span>. <b>Version</b> <span>0.1.1</span></div>
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select 2 -->
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ url('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ url('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ url('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ url('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ url('plugins/moment/moment.min.js') }}"></script>
<script src="{{ url('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('dist/js/adminlte.js') }}"></script>
{{-- Sweet Alert --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const today = new Date("{{ getCurrentDateTime() }}")
const timeDiv = document.getElementById('time')

function startTime() {
  today.setSeconds(today.getSeconds() + 1)
  let h = today.getHours()
  let m = today.getMinutes()
  let s = today.getSeconds()
  h = checkTime(h)
  m = checkTime(m)
  s = checkTime(s)
  timeDiv.innerHTML =  h + ":" + m + ":" + s
  setTimeout(startTime, 1000)
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

if( Boolean(timeDiv) ) {
  startTime()
}

</script>

@stack('script')
</body>
</html>
