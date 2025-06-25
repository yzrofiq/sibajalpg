<h3 style="text-align: center;">LAPORAN E-KATALOG<br>{{ $tanggal }}</h3>

<style>
  body {
    font-family: sans-serif;
    font-size: 16px;
  }

  table {
    width: 90%; /* sedikit lebih kecil dari 100% agar tidak terlalu mentok ke kanan */
    margin: 0 auto 20px auto; /* ini yang bikin tabel betul-betul center */
    border-collapse: collapse;
    font-size: 11px;
  }

  th, td {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
    vertical-align: middle;
    word-wrap: break-word;
  }

  th {
    background-color: #f0f0f0;
  }

  th:nth-child(1), td:nth-child(1) { width: 5%; }
  th:nth-child(2), td:nth-child(2) { width: 50%; text-align: left; }
  th:nth-child(3), td:nth-child(3) { width: 20%; }
  th:nth-child(4), td:nth-child(4) { width: 25%; text-align: right; }

  .ttd-table {
    width: 100%;
    margin-top: 40px;
    border: none;
  }

  .ttd-table td {
    border: none;
    font-weight: bold;
  }

  .ttd-table p {
    margin: 0;
  }
</style>


<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>SATUAN KERJA</th>
      <th>TOTAL TRANSAKSI</th>
      <th>NILAI TRANSAKSI</th>
    </tr>
  </thead>
  <tbody>
    @php $i = 1; @endphp
    @foreach($data as $nama_satker => $rekap)
      <tr>
        <td>{{ $i++ }}</td>
        <td style="text-align: left;">{{ $nama_satker }}</td>
        <td>{{ $rekap['total_transaksi'] }}</td>
        <td style="text-align: right;">Rp{{ number_format($rekap['nilai_transaksi'], 0, ',', '.') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

{{-- Tanda Tangan --}}
<table class="ttd-table">
  <tr>
    <td style="width: 60%;"></td>
    <td style="width: 40%; text-align: center;">
      <p>KEPALA BIRO PENGADAAN</p>
      <p>BARANG DAN JASA,</p>
      <br><br><br><br><br>
      <p>SLAMET RIADI, S.Sos</p>
      <p>PEMBINA UTAMA MUDA</p>
      <p>NIP. 19670828 199903 1 005</p>
    </td>
  </tr>
</table>
