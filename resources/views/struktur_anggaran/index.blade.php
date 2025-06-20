@extends('layouts.adminlte')

@section('title', 'RUP Provinsi Lampung')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">RUP Pemerintah Provinsi Lampung</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">RUP</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    
    {{-- Summary --}}
    @include('components.summary')

    {{-- Total Paket dan Total Pagu --}}
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="alert alert-info mb-0">
          <strong>Total Paket:</strong> {{ $totalPaket }}
        </div>
      </div>
      <div class="col-md-6">
        <div class="alert alert-success mb-0">
          <strong>Total Pagu RUP:</strong> Rp {{ number_format($totalPaguRup, 0, ',', '.') }}
        </div>
      </div>
    </div>

    {{-- Data Table --}}
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title">Data Struktur Anggaran Satker</h3>
        <div class="ml-auto">
          <a href="#" class="btn btn-sm bg-primary" target="_blank">Download</a>
        </div>
      </div>
      <div class="card-body table-responsive">
        <table id="struktur" class="table table-hover table-head-fixed text-nowrap">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Kode Satker</th>
              <th>Nama Satker</th>
              <th>Belanja Operasi</th>
              <th>Belanja Modal</th>
              <th>Belanja BTT</th>
              <th>Belanja Non Pengadaan</th>
              <th>Belanja Pengadaan</th>
              <th>Total Belanja</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($data as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kd_satker }}</td>
                <td>{{ $item->nama_satker }}</td>
                <td>{{ number_format($item->belanja_operasi, 0, ',', '.') }}</td>
                <td>{{ number_format($item->belanja_modal, 0, ',', '.') }}</td>
                <td>{{ number_format($item->belanja_btt, 0, ',', '.') }}</td>
                <td>{{ number_format($item->belanja_non_pengadaan, 0, ',', '.') }}</td>
                <td>{{ number_format($item->belanja_pengadaan, 0, ',', '.') }}</td>
                <td><strong>{{ number_format($item->total_belanja, 0, ',', '.') }}</strong></td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center">Data tidak ditemukan.</td>
              </tr>
            @endforelse
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
<script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
  $(function () {
    $('#struktur').DataTable({
        scrollX: true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": falsey,
      "language": {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ entri",
        zeroRecords: "Tidak ditemukan",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
        infoEmpty: "Tidak ada entri",
        paginate: {
          previous: "Sebelumnya",
          next: "Berikutnya"
        }
      }
    });
  });
</script>
@endpush
