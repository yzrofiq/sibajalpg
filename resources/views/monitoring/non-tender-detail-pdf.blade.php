<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Non Tender - PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #ddd; }
        tfoot td { font-weight: bold; background-color: #f1f5f9; }
    </style>
</head>
<body>
    <h2>Detail Non Tender Selesai Tanpa Kontrak</h2>
    <p><strong>Satker:</strong> {{ $satker }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Non Tender</th>
                <th>Nama Paket</th>
                <th>Pagu (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $item->kd_nontender }}</td>
                <td>{{ $item->nama_paket }}</td>
                <td style="text-align: right;">{{ number_format($item->pagu, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center;">Total Pagu</td>
                <td style="text-align: right;">{{ number_format($totalPagu, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
