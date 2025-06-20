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
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-newspaper"></i>
          <p>E-Katalog</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-download"></i>
          <p>
            Summary Report
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('tender.strategic') }}" class="nav-link">
              <p>10 Paket Strategis</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                <span style="font-size: 12px;">Reviu Perencanaan Kegiatan PBJ</span>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a target="_blank" href="{{ route('report.categorize') }}" class="nav-link">
                  <p>Pengelompokan Jenis Pengadaan</p>
                </a>
              </li>
              <li class="nav-item">
                <a target="_blank" href="{{ route('tender.fund.source') }}" class="nav-link">
                  <p>Hasil Sumber Dana</p>
                </a>
              </li>
              <li class="nav-item">
                <a target="_blank" href="{{ route('report.all') }}" class="nav-link">
                  <p>Laporan Keseluruhan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a target="_blank" href="{{ route('non-tender.realization') }}" class="nav-link">
              <p>Realisasi Non Tender</p>
            </a>
          </li>
          <li class="nav-item">
            <a target="_blank" href="{{ route('tender.realization') }}" class="nav-link">
              <p>Realisasi Tender</p>
            </a>
          </li>
          <li class="nav-item">
            <a target="_blank" href="{{ route('report.rup') }}" class="nav-link">
              <p>Data RUP</p>
            </a>
          </li>
          <li class="nav-item">
            <a target="_blank" href="{{ url('/struktur-anggaran') }}" class="nav-link">
              <p>Struktur Anggaran</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('vendor.list') }}" class="nav-link">
              <p>Data Vendor</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('report.review') }}" class="nav-link" target="_blank">
              <p>Hasil Review</p>
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
