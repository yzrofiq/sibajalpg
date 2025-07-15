@extends('layouts.user-adminlte')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  body {
    background-color: #F5F8FD;
  }

  .content-wrapper,
  section.content {
    background-color: #F5F8FD;
    padding-bottom: 4rem;
  }

  .filter-container {
    background-color: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
  }

  .filter-container form label {
    margin-right: 0.5rem;
    font-weight: 500;
  }

  .filter-container select,
  .filter-container .btn {
    margin-right: 1rem;
  }

  .table-wrapper {
    overflow-x: auto;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .table {
    min-width: 1100px;
    font-size: 0.9rem;
  }

  .table th {
    background-color: #085DCB;
    color: white;
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
  }

  .table td {
    text-align: center;
    padding: 10px;
    vertical-align: middle;
    color: #334155;
  }

  .table td.text-left {
    text-align: left;
  }

  .table td.text-right {
    text-align: right;
  }

  .table tbody tr:hover {
    background-color: #f8fafc;
  }

  .total-row {
    background-color: #319b74;
    color: white;
    font-weight: bold;
  }

  .no-data {
    text-align: center;
    font-style: italic;
    color: #64748b;
    padding: 24px;
  }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4 fw-bold text-primary">Presentase Realisasi Pengadaan terhadap RUP</h1>

    {{-- Filter --}}
    <div class="filter-container">
        <form method="GET" id="filter-form" class="d-flex align-items-center flex-wrap">
            <div class="mb-2 me-3">
                <label for="tahun" class="form-label">Pilih Tahun:</label>
                <select name="tahun" id="tahun" class="form-control" onchange="document.getElementById('filter-form').submit()">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach([2024, 2025] as $th)
                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 me-3">
                <label for="satker" class="form-label">Nama Satker:</label>
                <select name="satker" id="satker" class="form-control" onchange="document.getElementById('filter-form').submit()">
                    <option value="">-- Semua Satker --</option>
                    @foreach($listSatker as $satker)
                        <option value="{{ $satker }}" {{ request('satker') == $satker ? 'selected' : '' }}>{{ $satker }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <a href="{{ route('monitoring.realisasi.pdf', ['tahun' => request('tahun'), 'satker' => request('satker')]) }}"
                   target="_blank"
                   class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th colspan="4">PENGADAAN</th>
                </tr>
                <tr>
                    <th>Nama Satker</th>
                    <th>Anggaran Belanja Pengadaan</th>
                    <th>Total Realisasi Pengadaan <br><span style="font-size: 11px;">(Tender, Non-Tender, E-Katalog, Toko Daring)</span></th>
                    <th>Presentase Realisasi Pengadaan <br><span style="font-size: 11px;">(D / C × 100%)</span></th>
                </tr>
                <tr>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                    <th>E</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBelanja = 0;
                    $totalTransaksi = 0;
                    $totalPersen = 0;
                    $jumlahData = $data->count();
                @endphp

                @forelse($data as $index => $item)
                    @php
                        $totalBelanja += $item->belanja_pengadaan;
                        $totalTransaksi += $item->total_transaksi;
                        $totalPersen += $item->presentase_realisasi;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $item->nama_satker }}</td>
                        <td class="text-right">{{ number_format($item->belanja_pengadaan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->total_transaksi, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>{{ number_format($item->presentase_realisasi, 2, ',', '.') }}%</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">Tidak ada data yang tersedia untuk tahun atau satker yang dipilih.</td>
                    </tr>
                @endforelse

                @if($jumlahData > 0)
                    <tr class="total-row">
                        <td colspan="2">TOTAL</td>
                        <td class="text-right">{{ number_format($totalBelanja, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalTransaksi, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalPersen / $jumlahData, 2, ',', '.') }}%</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
