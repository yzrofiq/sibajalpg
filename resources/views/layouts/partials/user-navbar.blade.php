{{-- File: resources/views/layouts/partials/user-navbar.blade.php --}}

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Public Sans', sans-serif;
        background-color: #ffffff;
    }
    
    /* Submenu styling */
    .dropdown-submenu .dropdown-menu {
        top: 100% !important;
        left: 0 !important;
        margin-top: 0.2rem;
    }

    /* Consistent dropdown styling */
    .dropdown-menu {
        background-color: #ffffff;
        min-width: 220px;
        border: 1px solid #ddd;
    }

    /* Dropdown item styling */
    .dropdown-item {
        border-radius: 0.375rem;
        transition: background-color 0.2s ease;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dropdown-item:hover {
        background-color: #f1f1f1;
    }
    
    /* Dropdown arrow styling */
    .dropdown-toggle::after {
        margin-left: 0.5rem;
        vertical-align: middle;
        border-top: 0.4em solid;
        border-right: 0.4em solid transparent;
        border-left: 0.4em solid transparent;
        transition: transform 0.2s ease;
    }
    
    .dropdown-submenu > .dropdown-item::after {
        content: "";
        display: inline-block;
        margin-left: auto;
        border-top: 0.4em solid transparent;
        border-bottom: 0.4em solid transparent;
        border-left: 0.4em solid;
        border-right: 0;
        vertical-align: middle;
    }
    
    .show > .dropdown-toggle::after {
        transform: rotate(-180deg);
    }
    
    .dropdown-submenu.show > .dropdown-item::after {
        transform: rotate(90deg);
    }
    
    /* Mobile specific styles */
    @media (max-width: 991.98px) {
        .dropdown-menu {
            margin-left: 1rem;
            border: none;
            box-shadow: none;
        }
        
        .dropdown-submenu .dropdown-menu {
            margin-left: 1.5rem;
        }
        
        .dropdown-item {
            padding-left: 0.5rem;
        }
    }
    
    /* Submenu arrow styling */
    .dropdown-submenu > .dropdown-item::after {
      content: "";
      display: inline-block;
      margin-left: 0.5rem;
      vertical-align: middle;
      border-top: 0.4em solid transparent;
      border-bottom: 0.4em solid transparent;
      border-left: 0.4em solid;
      transition: transform 0.2s ease;
    }

    .dropdown-submenu.show > .dropdown-item::after {
      transform: rotate(90deg);
    }

    /* Mobile adjustments */
    @media (max-width: 991.98px) {
      .dropdown-submenu > .dropdown-item {
        padding-right: 1.5rem;
      }
      
      .dropdown-submenu > .dropdown-item::after {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
      }
      
      .dropdown-submenu.show > .dropdown-item::after {
        transform: translateY(-50%) rotate(90deg);
      }
    }
    
    /* Monitoring dropdown specific styles */
    .monitoring-dropdown .dropdown-menu {
        padding: 0.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .monitoring-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        margin: 0.15rem 0;
        border-radius: 0.375rem;
    }
    
    .monitoring-dropdown .dropdown-submenu .dropdown-menu {
        margin-top: 0.25rem;
        border-radius: 0.5rem;
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
          <a class="nav-link dropdown-toggle {{ request()->routeIs('report.ekatalog') || request()->routeIs('report.tokodaring') ? 'active' : '' }}" href="#" id="epurchasingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            E-Purchasing
          </a>
          <ul class="dropdown-menu" aria-labelledby="epurchasingDropdown">
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
          <a class="nav-link dropdown-toggle {{ $isSummaryReportActive ? 'active' : '' }}" href="#" id="summaryReportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Summary Report
          </a>
          <ul class="dropdown-menu" aria-labelledby="summaryReportDropdown">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterModal">Realisasi Non Tender</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#filterTenderModal">Realisasi Tender</a></li>
          </ul>
        </li>

        <!-- Monitoring Dropdown -->
        <li class="nav-item dropdown monitoring-dropdown">
          <a class="nav-link dropdown-toggle {{ request()->is('monitoring*') ? 'active' : '' }}" href="#" id="monitoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Monitoring
          </a>
          <ul class="dropdown-menu shadow rounded-3 p-1 bg-white" aria-labelledby="monitoringDropdown">
            <li>
              <a class="dropdown-item px-3 py-2 rounded-2 {{ request()->routeIs('monitoring.realisasi.satker') ? 'active' : '' }}" href="{{ route('monitoring.realisasi.satker') }}">
                Realisasi Pengadaan
              </a>
            </li>
            <li>
              <a class="dropdown-item px-3 py-2 rounded-2 {{ request()->routeIs('monitoring.rekap.realisasi-berlangsung') ? 'active' : '' }}" href="{{ route('monitoring.rekap.realisasi-berlangsung') }}">
                Realisasi Berlangsung
              </a>
            </li>
            <li>
              <a class="dropdown-item px-3 py-2 rounded-2 {{ request()->routeIs('monitoring.rekap.realisasi') ? 'active' : '' }}" href="{{ route('monitoring.rekap.realisasi') }}">
                Realisasi Selesai
              </a>
            </li>

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

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize all dropdowns
  const dropdownElements = document.querySelectorAll('.dropdown-toggle');
  
  dropdownElements.forEach(function(dropdownToggleEl) {
    // Initialize dropdown
    const dropdown = new bootstrap.Dropdown(dropdownToggleEl);
    
    // Handle click for mobile
    dropdownToggleEl.addEventListener('click', function(e) {
      if (window.innerWidth <= 991.98) {
        const parentItem = this.closest('.dropdown, .dropdown-submenu');
        const isShowing = parentItem.classList.contains('show');
        
        // Close all other dropdowns at the same level
        const siblings = Array.from(parentItem.parentNode.children).filter(
          child => child !== parentItem
        );
        
        siblings.forEach(function(sibling) {
          const siblingToggle = sibling.querySelector('.dropdown-toggle');
          if (siblingToggle) {
            bootstrap.Dropdown.getInstance(siblingToggle)?.hide();
          }
        });
        
        // For submenus, prevent closing when clicking the toggle
        if (parentItem.classList.contains('dropdown-submenu')) {
          e.preventDefault();
          e.stopPropagation();
          
          // Toggle current submenu
          if (isShowing) {
            dropdown.hide();
          } else {
            dropdown.show();
          }
        }
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-menu')) {
      const openDropdowns = document.querySelectorAll('.dropdown.show, .dropdown-submenu.show');
      openDropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        if (toggle) {
          bootstrap.Dropdown.getInstance(toggle)?.hide();
        }
      });
    }
  });

  // Handle hover for desktop
  function setupDesktopHover() {
    const dropdowns = document.querySelectorAll('.dropdown, .dropdown-submenu');
    
    dropdowns.forEach(dropdown => {
      // Remove previous event listeners
      dropdown.removeEventListener('mouseenter', handleMouseEnter);
      dropdown.removeEventListener('mouseleave', handleMouseLeave);
      
      if (window.innerWidth > 991.98) {
        dropdown.addEventListener('mouseenter', handleMouseEnter);
        dropdown.addEventListener('mouseleave', handleMouseLeave);
      }
    });
  }

  function handleMouseEnter() {
    const toggle = this.querySelector('.dropdown-toggle');
    if (toggle) {
      bootstrap.Dropdown.getOrCreateInstance(toggle).show();
      
      // For submenus, also show parent menu
      if (this.classList.contains('dropdown-submenu')) {
        const parentDropdown = this.closest('.dropdown-menu')?.parentElement;
        if (parentDropdown) {
          const parentToggle = parentDropdown.querySelector('.dropdown-toggle');
          if (parentToggle) {
            bootstrap.Dropdown.getOrCreateInstance(parentToggle).show();
          }
        }
      }
    }
  }

  function handleMouseLeave() {
    const toggle = this.querySelector('.dropdown-toggle');
    if (toggle) {
      // Delay closing to allow moving to submenu
      setTimeout(() => {
        if (!this.matches(':hover')) {
          const dropdown = bootstrap.Dropdown.getInstance(toggle);
          if (dropdown) {
            // Check if mouse moved to submenu
            const submenu = this.querySelector('.dropdown-menu');
            if (!submenu || !submenu.matches(':hover')) {
              dropdown.hide();
            }
          }
        }
      }, 300);
    }
  }

  // Initialize
  setupDesktopHover();
  window.addEventListener('resize', setupDesktopHover);
});
</script>