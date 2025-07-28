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
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('32x32.png') }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('16x16.png') }}"/>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Public Sans', sans-serif;
            background-color: #ffffff;
        }
        /* Submenu turun ke bawah */
          .dropdown-submenu .dropdown-menu {
            top: 100% !important;
            left: 0 !important;
            margin-top: 0.2rem;
          }

          /* Konsistensi ukuran dan background */
          .dropdown-menu {
            background-color: #ffffff;
            min-width: 220px;
            border: 1px solid #ddd;
          }

          /* Rounded item & hover style */
          .dropdown-item {
            border-radius: 0.375rem;
            transition: background-color 0.2s ease;
          }
          .dropdown-item:hover {
            background-color: #f1f1f1;
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
        <li>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterModal">
                Realisasi Non Tender
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterTenderModal">
                Realisasi Tender
            </a>
        </li>
    </ul>
</li>

       <!-- Dropdown: Monitoring -->
       <li class="nav-item dropdown position-relative">
  <a class="nav-link dropdown-toggle {{ request()->is('monitoring*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Monitoring
  </a>
  <ul class="dropdown-menu shadow rounded-3 p-1 bg-white">
    <li>
      <a class="dropdown-item px-3 py-2 rounded-2" href="{{ route('monitoring.realisasi.satker') }}">
        Realisasi Pengadaan
      </a>
    </li>
    <li>
      <a class="dropdown-item px-3 py-2 rounded-2" href="{{ route('monitoring.rekap.realisasi-berlangsung') }}">
        Realisasi Berlangsung
      </a>
    </li>
    <li>
      <a class="dropdown-item px-3 py-2 rounded-2" href="{{ route('monitoring.rekap.realisasi') }}">
        Realisasi Selesai
      </a>
    </li>

    <!-- Sub-menu: Monitoring Belum Input -->
    <li class="dropdown-submenu position-relative">
      <a class="dropdown-item dropdown-toggle px-3 py-2 rounded-2" href="#" data-bs-toggle="dropdown">
        Monitoring Belum Input
      </a>
      <ul class="dropdown-menu shadow rounded-3 mt-1 bg-white">
        <li>
          <a class="dropdown-item px-3 py-2 rounded-2" href="{{ route('monitoring.kontrak') }}">
            Kontrak Belum Input - Tender
          </a>
        </li>
        <li>
          <a class="dropdown-item px-3 py-2 rounded-2" href="{{ route('monitoring.kontrak.non_tender') }}">
            Kontrak Belum Input - Non Tender
          </a>
        </li>
      </ul>
    </li>
  </ul>
</li>
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
{{-- Modal Filter Non Tender --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Download Realisasi Non Tender</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label for="nonYear">Tahun:</label>
        <select name="nonYear" id="nonYear" class="form-control">
          @foreach ($nonTenderYears as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
          @endforeach
        </select>

        <label for="month">Bulan:</label>
        <select id="month" class="form-control">
          <option value="ALL">KESELURUHAN</option>
          @foreach (range(1, 12) as $month)
            <option value="{{ $month }}">{{ strtoupper(getMonthName($month)) }}</option>
          @endforeach
        </select>

        <label for="day" id="dayLabel" style="display: none;">Tanggal:</label>
        <select id="day" class="form-control" style="display: none;">
          <option value="ALL">KESELURUHAN</option>
        </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="viewButton">Lihat</button>
        <button class="btn btn-success" id="downloadButton">Download</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Filter Tender --}}
<div class="modal fade" id="filterTenderModal" tabindex="-1" aria-labelledby="filterTenderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Download Realisasi Tender</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label for="tenderYear">Tahun:</label>
        <select name="tenderYear" id="tenderYear" class="form-control">
          @foreach ($tenderYears as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
          @endforeach
        </select>

        <label for="tenderMonth">Bulan:</label>
        <select id="tenderMonth" class="form-control">
          <option value="ALL">KESELURUHAN</option>
          @foreach (range(1, 12) as $month)
            <option value="{{ $month }}">{{ strtoupper(getMonthName($month)) }}</option>
          @endforeach
        </select>

        <label for="tenderDay" id="tenderDayLabel" style="display: none;">Tanggal:</label>
        <select id="tenderDay" class="form-control" style="display: none;">
          <option value="ALL">KESELURUHAN</option>
        </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="viewTenderButton">Lihat</button>
        <button class="btn btn-success" id="downloadTenderButton">Download</button>
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
document.addEventListener('DOMContentLoaded', function () {
  // === NON-TENDER ===
  const nonYear = document.getElementById('nonYear');
  const nonMonth = document.getElementById('month');
  const nonDay = document.getElementById('day');
  const nonDayLabel = document.getElementById('dayLabel');

  nonMonth.value = 'ALL';
  nonDayLabel.style.display = 'none';
  nonDay.style.display = 'none';

  function updateNonTenderDays() {
    const year = parseInt(nonYear.value);
    const month = parseInt(nonMonth.value);

    if (!isNaN(year) && !isNaN(month)) {
      const daysInMonth = new Date(year, month, 0).getDate();
      nonDay.innerHTML = '<option value="ALL">KESELURUHAN</option>';
      for (let i = 1; i <= daysInMonth; i++) {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = i;
        nonDay.appendChild(opt);
      }
      nonDayLabel.style.display = 'block';
      nonDay.style.display = 'block';
    }
  }

  nonMonth.addEventListener('change', function () {
    if (this.value !== 'ALL') {
      updateNonTenderDays();
    } else {
      nonDay.innerHTML = '<option value="ALL">KESELURUHAN</option>';
      nonDayLabel.style.display = 'none';
      nonDay.style.display = 'none';
    }
  });

  nonYear.addEventListener('change', function () {
    if (nonMonth.value !== 'ALL') {
      updateNonTenderDays();
    }
  });

  document.getElementById('viewButton').addEventListener('click', function () {
    const query = `?year=${nonYear.value}&month=${nonMonth.value}&day=${nonDay.value}`;
    window.location.href = `/non-tender/view-pdf${query}`;
  });

  document.getElementById('downloadButton').addEventListener('click', function () {
    const query = `?year=${nonYear.value}&month=${nonMonth.value}&day=${nonDay.value}`;
    window.location.href = `/non-tender/download-pdf${query}`;
  });

  // === TENDER ===
  const tenderYear = document.getElementById('tenderYear');
  const tenderMonth = document.getElementById('tenderMonth');
  const tenderDay = document.getElementById('tenderDay');
  const tenderDayLabel = document.getElementById('tenderDayLabel');

  tenderMonth.value = 'ALL';
  tenderDayLabel.style.display = 'none';
  tenderDay.style.display = 'none';

  function updateTenderDays() {
    const year = parseInt(tenderYear.value);
    const month = parseInt(tenderMonth.value);

    if (!isNaN(year) && !isNaN(month)) {
      const daysInMonth = new Date(year, month, 0).getDate();
      tenderDay.innerHTML = '<option value="ALL">KESELURUHAN</option>';
      for (let i = 1; i <= daysInMonth; i++) {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = i;
        tenderDay.appendChild(opt);
      }
      tenderDayLabel.style.display = 'block';
      tenderDay.style.display = 'block';
    }
  }

  tenderMonth.addEventListener('change', function () {
    if (this.value !== 'ALL') {
      updateTenderDays();
    } else {
      tenderDay.innerHTML = '<option value="ALL">KESELURUHAN</option>';
      tenderDayLabel.style.display = 'none';
      tenderDay.style.display = 'none';
    }
  });

  tenderYear.addEventListener('change', function () {
    if (tenderMonth.value !== 'ALL') {
      updateTenderDays();
    }
  });

  document.getElementById('viewTenderButton').addEventListener('click', function () {
    const query = `?year=${tenderYear.value}&month=${tenderMonth.value}&day=${tenderDay.value}`;
    window.location.href = `/tender/view-pdf${query}`;
  });

  document.getElementById('downloadTenderButton').addEventListener('click', function () {
    const query = `?year=${tenderYear.value}&month=${tenderMonth.value}&day=${tenderDay.value}`;
    window.location.href = `/tender/download-pdf${query}`;
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const submenu = document.querySelector('.dropdown-submenu');
  if (submenu) {
    const parentMenu = submenu.closest('.dropdown');
    submenu.addEventListener('mouseenter', () => {
      const toggle = submenu.querySelector('[data-bs-toggle="dropdown"]');
      bootstrap.Dropdown.getOrCreateInstance(toggle).show();
    });
    submenu.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!submenu.matches(':hover')) {
          const toggle = submenu.querySelector('[data-bs-toggle="dropdown"]');
          bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
        }
      }, 300);
    });
    parentMenu.addEventListener('mouseleave', () => {
      const toggle = submenu.querySelector('[data-bs-toggle="dropdown"]');
      bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
    });
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle submenu toggle on mobile
  const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
  
  dropdownSubmenus.forEach(function(submenu) {
    const link = submenu.querySelector('.dropdown-toggle');
    
    // Prevent default behavior on mobile
    link.addEventListener('click', function(e) {
      if (window.innerWidth < 992) { // lg breakpoint
        e.preventDefault();
        e.stopPropagation();
        
        const submenuDropdown = this.nextElementSibling;
        const isOpen = submenuDropdown.classList.contains('show');
        
        // Close all other open submenus first
        document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(menu) {
          if (menu !== submenuDropdown) {
            menu.classList.remove('show');
          }
        });
        
        // Toggle current submenu
        submenuDropdown.classList.toggle('show');
      }
    });
    
    // Close when clicking outside on mobile
    document.addEventListener('click', function(e) {
      if (window.innerWidth < 992 && !e.target.closest('.dropdown-submenu')) {
        document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(menu) {
          menu.classList.remove('show');
        });
      }
    });
  });
  
  // Handle hover on desktop
  if (window.innerWidth >= 992) {
    const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
    
    dropdownSubmenus.forEach(function(submenu) {
      submenu.addEventListener('mouseenter', function() {
        this.querySelector('.dropdown-menu').classList.add('show');
      });
      
      submenu.addEventListener('mouseleave', function() {
        this.querySelector('.dropdown-menu').classList.remove('show');
      });
    });
  }
});
</script>
@stack('scripts')
</body>
</html>
