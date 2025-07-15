<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Kontrak - PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #ddd; }
        tfoot td { font-weight: bold; background-color: #f1f5f9; }
    </style>
</head>
<body>
    <h2>Detail Tender Selesai Tanpa Kontrak</h2>
    <p><strong>Satker:</strong> {{ $satker }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Kode Tender</th>
                <th>Nama Paket</th>
                <th style="width: 20%;">Pagu (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $item->kd_tender }}</td>
                <td>{{ $item->nama_paket }}</td>
                <td style="text-align: right;">{{ number_format($item->pagu, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; font-style: italic;">Tidak ada data tender yang belum input kontrak.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center;">Total Pagu</td>
                <td style="text-align: center;">{{ number_format($totalPagu, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
