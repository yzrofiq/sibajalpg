<style>
  td {
    border: 1px solid #000000; 
    padding: 5px; 
  }
</style>

<page>
<div style="text-align: center; font-size: 14px">
    {{-- Tampilkan judul dari controller --}}
    @foreach (explode("\n", $title) as $line)
      <p style="margin: 0;"><b>{{ $line }}</b></p>
    @endforeach
  </div>

  <br/>

  <div>
    <table style="width: 100%; border-collapse: collapse; max-width: 100%; display: block; border: 1px solid #000000">
      <tr style="text-align: center;">
        <td rowspan="2" style="vertical-align: middle;">No.</td>
        <td rowspan="2" style="vertical-align: middle;">OPD</td>
        <td rowspan="2" style="vertical-align: middle; font-size: 10px;">JUMLAH<br/>PAKET</td>
        <td colspan="4">JENIS PEKERJAAN</td>
        <td colspan="3">JUMLAH</td>
        <td rowspan="2" style="vertical-align: middle;">EFISIENSI</td>
      </tr>
      <tr>
        <td style="font-size: 10px; text-align: center;">KONSTRUKSI</td>
        <td style="font-size: 10px; text-align: center;">KONSULTASI</td>
        <td style="font-size: 10px; text-align: center;">BARANG</td>
        <td style="font-size: 10px; text-align: center;">JASA LAINNYA</td>
        <td style="font-size: 10px; text-align: center;">PAGU</td>
        <td style="font-size: 10px; text-align: center;">HPS</td>
        <td style="font-size: 10px; text-align: center;">NILAI TERKONTRAK</td>
      </tr>
      @foreach ($data as $item)
      <tr>
        <td style="text-align: center;">{{ $loop->iteration }}</td>
        <td style="font-size: 12px;">{{ $item['name'] }}</td>
        <td style="text-align: center;">{{ $item['package_count'] }}</td>
        <td style="text-align: center;">{{ $item['constructions'] }}</td>
        <td style="text-align: center;">{{ $item['consultations'] }}</td>
        <td style="text-align: center;">{{ $item['goods'] }}</td>
        <td style="text-align: center;">{{ $item['services'] }}</td>
        <td style="text-align: right;">{{ moneyFormat($item['pagu']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($item['hps']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($item['nilai_terkontrak']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($item['efficiency']) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="font-size: 12px; text-align: right;"><b>Total</b></td>
        <td style="text-align: center;">{{ $total['package_count'] }}</td>
        <td style="text-align: center;">{{ $total['constructions'] }}</td>
        <td style="text-align: center;">{{ $total['consultations'] }}</td>
        <td style="text-align: center;">{{ $total['goods'] }}</td>
        <td style="text-align: center;">{{ $total['services'] }}</td>
        <td style="text-align: right;">{{ moneyFormat($total['pagu']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($total['hps']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($total['nilai_terkontrak']) }}</td>
        <td style="text-align: right;">{{ moneyFormat($total['efficiency']) }}</td>
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
