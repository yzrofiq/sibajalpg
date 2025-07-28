<style>
  td {
    border: 1px solid #000000;
    padding: 5px;
  }
  
</style>

<page>
  <div style="text-align: center; font-size: 14px">
    <p style="margin: 0;"><b>REALISASI PAKET NON TENDER</b></p>
    <p style="margin: 0;"><b>OPD PROVINSI LAMPUNG</b></p>

    <p style="margin: 0;"><b>
      @if ($month !== 'ALL' && $day !== 'ALL')
        TAHUN ANGGARAN {{ $year }} TANGGAL {{ $day }} {{ strtoupper(getMonthName($month)) }} {{ $year }}
      @elseif ($month !== 'ALL')
        TAHUN ANGGARAN {{ $year }} {{ strtoupper(getMonthName($month)) }} {{ $year }}
      @else
        TAHUN ANGGARAN {{ $year }} S.D TANGGAL {{ date("d") }} {{ strtoupper(getMonthName(date("m"))) }} {{ date("Y") }}
      @endif
    </b></p>
  </div>

  <br/>

  <div>
    <div style="text-align: center;">
      <table style="margin: auto; border-collapse: collapse; width: auto; border: 1px solid #000000;">
        <tr style="text-align: center;">
          <td rowspan="2">No.</td>
          <td rowspan="2">OPD</td>
          <td rowspan="2">STATUS</td>
          <td rowspan="2" style="font-size: 10px;">JUMLAH<br/>PAKET</td>
          <td colspan="4">JENIS PEKERJAAN</td>
          <td colspan="3">JUMLAH</td>
          <td rowspan="2">EFISIENSI</td>
        </tr>
        <tr>
          <td style="font-size: 10px;">KONSTRUKSI</td>
          <td style="font-size: 10px;">KONSULTASI</td>
          <td style="font-size: 10px;">BARANG</td>
          <td style="font-size: 10px;">JASA LAINNYA</td>
          <td style="font-size: 10px;">PAGU</td>
          <td style="font-size: 10px;">HPS</td>
          <td style="font-size: 10px;">NILAI TERKONTRAK</td>
        </tr>
        @foreach ($data as $item)
        <tr>
          <td style="text-align: center;">{{ $loop->iteration }}</td>
          <td style="font-size: 12px;">{{ $item['name'] }}</td>
          <td style="font-size: 12px;">
    @if (!empty($item['status_list']))
        @php
            $statusCounts = [];
            foreach ($item['status_list'] as $status) {
                $statusCounts[$status] = $item['status_count'][$status] ?? 0;
            }
        @endphp
        {{ implode(', ', collect($statusCounts)->map(fn($count, $status) => "$status ($count)")->toArray()) }}
    @else
        -
    @endif
</td>
          <td style="text-align: center;">{{ $item['package_count'] }}</td>
          <td style="text-align: center;">{{ $item['constructions'] }}</td>
          <td style="text-align: center;">{{ $item['consultations'] }}</td>
          <td style="text-align: center;">{{ $item['goods'] }}</td>
          <td style="text-align: center;">{{ $item['services'] }}</td>
          <td style="text-align: right;">{{ format_hps_pagu($item['pagu']) }}</td>
          <td style="text-align: right;">{{ format_hps_pagu($item['hps']) }}</td>
          <td style="text-align: right;">{{ moneyFormat($item['nilai_terkontrak']) }}</td>
          <td style="text-align: right;">{{ moneyFormat($item['efficiency']) }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="2" style="font-size: 12px; text-align: right;"><b>Total</b></td>
          <td style="text-align: center;">{{ $total['package_count'] }}</td>
          <td style="text-align: center;">{{ $total['package_count'] }}</td>
          <td style="text-align: center;">{{ $total['constructions'] }}</td>
          <td style="text-align: center;">{{ $total['consultations'] }}</td>
          <td style="text-align: center;">{{ $total['goods'] }}</td>
          <td style="text-align: center;">{{ $total['services'] }}</td>
          <td style="text-align: right;">{{ format_hps_pagu($total['pagu']) }}</td>
          <td style="text-align: right;">{{ format_hps_pagu($total['hps']) }}</td>
          <td style="text-align: right;">{{ moneyFormat($total['nilai_terkontrak']) }}</td>
          <td style="text-align: right;">{{ moneyFormat($total['efficiency']) }}</td>
        </tr>
      </table>
    </div>

    <br/><br/><br/><br/>

    <table style="width: 90%;">
      <tr>
        <td style="width: 80%; border: 0;"></td>
        <td style="width: 29.5%; text-align: center; font-weight: bold; border: 0;">
          <p style="margin: 0;">KEPALA BIRO PENGADAAN</p>
          <p style="margin: 0;">BARANG DAN JASA,</p>
          <br/><br/><br/><br/><br/><br/><br/><br/>
          <p style="margin: 0;">PUADI JAILANI,SH.,MH</p>
          <p style="margin: 0;">PEMBINA UTAMA MUDA</p>
          <p style="margin: 0;">NIP. 19650905 199103 1 004</p>
        </td>
      </tr>
    </table>
  </div>
</page>
