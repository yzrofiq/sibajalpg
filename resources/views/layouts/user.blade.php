{{-- File: resources/views/layouts/user.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBAJA Provinsi Lampung</title>

    <!-- Fonts & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('css/sibaja.css') }}" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Public Sans', sans-serif;
            background-color: #ffffff;
        }
    </style>
    @stack('head')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
  <div class="container-fluid px-4">

    <!-- Logo -->
    <div class="d-flex align-items-center me-4 sibaja-logo-box shadow-sm">
      <img src="{{ asset('images/sibaja-logo.png') }}" alt="Logo" class="me-2">
      <div class="sibaja-header-text">
        <div class="sibaja-header-title">
          <strong>SiBAJA</strong>
          <span class="text-divider">|</span>
          <span>Provinsi Lampung</span>
        </div>
        <hr class="sibaja-divider">
        <div class="sibaja-subtitle">Sistem Informasi Barang dan Jasa</div>
      </div>
    </div>

    <!-- Toggle Mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Menu -->
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-4 me-auto">

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('tender.list') ? 'active' : '' }}" href="{{ route('tender.list') }}">Tender</a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('non-tender.list') ? 'active' : '' }}" href="{{ route('non-tender.list') }}">Non Tender</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->is('report/ekatalog*') || request()->is('report/tokodaring*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">E-Purchasing</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('report.ekatalog') }}">E-Katalog</a></li>
            <li><a class="dropdown-item" href="{{ route('report.tokodaring') }}">Toko Daring</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('report.rup') ? 'active' : '' }}" href="{{ route('report.rup') }}">RUP</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Summary Report</a>
          <ul class="dropdown-menu">
            <!-- <li><a class="dropdown-item" href="{{ route('tender.strategic') }}">10 Paket Strategis</a></li>
            <li class="dropdown-submenu position-relative">
              <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Reviu Perencanaan PBJ</a>
              <ul class="dropdown-menu border-0 shadow">
                <li><a class="dropdown-item" href="{{ route('report.categorize') }}" target="_blank">Pengelompokan Jenis Pengadaan</a></li>
                <li><a class="dropdown-item" href="{{ route('tender.fund.source') }}" target="_blank">Hasil Sumber Dana</a></li>
                <li><a class="dropdown-item" href="{{ route('report.all') }}" target="_blank">Laporan Keseluruhan</a></li>
              </ul>
            </li> -->
            <li>
  <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterModal">
    Realisasi Non Tender
  </a>
</li>
            <li><a class="dropdown-item" href="{{ route('tender.realization') }}" target="_blank">Realisasi Tender</a></li>
            <!-- <li><a class="dropdown-item" href="{{ route('report.review') }}" target="_blank">Hasil Review</a></li> -->
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Monitoring</a>
          <ul class="dropdown-menu">
            {{-- Kosong untuk saat ini --}}
          </ul>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('report') ? 'active' : '' }}" href="{{ route('report') }}" target="_blank">Grafik Report</a>
        </li> -->
      </ul>

      <!-- User Icon -->
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>

  </div>
</nav>



<!-- KONTEN -->
<main class="mt-0">
    @yield('content')
</main>

<!-- INFORMASI KANTOR + JAM -->
<div style="background-color: #1b2141; color: white; padding: 50px 0 30px 0;">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-start">
    <!-- Info Kontak -->
    <div class="mb-4 mb-md-0">
      <h5 class="fw-bold text-white mb-3">Kantor Kami</h5>
      <p class="mb-1"><i class="bi bi-geo-alt-fill me-2"></i> Alamat: Jl. Wolter Monginsidi No.69, Talang, Kec. Telukbetung Selatan, Kota Bandar Lampung, Lampung 35221</p>
      <p class="mb-1"><i class="bi bi-telephone-fill me-2"></i> Telepon: (0721) 481107</p>
      <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> Email: biropbj@lampungprov.go.id</p>
      <div class="d-flex gap-3 mt-3">
        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
        <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
        <a href="https://www.instagram.com/biropbj?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="text-white fs-4"><i class="bi bi-instagram"></i></a>

      </div>
    </div>

    <!-- Jam Realtime -->
    <div class="d-flex gap-3">
      <div class="bg-primary text-center rounded p-3 shadow" style="min-width: 100px; background-color: #0F2C74 !important;">
        <div id="jam-box" class="fw-bold fs-3">00</div>
        <div class="text-uppercase mt-1" style="font-size: 14px;">JAM</div>
      </div>
      <div class="bg-primary text-center rounded p-3 shadow" style="min-width: 100px; background-color: #0F2C74 !important;">
        <div id="menit-box" class="fw-bold fs-3">00</div>
        <div class="text-uppercase mt-1" style="font-size: 14px;">MENIT</div>
      </div>
      <div class="bg-primary text-center rounded p-3 shadow" style="min-width: 100px; background-color: #0F2C74 !important;">
        <div id="detik-box" class="fw-bold fs-3">00</div>
        <div class="text-uppercase mt-1" style="font-size: 14px;">DETIK</div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Filter --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Download Realisasi Non Tender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
            <label for="year">Tahun:</label>
                <select form="form" name="year" id="year" class="form-control">
    @foreach ($availableYears as $item)
        <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>{{ $item }}</option>
    @endforeach
</select>

                <label for="month">Bulan:</label>
                <select id="month" class="form-control">
                    <option value="ALL" selected>KESELURUHAN</option> <!-- Default memilih Keseluruhan -->
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" >{{ strtoupper(getMonthName($month)) }}</option>
                    @endforeach
                </select>

                <label for="day" id="dayLabel" style="display: none;">Tanggal:</label>
                <select id="day" class="form-control" style="display: none;">
                    <option value="ALL">KESELURUHAN</option>
                    <!-- Tanggal akan ditampilkan setelah memilih bulan -->
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="viewButton">Lihat</button>
                <button type="button" class="btn btn-success" id="downloadButton">Download</button>
            </div>
        </div>
    </div>
</div>


<!-- FOOTER -->
<footer class="text-center p-2 mt-0" style="background-color: #2b3e64; color: white;">
    <p class="mb-0">© {{ date('Y') }} Biro Pengadaan Barang dan Jasa Provinsi Lampung</p>
</footer>



<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Set default value for month dropdown to "Keseluruhan"
    document.getElementById('month').value = 'ALL';

    // Fungsi untuk mengisi dropdown tanggal berdasarkan bulan
    document.getElementById('month').addEventListener('change', function() {
        let month = this.value;
        let daysInMonth = new Date(2024, month, 0).getDate(); // Jumlah hari dalam bulan
        let daySelect = document.getElementById('day');
        let dayLabel = document.getElementById('dayLabel');

        daySelect.innerHTML = '<option value="ALL">KESSELURUHAN</option>'; // Default

        // Isi dropdown tanggal berdasarkan bulan
        if (month != 'ALL') {
            for (let i = 1; i <= daysInMonth; i++) {
                let option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                daySelect.appendChild(option);
            }

            // Menampilkan label dan dropdown tanggal setelah bulan dipilih
            dayLabel.style.display = 'block';
            daySelect.style.display = 'block';
        } else {
            // Jika bulan "Keseluruhan", sembunyikan dropdown tanggal
            dayLabel.style.display = 'none';
            daySelect.style.display = 'none';
        }
    });

    // Fungsi untuk tombol "Lihat"
    document.getElementById('viewButton').addEventListener('click', function() {
        let year = document.getElementById('year').value;
        let month = document.getElementById('month').value;
        let day = document.getElementById('day').value;

        // Arahkan ke tampilan PDF sesuai dengan filter
        window.location.href = `/non-tender/view-pdf?year=${year}&month=${month}&day=${day}`;
    });

    // Fungsi untuk tombol "Download"
    document.getElementById('downloadButton').addEventListener('click', function() {
        let year = document.getElementById('year').value;
        let month = document.getElementById('month').value;
        let day = document.getElementById('day').value;

        // Unduh PDF sesuai dengan filter
        window.location.href = `/non-tender/download-pdf?year=${year}&month=${month}&day=${day}`;
    });
});
</script>

@stack('scripts')
</body>
</html>
