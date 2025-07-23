@extends('layouts.user-adminlte')

@push('style')
<style>
    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        table-layout: fixed !important;
        font-size: 13px !important;
    }

    th, td {
        border: 1px solid #cbd5e1 !important;
        padding: 10px !important;
        text-align: center !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    th {
        background-color: #f1f5f9 !important;
        font-weight: bold !important;
    }

    tbody tr:hover {
        background-color: #f9fafb !important;
    }

    .bg-blue {
        background-color: #3b82f6 !important;
        color: white !important;
    }

    .bg-yellow {
        background-color: #facc15 !important;
        color: #1e293b !important;
    }

    .bg-green {
        background-color: #22c55e !important;
        color: white !important;
    }

    .bg-gray {
        background-color: #94a3b8 !important;
        color: white !important;
    }

    .text-left {
        text-align: left !important;
    }

    .text-right {
        text-align: right !important;
    }

    .filter-form {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-form select {
        padding: 6px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
    }

    h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #334155;
    }
</style>
@endpush


@section('content')
<div class="container py-4">
    <h1>Monitoring Kontrak Tender</h1>

    {{-- 🔍 Filter --}}
    <form method="GET" class="filter-form">
        <div>
            <label for="tahun" class="me-2">Tahun:</label>
            <select name="tahun" id="tahun" onchange="this.form.submit()">
                @foreach ($tahunList as $t)
                    <option value="{{ $t }}" {{ $t == request('tahun', $tahun) ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="nama_satker" class="me-2">Satker:</label>
            <select name="nama_satker" id="nama_satker" onchange="this.form.submit()">
                <option value="">Semua Satker</option>
                @foreach ($namaSatkerList as $satkerName)
                    <option value="{{ $satkerName }}" {{ $satkerName == request('nama_satker') ? 'selected' : '' }}>
                        {{ $satkerName }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- 📊 Tabel --}}
    <div class="table-responsive">
        <table>
        <thead>
    <tr>
        <th style="width: 50px;" class="bg-gray">No.</th>
        <th style="width: 350px;" class="bg-blue text-left">Nama Satker</th>
        <th style="width: 180px;" class="bg-yellow">Total Tender Selesai</th>
        <th style="width: 200px;" class="bg-green">Total Nilai Pagu</th>
        <th style="width: 200px;" class="bg-gray">Kontrak Belum Input</th>
    </tr>
</thead>

            <tbody>
                @forelse ($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-left">
                        <a href="{{ route('monitoring.kontrak.detail', ['satker' => urlencode($item['nama_satker'])]) }}" style="color: #1d4ed8;">
                            {{ $item['nama_satker'] }}
                        </a>
                        </td>
                        <td>{{ $item['total_paket'] }}</td>
                        <td class="text-right">{{ number_format($item['total_pagu'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['total_kontrak'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="font-style: italic;">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse

                {{-- 🔢 Total Row --}}
                <tr style="font-weight: bold; background-color: #f8fafc;">
                    <td colspan="2" class="text-right">Total</td>
                    <td>{{ $totals['total_paket'] }}</td>
                    <td class="text-right">{{ number_format($totals['total_pagu'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['total_kontrak'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
