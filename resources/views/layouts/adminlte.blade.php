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

{{-- Modal Filter --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="filterForm" method="GET" target="_blank"> {{-- ✅ Tambahkan form wrapper --}}
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Download Realisasi Non Tender</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <label for="year">Tahun:</label>
          <select name="year" id="year" class="form-control" form="filterForm">
    @isset($years)
        @foreach ($years as $yearOption)
            <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
        @endforeach
    @else
        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
    @endisset
</select>


          <label for="month">Bulan:</label>
          <select name="month" id="month" class="form-control" form="filterForm">
            <option value="ALL" selected>KESELURUHAN</option>
            @foreach (range(1, 12) as $month)
              <option value="{{ $month }}">{{ strtoupper(getMonthName($month)) }}</option>
            @endforeach
          </select>

          <label for="day" id="dayLabel" style="display: none;">Tanggal:</label>
          <select name="day" id="day" class="form-control" style="display: none;" form="filterForm">
            <option value="ALL">KESELURUHAN</option>
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="viewButton">Lihat</button>
          <button type="button" class="btn btn-success" id="downloadButton">Download</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- Scripts --}}

{{-- jQuery dan jQuery UI --}}
<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  // Fix konflik jQuery UI & Bootstrap
  $.widget.bridge('uibutton', $.ui.button);
</script>

{{-- Bootstrap 4 (dari AdminLTE, sudah termasuk Popper.js) --}}
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- Plugin tambahan --}}
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

{{-- AdminLTE Core --}}
<script src="{{ url('dist/js/adminlte.js') }}"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Set default bulan
    document.getElementById('month').value = 'ALL';

    // Isi tanggal sesuai bulan
    document.getElementById('month').addEventListener('change', function() {
      let month = this.value;
      let daysInMonth = new Date(2024, month, 0).getDate();
      let daySelect = document.getElementById('day');
      let dayLabel = document.getElementById('dayLabel');

      daySelect.innerHTML = '<option value="ALL">KESELURUHAN</option>';
      if (month !== 'ALL') {
        for (let i = 1; i <= daysInMonth; i++) {
          let opt = document.createElement('option');
          opt.value = i;
          opt.textContent = i;
          daySelect.appendChild(opt);
        }
        dayLabel.style.display = 'block';
        daySelect.style.display = 'block';
      } else {
        dayLabel.style.display = 'none';
        daySelect.style.display = 'none';
      }
    });

    // Tombol "Lihat"
    document.getElementById('viewButton').addEventListener('click', function () {
      const form = document.getElementById('filterForm');
      form.action = "{{ route('non-tender.viewPdf') }}";
      form.submit();
    });

    // Tombol "Download"
    document.getElementById('downloadButton').addEventListener('click', function () {
      const form = document.getElementById('filterForm');
      form.action = "{{ route('non-tender.downloadPdf') }}";
      form.submit();
    });
  });
</script>




@stack('script')
</body>

</html>
