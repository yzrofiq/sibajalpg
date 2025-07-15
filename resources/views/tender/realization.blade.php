<style>
  td {
    border: 1px solid #000000;
    padding: 5px;
    font-size: 9px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }

  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  .header-title {
    font-size: 12px;
    text-align: center;
    margin-bottom: 5px;
  }

  .signature {
    width: 100%;
    margin-top: 50px;
  }
</style>

<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
<div style="text-align: center; font-size: 14px">
    <p style="margin: 0;"><b>REALISASI PAKET TENDER</b></p>
    <p style="margin: 0;"><b>OPD PROVINSI LAMPUNG</b></p>
    <p style="margin: 0;"><b>TAHUN ANGGARAN {{ $year }} 
S.D TANGGAL {{ $day == 'ALL' ? date('d') : $day }} 
{{ strtoupper(getMonthName($month == 'ALL' ? date('m') : $month)) }} {{ $year }}</b></p>

  </div>

  <table>
    <thead>
      <tr class="text-center">
        <td rowspan="2" style="width: 3%;">No.</td>
        <td rowspan="2" style="width: 20%;">Nama OPD</td>
        <td rowspan="2" style="width: 7%;">Jumlah Paket</td>
        <td colspan="4">Jenis Pekerjaan</td>
        <td colspan="3">Jumlah Nilai</td>
        <td rowspan="2" style="width: 10%;">Efisiensi</td>
      </tr>
      <tr class="text-center">
        <td style="width: 6%;">Konstruksi</td>
        <td style="width: 6%;">Konsultasi</td>
        <td style="width: 6%;">Barang</td>
        <td style="width: 6%;">Jasa Lainnya</td>
        <td style="width: 10%;">Pagu</td>
        <td style="width: 10%;">HPS</td>
        <td style="width: 10%;">Nilai Terkontrak</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $item['name'] }}</td>
        <td class="text-center">{{ $item['package_count'] }}</td>
        <td class="text-center">{{ $item['constructions'] }}</td>
        <td class="text-center">{{ $item['consultations'] }}</td>
        <td class="text-center">{{ $item['goods'] }}</td>
        <td class="text-center">{{ $item['services'] }}</td>
        <td class="text-right">{{ moneyFormat($item['pagu']) }}</td>
        <td class="text-right">{{ moneyFormat($item['hps']) }}</td>
        <td class="text-right">{{ moneyFormat($item['nilai_terkontrak']) }}</td>
        <td class="text-right">{{ moneyFormat($item['efficiency']) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" class="text-right"><b>Total</b></td>
        <td class="text-center">{{ $total['package_count'] }}</td>
        <td class="text-center">{{ $total['constructions'] }}</td>
        <td class="text-center">{{ $total['consultations'] }}</td>
        <td class="text-center">{{ $total['goods'] }}</td>
        <td class="text-center">{{ $total['services'] }}</td>
        <td class="text-right">{{ moneyFormat($total['pagu']) }}</td>
        <td class="text-right">{{ moneyFormat($total['hps']) }}</td>
        <td class="text-right">{{ moneyFormat($total['nilai_terkontrak']) }}</td>
        <td class="text-right">{{ moneyFormat($total['efficiency']) }}</td>
      </tr>
    </tbody>
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
</page>
