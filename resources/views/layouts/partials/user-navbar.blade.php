{{-- File: resources/views/layouts/partials/user-navbar.blade.php --}}
<style>
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

<nav class="navbar navbar-expand-lg navbar-dark navbar-blue shadow-sm sticky-top">
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

    <!-- Toggle Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Items -->
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

        <!-- E-Purchasing -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('report.ekatalog') || request()->routeIs('report.tokodaring') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
            E-Purchasing
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('report.ekatalog') }}">E-Katalog</a></li>
            <li><a class="dropdown-item" href="{{ route('report.tokodaring') }}">Toko Daring</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('report.rup') ? 'active' : '' }}" href="{{ route('report.rup') }}">RUP</a>
        </li>

        <!-- Summary Report -->
        <li class="nav-item dropdown">
          @php
            $isSummaryReportActive =
              request()->routeIs('report.categorize') ||
              request()->routeIs('tender.fund.source') ||
              request()->routeIs('report.all') ||
              request()->routeIs('non-tender.realization') ||
              request()->routeIs('tender.realization') ||
              request()->routeIs('report.review');
          @endphp
          <a class="nav-link dropdown-toggle {{ $isSummaryReportActive ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
            Summary Report
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterModal">Realisasi Non Tender</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterTenderModal">Realisasi Tender</a></li>
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

      <!-- User -->
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" data-bs-toggle="dropdown">
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

<!-- Bootstrap Bundle with Popper (wajib untuk dropdown dan submenu) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
