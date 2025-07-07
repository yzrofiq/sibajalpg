<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SiBAJA')</title>

  {{-- Favicon --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ url('logo.png') }}"/>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ url('32x32.png') }}"/>
  <link rel="icon" type="image/png" sizes="16x16" href="{{ url('16x16.png') }}"/>

  {{-- Styles --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/jqvmap/jqvmap.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">

  @stack('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  {{-- Preloader --}}
  @include('components.preloader')

  {{-- ===== NAVBAR ===== --}}
  @hasSection('navbar')
    {{-- Gunakan navbar override --}}
    @yield('navbar')
  @else
    {{-- Default Admin Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        @yield('navbar-extra')
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link text-danger" href="{{ route('logout') }}">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
  @endif

  {{-- ===== SIDEBAR ===== --}}
@if(trim($__env->yieldContent('hideSidebar')) == 'true')
  {{-- Tidak tampilkan sidebar --}}
  <style>
    .content-wrapper, .main-footer {
      margin-left: 0 !important;
    }
  </style>
@else
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
      <span class="brand-text font-weight-light">SIBAJA</span>
    </a>
    @include('components.sidebar') {{-- <- sidebar adminmu di sini --}}
  </aside>
@endif


  {{-- ===== KONTEN UTAMA ===== --}}
  <div class="content-wrapper pt-4">
    @yield('content')
  </div>

  {{-- ===== FOOTER ===== --}}
  @hasSection('footer')
    @yield('footer')
  @else
    <footer class="main-footer">
      <div class="d-block d-md-flex justify-content-between">
        <p>
          &copy; {{ date('Y') }}. <a href="https://bpbjprovlampung.id/">Biro Pengadaan Barang dan Jasa Provinsi Lampung</a>
        </p>
        <div>
          <b>Time</b> <span id="time">{{ date('Y-m-d H:i:s') }}</span>.
          <b>Server Status</b> <span class="text-success">Good</span>.
          <b>Version</b> <span>0.1.1</span>
        </div>
      </div>
    </footer>
  @endif

  {{-- Control Sidebar (Kosong) --}}
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

{{-- Scripts --}}
<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ url('plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ url('plugins/sparklines/sparkline.js') }}"></script>
<script src="{{ url('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ url('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<script src="{{ url('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ url('plugins/moment/moment.min.js') }}"></script>
<script src="{{ url('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ url('dist/js/adminlte.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  const today = new Date("{{ getCurrentDateTime() }}");
  const timeDiv = document.getElementById('time');

  function startTime() {
    today.setSeconds(today.getSeconds() + 1);
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();
    h = checkTime(h);
    m = checkTime(m);
    s = checkTime(s);
    timeDiv.innerHTML = h + ":" + m + ":" + s;
    setTimeout(startTime, 1000);
  }

  function checkTime(i) {
    return (i < 10) ? "0" + i : i;
  }

  if (timeDiv) {
    startTime();
  }
</script>

@stack('script')
</body>
</html>
