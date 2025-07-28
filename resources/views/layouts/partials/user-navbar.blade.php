{{-- File: resources/views/layouts/partials/user-navbar.blade.php --}}

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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar">
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
          <a class="nav-link dropdown-toggle {{ request()->routeIs('report.ekatalog') || request()->routeIs('report.tokodaring') ? 'active' : '' }}" href="#" data-toggle="dropdown">
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
            $isSummaryReportActive = 
              request()->routeIs('report.categorize')

              || request()->routeIs('tender.fund.source')
              || request()->routeIs('report.all')
              || request()->routeIs('non-tender.realization')
              || request()->routeIs('tender.realization')
              || request()->routeIs('report.review');
          @endphp
          <a class="nav-link dropdown-toggle {{ $isSummaryReportActive ? 'active' : '' }}" href="#" data-toggle="dropdown">
            Summary Report
          </a>
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
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle {{ request()->is('monitoring*') ? 'active' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
    Monitoring
  </a>
  <ul class="dropdown-menu">
    <li>
      <a class="dropdown-item" href="{{ route('monitoring.realisasi.satker') }}">
        Realisasi Pengadaan
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('monitoring.rekap.realisasi-berlangsung') }}">
        Realisasi Berlangsung
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('monitoring.rekap.realisasi') }}">
        Realisasi Selesai
      </a>
    </li>

    <!-- Sub-menu: Monitoring Belum Input -->
    <li class="dropdown-submenu">
      <a class="dropdown-item dropdown-toggle" href="#">
        Monitoring Belum Input
      </a>
      <ul class="dropdown-menu">
        <li>
          <a class="dropdown-item" href="{{ route('monitoring.kontrak') }}">
            Kontrak Tender
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="{{ route('monitoring.kontrak.non_tender') }}">
            Kontrak Non Tender
          </a>
        </li>
      </ul>
    </li>
  </ul>
</li>

      </ul>

      <!-- User Account Menu -->
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

{{-- JS support dropdown-submenu (for Bootstrap 4) --}}
@push('script')
<script>
  $(function () {
    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
      var $el = $(this);
      var $parent = $el.offsetParent(".dropdown-menu");
      if (!$el.next().hasClass('show')) {
        $el.parents('.dropdown-menu').first().find('.show').removeClass("show");
      }
      var $subMenu = $el.next(".dropdown-menu");
      $subMenu.toggleClass('show');

      $el.parent("li").toggleClass('show');

      $el.parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
        $('.dropdown-submenu .show').removeClass("show");
      });

      return false;
    });
  });
</script>
@endpush