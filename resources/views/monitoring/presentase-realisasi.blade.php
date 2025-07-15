@extends('layouts.adminlte')

@push('style')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
    }

    .container {
        padding: 24px;
        max-width: 1400px;
        margin: auto;
    }

    h1 {
        color: #1d4ed8;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    form {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    label {
        font-weight: 600;
    }

    select {
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background-color: #fff;
        cursor: pointer;
    }

    .export-btn {
        margin-left: auto;
        padding: 8px 16px;
        background-color: #1d4ed8;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .export-btn:hover {
        background-color: #1e3a8a;
    }

    .table-wrapper {
        overflow-x: auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    table {
        min-width: 1100px;
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        color: #334155;
    }

    thead th {
        background-color: #f1f5f9;
        padding: 10px;
        border: 1px solid #e2e8f0;
        font-weight: bold;
        text-align: center;
    }

    tbody td {
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    tbody td.text-left { text-align: left; }
    tbody td.text-right { text-align: right; }

    tbody tr:hover {
        background-color: #f8fafc;
    }

    .total-row {
        background-color: #fef9c3;
        font-weight: bold;
    }

    .no-data {
        text-align: center;
        font-style: italic;
        color: #64748b;
        padding: 20px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1>Presentase Realisasi Pengadaan terhadap RUP</h1>

    {{-- Filter --}}
    <form method="GET" id="filter-form">
        <label for="tahun">Pilih Tahun:</label>
        <select name="tahun" id="tahun" onchange="document.getElementById('filter-form').submit()">
            <option value="">-- Pilih Tahun --</option>
            @foreach([2024, 2025] as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>

        <label for="satker">Nama Satker:</label>
        <select name="satker" id="satker" onchange="document.getElementById('filter-form').submit()">
            <option value="">-- Semua Satker --</option>
            @foreach($listSatker as $satker)
                <option value="{{ $satker }}" {{ request('satker') == $satker ? 'selected' : '' }}>{{ $satker }}</option>
            @endforeach
        </select>

        <a href="{{ route('monitoring.realisasi.pdf', ['tahun' => request('tahun'), 'satker' => request('satker')]) }}"
           target="_blank"
           style="margin-left: 20px; padding: 6px 14px; background-color: #1e40af; color: white; border-radius: 6px; text-decoration: none;">
            📄 Export PDF
        </a>
    </form>

    {{-- Tabel --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th colspan="4" class="bg-yellow">PENGADAAN</th>
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
