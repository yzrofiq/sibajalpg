@extends('layouts.adminlte')

@push('style')
<style>
    table { border-collapse: collapse; width: 100%; font-size: 13px; }
    th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: center; }
    th { background-color: #f1f5f9; font-weight: bold; }
    .bg-blue { background-color: #bfdbfe; }
    .bg-yellow { background-color: #fef9c3; }
    .bg-green { background-color: #86efac; }
    .bg-gray { background-color: #e2e8f0; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }

    .filter-form { margin-bottom: 20px; }
    .filter-form select { padding: 5px 8px; border: 1px solid #ccc; border-radius: 4px; margin-right: 10px; }
</style>
@endpush


@section('content')
<div class="container">
    <h1>Monitoring Kontrak Tender</h1>

    {{-- 🔍 Filter --}}
    <form method="GET" class="mb-4">
        <select name="tahun" onchange="this.form.submit()">
            @foreach ($tahunList as $t)
                <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>

        <select name="nama_satker" onchange="this.form.submit()">
            <option value="">Semua Satker</option>
            @foreach ($namaSatkerList as $satkerName)
                <option value="{{ $satkerName }}" {{ $satkerName == request('nama_satker') ? 'selected' : '' }}>
                    {{ $satkerName }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th class="bg-blue">Nama Satker</th>
                <th class="bg-yellow">Total Tender Selesai</th>
                <th class="bg-green">Total Nilai Pagu</th>
                <th class="bg-gray">Kontrak yang belum input</th>
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
                    <td colspan="5" style="text-align:center; font-style:italic;">Tidak ada data.</td>
                </tr>
            @endforelse

            {{-- Total Row --}}
            <tr style="font-weight: bold; background-color: #f9fafb;">
                <td colspan="2" class="text-right">Total</td>
                <td>{{ $totals['total_paket'] }}</td>
                <td class="text-right">{{ number_format($totals['total_pagu'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totals['total_kontrak'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
