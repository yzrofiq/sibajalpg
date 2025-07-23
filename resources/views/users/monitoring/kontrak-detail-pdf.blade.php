<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Tender Belum Kontrak</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        h4 {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
            word-break: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mt-2 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h4>DAFTAR TENDER BELUM INPUT KONTRAK<br>{{ $tahun }}</h4>

    @if (!empty($namaSatker))
        <p><strong>Satker:</strong> {{ $namaSatker }}</p>
    @endif

    <table class="mt-2">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 15%;">Kode Tender</th>
                <th>Nama Paket</th>
                <th style="width: 18%;">Pagu (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->kd_tender }}</td>
                    <td>{{ $item->nama_paket }}</td>
                    <td class="text-right">{{ number_format($item->pagu, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center"><em>Tidak ada data tender yang belum input kontrak.</em></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
