<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <a href="{{ route('tender.list') }}" class="nav-link">
          <i class="nav-icon fa fa-list-ol"></i>
          <p>Tender</p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('non-tender.list') }}" class="nav-link">
          <i class="nav-icon fa fa-list-ul"></i>
          <p>Non Tender</p>
        </a>
      </li>

      <li class="nav-item">
        <a target="_blank" href="{{ route('report.rup') }}" class="nav-link">
          <i class="nav-icon fas fa-file-alt"></i>
          <p>RUP</p>
        </a>
      </li>

      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-shopping-cart"></i>
          <p>
            E-Purchasing
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('report.ekatalog') }}" class="nav-link">
              <p>E-Katalog</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('report.tokodaring') }}" class="nav-link">
              <p>Toko Daring</p>
            </a>
          </li>
        </ul>
      </li>


      <!-- Summary Report -->
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-download"></i>
          <p>
            Summary Report
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="modal" data-target="#filterModal">
              <p>Realisasi Non Tender</p>
            </a>
          </li>
          <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="modal" data-target="#filterTenderModal">
              <p>Realisasi Tender</p>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a target="_blank" href="{{ route('report') }}" class="nav-link">
          <i class="nav-icon fa fa-chart-pie"></i>
          <p>Grafik Report</p>
        </a>
      </li>


      @if ( Auth::user()->role_id == 1 )
      <li class="nav-item">
        <a href="{{ route('user.list') }}" class="nav-link">
          <i class="nav-icon fa fa-users"></i>
          <p>Manajemen User</p>
        </a>
      </li>
      @endif

    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
