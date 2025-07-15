@extends('layouts.adminlte')

@section('title', 'Rekap e-Katalog')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('navbar-extra')
<li class="nav-item d-flex align-items-center">
  <form id="filterForm" action="{{ route('report.ekatalog') }}" method="GET" class="form-inline">

    {{-- Provinsi --}}
    <div class="form-group mr-2 mb-0">
      <input type="text" class="form-control form-control-sm" value="Provinsi Lampung" readonly>
    </div>

    {{-- Tahun --}}
    <div class="form-group mr-2 mb-0">
      <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
        @foreach($tahunTersedia as $t)
          <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
      </select>
    </div>

    {{-- Satuan Kerja --}}
<div class="form-group mr-2 mb-0">
  <select name="satker" class="form-control form-control-sm" onchange="this.form.submit()">
    <option value="Semua" {{ request('satker') == 'Semua' ? 'selected' : '' }}>Semua Satuan Kerja</option>
    @foreach($satkerList as $s)
      <option value="{{ $s }}" {{ request('satker') == $s ? 'selected' : '' }}>{{ $s }}</option>
    @endforeach
  </select>
</div>



    {{-- Versi --}}
    <div class="form-group mr-2 mb-0">
      <select name="versi" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="V5" {{ $versi == 'V5' ? 'selected' : '' }}>e-Katalog V5</option>
        <option value="V6" {{ $versi == 'V6' ? 'selected' : '' }}>e-Katalog V6</option>
      </select>
    </div>

    {{-- Status Paket --}}
    <div class="form-group ml-2 mb-0">
      <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Semua Status</option>
        <option value="Proses" {{ $status == 'Proses' ? 'selected' : '' }}>Paket Proses</option>
        <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Paket Selesai</option>
      </select>
    </div>

  </form>
</li>
@endsection

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Header --}}
    <div class="card mb-3 mt-3">
      <div class="card-body bg-light border rounded">
        <strong>Laporan e-Katalog Versi {{ strtoupper($versi) }} - Tahun {{ $tahun }}</strong>
      </div>
    </div>

    {{-- Summary Box --}}
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="card bg-info text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">Total Paket</div>
              <div class="h3 mb-0">{{ number_format($totalPaket, 0, ',', '.') }}</div>
            </div>
            <div class="text-end">
              <i class="fas fa-box fa-2x ms-3"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-success text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">Total Nilai Transaksi</div>
              <div class="h3 mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div>
            </div>
            <div class="text-end">
              <i class="fas fa-money-bill-wave fa-2x ms-3"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Rekapitulasi e-Katalog</h3>
        <div class="ml-auto">
          <a href="{{ route('report.ekatalog.exportpdf', ['tahun' => $tahun, 'versi' => $versi]) }}"
            class="btn btn-success btn-sm" target="_blank">
            Export PDF
          </a>
        </div>
      </div>

      <div class="card-body table-responsive">
        <table id="ekatalogTable" class="table table-bordered table-hover text-nowrap w-100">
          <thead class="thead-light">
            <tr>
              <th>ID RUP</th>
              <th>Satuan Kerja</th>
              <th>Nama Paket</th>
              <th>Status Paket</th>
              <th>Nilai Kontrak</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $item)
              <tr>
                <td>{{ $item['id_rup'] }}</td>
                <td>{{ $item['nama_satker'] }}</td>
                <td>{{ $item['nama_paket'] }}</td>
                <td>{{ $item['status'] }}</td>
                <td class="text-end">{{ number_format($item['nilai_kontrak'], 0, ',', '.') }}</td>
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
    $('#ekatalogTable').DataTable({
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