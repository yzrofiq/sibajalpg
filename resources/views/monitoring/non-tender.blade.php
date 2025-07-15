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
    <h1>Monitoring Non Tender</h1>

    {{-- 🔍 Filter Tahun dan Satker --}}
    <form method="GET" class="filter-form">
        <select name="tahun_anggaran" onchange="this.form.submit()">
            @foreach ($tahunList as $th)
                <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}</option>
            @endforeach
        </select>

        <select name="nama_satker" onchange="this.form.submit()">
            <option value="">Semua Satker</option>
            @foreach ($satkerList as $satkerName)
                <option value="{{ $satkerName }}" {{ request('nama_satker') == $satkerName ? 'selected' : '' }}>
                    {{ $satkerName }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th class="bg-blue">Nama Satker</th>
                    <th class="bg-yellow">Total Non Tender Selesai</th>
                    <th class="bg-green">Total Nilai Pagu</th>
                    <th class="bg-gray">Kontrak yang Belum Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-left">
                            <a href="{{ route('monitoring.kontrak.non_tender_detail', ['satker' => urlencode($item['nama_satker']), 'tahun_anggaran' => $tahun]) }}"
                               style="color: #1d4ed8;">
                                {{ $item['nama_satker'] }}
                            </a>
                        </td>
                        <td>{{ $item['total_non_tender_selesai'] }}</td>
                        <td class="text-right">{{ number_format($item['total_pagu'], 0, ',', '.') }}</td>
                        <td>{{ $item['kontrak_belum_input'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="font-style: italic;">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse

                {{-- Total row --}}
                <tr style="font-weight: bold; background-color: #f9fafb;">
                    <td colspan="2" class="text-right">Total</td>
                    <td>{{ $totals['total_non_tender_selesai'] }}</td>
                    <td class="text-right">{{ number_format($totals['total_pagu'], 0, ',', '.') }}</td>
                    <td>{{ $totals['kontrak_belum_input'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
