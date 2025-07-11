@extends('layouts.adminlte')

@section('title', 'Rekap Toko Daring')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('navbar-extra')
<li class="nav-item d-flex align-items-center">
  <form id="filterForm" action="{{ route('report.tokodaring') }}" method="GET" class="form-inline">

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
        <option value="Semua" {{ $satker == 'Semua' ? 'selected' : '' }}>Semua Satuan Kerja</option>
        @foreach($satkerList as $s)
          <option value="{{ $s }}" {{ $satker == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
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
        <strong>Laporan Toko Daring (Bela Pengadaan) - Tahun {{ $tahun }}</strong>
      </div>
    </div>

    {{-- Summary Box --}}
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="card bg-info text-white shadow-sm">
          <div class="card-body d-flex align-items-center">
            <div class="flex-grow-1">
              <div class="text-uppercase small font-weight-bold">Total Paket</div>
              <div class="h3 mb-0">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
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
        <h3 class="card-title">Rekapitulasi Toko Daring</h3>
        <div class="ml-auto">
          <a href="{{ route('report.tokodaring.exportpdf', ['tahun' => $tahun]) }}"
            class="btn btn-success btn-sm" target="_blank">
            Export PDF
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
            @foreach($rekap as $namaSatker => $rekapData)
              <tr>
                <td>{{ $namaSatker }}</td>
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

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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