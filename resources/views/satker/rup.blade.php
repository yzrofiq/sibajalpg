<style>
    td {
      border: 1px solid #000000; 
      padding: 5px; 
    }
  </style>
  <page>
    <div style="text-align: center; font-size: 14px">
      <p style="margin: 0;"><b>DATA RENCANA UMUM PENGADAAN (RUP)</b></p>
      <p style="margin: 0;"><b>PEMERINTAH PROVINSI LAMPUNG</b></p>
      <p style="margin: 0;"><b>PER TANGGAL {{ date("d") }} {{ strtoupper(getMonthName(date("m"))) }} {{ date("Y") }}</b></p>
    </div>
  
    <br/>
  
    <div>
      <table style="width: 100%; border-collapse: collapse; max-width: 100%; display: block; border: 1px solid #000000">
        <tr style="text-align: center;">
          <td rowspan="2" style="text-align: center; vertical-align: middle;">No.</td>
          <td rowspan="2" style="text-align: center; vertical-align: middle;">SATUAN KERJA</td>
          <td colspan="2" style="width: 13%; text-align: center;">PENYEDIA</td>
          <td colspan="2" style="width: 13%; text-align: center;">SWAKELOLA</td>
          <td colspan="3" style="width: 22%; text-align: center;">PENYEDIA DALAM SWAKELOLA</td>
          <td>TOTAL</td>
        </tr>
        <tr>
          <td style="vertical-align: middle; text-align: center;">PAKET</td>
          <td style="vertical-align: middle; text-align: center;">PAGU</td>
          <td style="vertical-align: middle; text-align: center;">PAKET</td>
          <td style="vertical-align: middle; text-align: center;">PAGU</td>
          <td style="vertical-align: middle; text-align: center;">PAKET</td>
          <td style="vertical-align: middle; text-align: center;">PAGU</td>
          <td style="text-align: center; font-size: 10px;">TOTAL<br/>DALAM PAKET</td>
          <td>TOTAL PAGU</td>
        </tr>
        @foreach ($data as $item)
        <tr>
          <td style="text-align: center;">{{ $loop->iteration }}</td>
          <td>{{ $item['name'] }}</td>
          @for ($i = 2; $i <= 9; $i++)
          <td>{{ moneyFormat($item['data'][$i]) }}</td>
          @endfor
        </tr>
        @endforeach
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