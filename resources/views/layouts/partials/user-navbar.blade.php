{{-- File: resources\views\layouts\partials\user-navbar.blade.php --}}

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

       <!-- Dropdown: E-Purchasing -->
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

<!-- Dropdown: Summary Report -->
<li class="nav-item dropdown">
  @php
    $isSummaryReportActive = request()->routeIs('tender.strategic')
      || request()->routeIs('report.categorize')
      || request()->routeIs('tender.fund.source')
      || request()->routeIs('report.all')
      || request()->routeIs('non-tender.realization')
      || request()->routeIs('tender.realization')
      || request()->routeIs('report.review');
  @endphp
  <a class="nav-link dropdown-toggle {{ $isSummaryReportActive ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
    Summary Report
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="{{ route('tender.strategic') }}">10 Paket Strategis</a></li>
    <li class="dropdown-submenu position-relative">
      <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Reviu Perencanaan PBJ</a>
      <ul class="dropdown-menu border-0 shadow">
        <li><a class="dropdown-item" href="{{ route('report.categorize') }}" target="_blank">Pengelompokan Jenis Pengadaan</a></li>
        <li><a class="dropdown-item" href="{{ route('tender.fund.source') }}" target="_blank">Hasil Sumber Dana</a></li>
        <li><a class="dropdown-item" href="{{ route('report.all') }}" target="_blank">Laporan Keseluruhan</a></li>
      </ul>
    </li>
    <li><a class="dropdown-item" href="{{ route('non-tender.realization') }}" target="_blank">Realisasi Non Tender</a></li>
    <li><a class="dropdown-item" href="{{ route('tender.realization') }}" target="_blank">Realisasi Tender</a></li>
    <li><a class="dropdown-item" href="{{ route('report.review') }}" target="_blank">Hasil Review</a></li>
  </ul>
</li>

        <!-- Dropdown: Monitoring -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->is('monitoring*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Monitoring</a>
          <ul class="dropdown-menu">
            {{-- Kosong untuk saat ini --}}
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('report') ? 'active' : '' }}" href="{{ route('report') }}" target="_blank">Grafik Report</a>
        </li>
      </ul>

      <!-- User Account Menu -->
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
