@extends('layouts.user-adminlte')

@push('style')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }
    th, td {
        border: 1px solid #e2e8f0;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f1f5f9;
        font-weight: bold;
    }
    .bg-blue { background-color: #3b82f6; color: white; }
    .bg-yellow { background-color: #facc15; color: #1e293b; }
    .bg-green { background-color: #22c55e; color: white; }
    .bg-gray { background-color: #94a3b8; color: white; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
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
                    <th>No.</th>
                    <th class="bg-blue">Nama Satker</th>
                    <th class="bg-yellow">Total Tender Selesai</th>
                    <th class="bg-green">Total Nilai Pagu</th>
                    <th class="bg-gray">Kontrak Belum Input</th>
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
