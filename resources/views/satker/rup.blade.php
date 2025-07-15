@extends('layouts.adminlte')

@section('title', 'RUP Provinsi Lampung')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
  .swakelola-col {
    min-width: 80px;
  }
</style>
@endpush

@section('navbar-extra')
<li class="nav-item d-flex align-items-center">
  <form id="filterForm" action="{{ route('report.rup') }}" method="GET" class="form-inline">

    {{-- Provinsi --}}
    <div class="form-group mr-2 mb-0">
      <input type="text" class="form-control form-control-sm" value="Provinsi Lampung" readonly>
    </div>

    {{-- Tahun --}}
    <div class="form-group mr-2 mb-0">
      <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
        @foreach(collect($tahunTersedia)->sortDesc() as $t)
          <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
      </select>
    </div>

    {{-- OPD --}}
    <div class="form-group mb-0">
      <select name="opd" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="">Semua Satuan Kerja</option>
        @foreach($daftarSatker as $satker)
          <option value="{{ $satker }}" {{ request('opd') == $satker ? 'selected' : '' }}>{{ $satker }}</option>
        @endforeach
      </select>
    </div>

  </form>
</li>
@endsection

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Header Box --}}
    <div class="card mb-3 mt-3">
      <div class="card-body bg-light border rounded">
        <strong>Rencana Umum Pengadaan (RUP)</strong>
      </div>
    </div>

    {{-- Summary --}}
    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card bg-info text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">Total Paket</div>
              <div class="h3 mb-0">{{ number_format($grandTotal['paket_total'], 0, ',', '.') }}</div>
            </div>
            <div class="text-end">
              <i class="fas fa-box fa-2x ms-3"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card bg-success text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">Total Pagu</div>
              <div class="h3 mb-0">Rp {{ number_format($grandTotal['pagu_total'], 0, ',', '.') }}</div>
            </div>
            <div class="text-end">
              <i class="fas fa-money-bill-wave fa-2x ms-3"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Rekapitulasi RUP</h3>
        <div class="ml-auto">
          {{-- ✅ Export PDF dengan filter dan mode preview --}}
          <a href="{{ route('report.rup.pdf', request()->all() + ['mode' => 'I']) }}" 
             class="btn btn-success btn-sm" 
             target="_blank">
            Export PDF
          </a>
        </div>
      </div>
      <div class="card-body table-responsive">
        <table id="rupTable" class="table table-bordered table-hover text-nowrap w-100">
          <thead class="thead-light">
            <tr>
              <th rowspan="2">No</th>
              <th rowspan="2">Satker</th>
              <th colspan="2">Penyedia</th>
              <th colspan="2">Swakelola</th>
              <th colspan="2" class="text-center">Penyedia Dalam Swakelola</th>
              <th colspan="2">Total</th>
            </tr>
            <tr>
              <th>Paket</th><th>Pagu</th>
              <th>Paket</th><th>Pagu</th>
              <th class="swakelola-col">Paket</th><th class="swakelola-col">Pagu</th>
              <th>Paket</th><th>Pagu</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['name'] }}</td>
                @for ($i = 2; $i <= 9; $i++)
                  <td class="{{ in_array($i, [6,7]) ? 'swakelola-col' : '' }}">
                    {{ number_format($item['data'][$i], 0, ',', '.') }}
                  </td>
                @endfor
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  div.dataTables_wrapper div.dataTables_info {
    margin-top: 15px !important;
  }
  div.dataTables_wrapper div.dataTables_paginate {
    margin-top: 15px !important;
  }
</style>
@endpush

@push('script')
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
  $(function () {
    $('#rupTable').DataTable({
      scrollX: true,
      autoWidth: false,
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ entri",
        zeroRecords: "Tidak ditemukan",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
        infoEmpty: "Tidak ada entri",
        paginate: { previous: "Sebelumnya", next: "Berikutnya" }
      }
    });
  });
</script>
@endpush
