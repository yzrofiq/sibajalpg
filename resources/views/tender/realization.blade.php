<style>
  td {
    border: 1px solid #000000; 
    padding: 5px; 
  }
</style>
<page>
  <div style="text-align: center; font-size: 14px">
    <p style="margin: 0;"><b>REALISASI PAKET TENDER</b></p>
    <p style="margin: 0;"><b>OPD PROVINSI LAMPUNG</b></p>
    <p style="margin: 0;"><b>TAHUN ANGGARAN {{ date("Y") }} S.D TANGGAL {{ date("d") }} {{ strtoupper(getMonthName(date("m"))) }} {{ date("Y") }}</b></p>
  </div>

  <br/><br/>

  <div>
    <table style="width: 100%; border-collapse: collapse; max-width: 100%; display: block; border: 1px solid #000000">
      <tr style="text-align: center;">
        <td rowspan="2" style="vertical-align: middle;">No.</td>
        <td rowspan="2" style="vertical-align: middle;">OPD</td>
        <td colspan="3">TOTAL PAKET TENDER</td>
        <td colspan="3">PAKET SELESAI</td>
        <td colspan="3">PROSES TENDER</td>
        <td rowspan="2" style="vertical-align: middle; font-size: 10px;">EFISIENSI</td>
        <td rowspan="2" style="vertical-align: middle;">%</td>
        <td rowspan="2" style="vertical-align: middle; font-size: 10px;">SUMBER<br/>DANA</td>
      </tr>
      <tr>
        <td style="font-size: 8px; text-align: center; vertical-align: middle;">TOTAL<br/>PAKET<br/>KESELU-<br/>RUHAN</td>
        <td style="font-size: 10px; text-align: center; vertical-align: middle;">NILAI<br/>DPA<br/>(Rp)</td>
        <td style="font-size: 10px; text-align: center; vertical-align: middle;">HPS<br/>(Rp)</td>
        <td style="font-size: 8px; text-align: center; vertical-align: middle;">TOTAL<br/>PAKET<br/>SELESAI</td>
        <td style="font-size: 10px; text-align: center; vertical-align: middle;">NILAI<br/>PAGU DPA<br/>(Rp)</td>
        <td style="font-size: 10px; text-align: center; vertical-align: middle;">HPS<br/>(Rp)</td>
        <td style="font-size: 8px; text-align: center; vertical-align: middle;">NILAI<br/>TERKONTRAK<br/>(Rp)</td>
        <td style="font-size: 8px; text-align: center; vertical-align: middle;">TOTAL<br/>PAKET<br/>DALAM<br/>PROSES</td>
        <td style="font-size: 10px; text-align: center; vertical-align: middle;">NILAI<br/>(Rp)</td>
      </tr>
      @foreach ($data as $item)
      <tr>
        <td style="font-size: 10px; text-align: center;">{{ $loop->iteration }}</td>
        <td style="font-size: 10px;">{{ $item['name'] }}</td>
        <td style="text-align: center; font-size: 10px;">{{ $item['total_package_count'] }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['total_dpa']) }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['total_hps']) }}</td>
        <td style="text-align: center; font-size: 10px;">{{ $item['done_package_count'] }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['done_pagu']) }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['done_hps']) }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['process_value']) }}</td>
        <td style="text-align: center; font-size: 10px;">{{ $item['process_package_count'] }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['value']) }}</td>
        <td style="text-align: right; font-size: 10px;">{{ moneyFormat($item['done_pagu'] - $item['process_value']) }}</td>
        <td style="text-align: right; font-size: 12px;">{{ $item['efficiency_percentage'] }}</td>
        <td style="text-align: center;" style="font-size: 12px;">{!! implode('<br/>', $item['source']) !!}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="text-align: right; vertical-align: middle; font-size: 12px;"><b>Total</b></td>
        <td style="vertical-align: middle; text-align: center; font-size: 10px;">{{ $total['total_package_count'] }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['total_dpa']) }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['total_hps']) }}</td>
        <td style="vertical-align: middle; text-align: center; font-size: 10px;">{{ $total['done_package_count'] }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['done_pagu']) }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['done_hps']) }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['process_value']) }}</td>
        <td style="vertical-align: middle; text-align: center; font-size: 10px;">{{ $total['process_package_count'] }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['value']) }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 10px;">{{ moneyFormat($total['done_pagu'] - $total['process_value']) }}</td>
        <td style="vertical-align: middle; text-align: right; font-size: 12px;">{{ $total['efficiency_percentage'] }}</td>
        <td style="text-align: center;" style="font-size: 12px;">{!! implode('<br/>', $total['source']) !!}</td>
      </tr>
    </table>
    <br/><br/><br/><br/>
    <table style="width: 90%;">
      <tr>
        <td style="width: 80%; border: 0;"></td>
        <td style="width: 29.5%; text-align: center; font-weight: bold; border: 0;">
          <p style="margin: 0;">KEPALA BIRO PENGADAAN</p>
          <p style="margin: 0;">BARANG DAN JASA,</p>
          <br/><br/><br/><br/><br/><br/><br/><br/>
          <p style="margin: 0;">SLAMET RIADI, S.Sos</p>
          <p style="margin: 0;">PEMBINA UTAMA MUDA</p>
          <p style="margin: 0;">NIP. 19670828 199903 1 005</p>
        </td>
      </tr>
    </table>
  </div>
</page>