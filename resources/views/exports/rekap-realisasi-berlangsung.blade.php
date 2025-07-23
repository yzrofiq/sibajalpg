<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Realisasi Pengadaan Berlangsung - Tahun {{ $tahun }}</title>
    <style>
    @page { margin: 10px; size: A4 landscape; }
    body { font-family: Arial, sans-serif; font-size: 8px; margin: 0; }

    h3 {
        text-align: center;
        margin-bottom: 12px;
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #000;
        padding: 3px 2px;
        text-align: center;
        font-size: 7.5px;
    }

    th {
        background-color: #e2e8f0;
        font-size: 8px;
    }

    .text-left { text-align: left; }
    .text-right { text-align: right; }

    /* Kolom width disesuaikan */
    th:nth-child(1), td:nth-child(1) { width: 2.5%; }
    th:nth-child(2), td:nth-child(2) { width: 19%; text-align: left; }
    th:nth-child(3), td:nth-child(3),
    th:nth-child(5), td:nth-child(5),
    th:nth-child(7), td:nth-child(7),
    th:nth-child(9), td:nth-child(9) { width: 6.5%; }

    th:nth-child(4), td:nth-child(4),
    th:nth-child(6), td:nth-child(6),
    th:nth-child(8), td:nth-child(8),
    th:nth-child(10), td:nth-child(10) { width: 10%; text-align: right; }

    .total-row {
        font-weight: bold;
        background-color: #fef9c3;
    }
</style>

</head>
<body>

<h3>Rekapitulasi Realisasi Pengadaan Berlangsung<br>Tahun {{ $tahun }}</h3>

<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Satker</th>
            <th colspan="2">Tender</th>
            <th colspan="2">Non-Tender</th>
            <th colspan="2">E-Katalog</th>
            <th colspan="2">Toko Daring</th>
        </tr>
        <tr>
            <th>Total Paket</th>
            <th>Total Pagu</th>
            <th>Total Paket</th>
            <th>Total Pagu</th>
            <th>Total Paket</th>
            <th>Total Nilai</th>
            <th>Total Paket</th>
            <th>Total Nilai</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = [
                'tender_paket' => 0, 'tender_nilai' => 0,
                'nontender_paket' => 0, 'nontender_nilai' => 0,
                'ekatalog_paket' => 0, 'ekatalog_nilai' => 0,
                'tokodaring_paket' => 0, 'tokodaring_nilai' => 0,
            ];
        @endphp

        @foreach ($data as $i => $row)
            @php
                $total['tender_paket'] += $row['total_paket_tender'];
                $total['tender_nilai'] += $row['total_nilai_tender'];
                $total['nontender_paket'] += $row['total_paket_nontender'];
                $total['nontender_nilai'] += $row['total_nilai_nontender'];
                $total['ekatalog_paket'] += $row['total_paket_ekatalog'];
                $total['ekatalog_nilai'] += $row['total_nilai_ekatalog'];
                $total['tokodaring_paket'] += $row['total_paket_tokodaring'];
                $total['tokodaring_nilai'] += $row['total_nilai_tokodaring'];
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left">{{ $row['nama_satker'] }}</td>
                <td>{{ $row['total_paket_tender'] }}</td>
                <td>{{ number_format($row['total_nilai_tender'], 0, ',', '.') }}</td>
                <td>{{ $row['total_paket_nontender'] }}</td>
                <td>{{ number_format($row['total_nilai_nontender'], 0, ',', '.') }}</td>
                <td>{{ $row['total_paket_ekatalog'] }}</td>
                <td>{{ number_format($row['total_nilai_ekatalog'], 0, ',', '.') }}</td>
                <td>{{ $row['total_paket_tokodaring'] }}</td>
                <td>{{ number_format($row['total_nilai_tokodaring'], 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <tr class="total-row">
            <td colspan="2">TOTAL</td>
            <td>{{ $total['tender_paket'] }}</td>
            <td>{{ number_format($total['tender_nilai'], 0, ',', '.') }}</td>
            <td>{{ $total['nontender_paket'] }}</td>
            <td>{{ number_format($total['nontender_nilai'], 0, ',', '.') }}</td>
            <td>{{ $total['ekatalog_paket'] }}</td>
            <td>{{ number_format($total['ekatalog_nilai'], 0, ',', '.') }}</td>
            <td>{{ $total['tokodaring_paket'] }}</td>
            <td>{{ number_format($total['tokodaring_nilai'], 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
