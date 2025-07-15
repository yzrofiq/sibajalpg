@extends('layouts.user-adminlte')


@push('style')
<style>
    .table-wrapper {
        overflow-x: auto;
        margin-top: 1rem;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }

    th, td {
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background-color: #f1f5f9;
        font-weight: bold;
        text-align: center;
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }

    .header-blue { color: #1d4ed8; }
    .header-green { color: #16a34a; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-blue-700 mb-4">
        Daftar Tender Selesai Tanpa Kontrak
    </h1>

    <div class="mb-4 text-gray-600">
        <strong>Satker:</strong> {{ $satker }} <br>
    </div>

    <a href="{{ route('monitoring.kontrak.detail.pdf', ['tahun' => request('tahun'), 'satker' => request('satker')]) }}"
    target="_blank"
           style="padding: 6px 14px; background-color: #047857; color: white; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            📄 Export PDF
        </a>

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse text-sm">
            <thead class="bg-blue-100 text-gray-700">
                <tr>
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Kode Tender</th>
                    <th class="border px-4 py-2">Nama Paket</th>
                    <th class="border px-4 py-2">Pagu</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($data as $i => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2 text-center">{{ $i + 1 }}</td>
                        <td class="border px-4 py-2">{{ $item->kd_tender }}</td>
                        <td class="border px-4 py-2">{{ $item->nama_paket }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($item->pagu, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-4 text-center text-gray-500 italic">
                            Tidak ada data tender yang belum input kontrak.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="border px-4 py-2 text-justify font-bold">Total Pagu</td>
                    <td class="border px-4 py-2 text-right font-bold text-green-700">
                        {{ number_format($totalPagu, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
