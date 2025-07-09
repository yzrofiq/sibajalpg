@extends('layouts.user-adminlte')

@section('title', 'Rekap Toko Daring')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  body {
    background-color: #F5F8FD !important;
  }

  .content-wrapper,
  section.content {
    background-color: #F5F8FD;
    padding-bottom: 4rem;
  }

  .card {
    background-color: white;
    border-radius: 6px;
  }

  .box-softblue {
    background-color: #5ca8dd !important;
    color: white;
  }

  .box-green {
    background-color: #319b74 !important;
    color: white;
  }

  .filter-row select.form-control {
  appearance: auto !important;     /* tampilkan panah bawaan browser */
  -webkit-appearance: auto !important;
  -moz-appearance: auto !important;
  background-image: none !important; /* jaga-jaga kalau tema overwrite */
  margin-left: 3px;
}

  .filter-container {
    background-color: white;
    padding: 1rem;
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(91, 123, 154, 0.1);
  }

  .table th,
  .table td {
    font-size: 0.875rem;
  }

  .dataTables_wrapper .dataTables_paginate {
    margin-top: 16px !important;
  }

  .dataTables_wrapper .dataTables_info {
    margin-top: 12px !important;
  }

  .dataTables_length select {
    min-width: 60px;
  }
</style>
@endpush

@section('navbar-extra')
<div class="container-fluid mt-3 mb-3 px-0">
  <div class="filter-container w-100">
    <form id="filterForm" action="{{ route('report.tokodaring') }}" method="GET" class="form-inline">
    <div class="row w-100 filter-row">

        <div class="col-md-2 mb-2">
          <input type="text" class="form-control form-control-sm w-100" value="Provinsi Lampung" disabled>
        </div>

        <div class="col-md-2 mb-2">
          <select name="tahun" class="form-control form-control-sm w-100" onchange="this.form.submit()">
            @foreach($tahunTersedia as $t)
              <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3 mb-2">
          <select name="satker" class="form-control form-control-sm w-100" onchange="this.form.submit()">
            <option value="Semua" {{ $satker == 'Semua' ? 'selected' : '' }}>Semua Satuan Kerja</option>
            @foreach($satkerList as $s)
              <option value="{{ $s }}" {{ $satker == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
          </select>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Header --}}
    <div class="mt-3 mb-4 ps-1">
  <h4 class="fw-bold" style="color: #1f3d7a;">
    Laporan Toko Daring (Bela Pengadaan) - Tahun {{ $tahun }}
  </h4>
</div>



    {{-- Summary Box --}}
    <div class="row mt-3 mb-3">
      <div class="col-md-6">
        <div class="card box-softblue text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">TOTAL PAKET</div>
              <div class="h3 mb-0">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
            </div>
            <div class="text-end">
              <i class="fas fa-box fa-2x ms-3"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card box-green text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">TOTAL NILAI TRANSAKSI</div>
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
        <h3 class="card-title">Rekapitulasi Toko Daring</h3>
        <div class="ml-auto">
          <a href="{{ route('report.tokodaring.exportpdf', ['tahun' => $tahun]) }}"
            class="btn btn-success btn-sm" target="_blank">
            <i class="fas fa-file-pdf mr-1"></i> Export PDF
          </a>
        </div>
      </div>

      <div class="card-body table-responsive">
        <table id="tokodaringTable" class="table table-bordered table-hover text-nowrap w-100">
          <thead class="thead-light">
            <tr>
              <th>Satuan Kerja</th>
              <th>Total Paket</th>
              <th>Nilai Transaksi</th>
            </tr>
          </thead>
          <tbody>
          @foreach($rekap as $kdSatker => $rekapData)
  <tr>
    <td>{{ $rekapData['nama_satker'] }}</td>
    <td>{{ $rekapData['total_transaksi'] }}</td>
    <td class="text-end">Rp {{ number_format($rekapData['nilai_transaksi'], 0, ',', '.') }}</td>
  </tr>
@endforeach

          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

@push('script')
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
  $(function () {
    $('#tokodaringTable').DataTable({
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
