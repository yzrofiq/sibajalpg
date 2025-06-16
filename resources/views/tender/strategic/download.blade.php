<style>
    td {
        border: 1px solid #000000; 
        padding: 5px;
    }
</style>
<page>
    <div style="width: 100%">
        <p style="line-height: 1rem; font-weight: bold; text-align: center; margin-bottom: 0px;">LAPORAN 10 (SEPULUH) PAKET STRATEGIS</p>
        <p style="line-height: 1rem; font-weight: bold; text-align: center; margin-bottom: 0px;">PEMERINTAH PROVINSI LAMPUNG TAHUN {{ date("Y") }}</p>
    </div>    

    <div style="width: 100%; margin-top: 12px; font-size: 10px;">
        <table style="border-collapse: collapse; width: 100%; max-width: 100%; display: block;">
            <tr>
                <td style="font-weight: bold; vertical-align: middle; text-align: center;">NO</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 12%;">NAMA PAKET</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 6%;">OPD</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 3%;">PAGU</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 3%;">HPS</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 3%;">PAGU PEMENANG</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 10%; font-size: 8px;">NAMA PERUSAHAAN</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 6%;">NPWP</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 10%;">ALAMAT</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 6%; font-size: 8px;">METODE PBJ</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center; width: 10%;">PELAKSANAAN</td>
                <td style="font-weight: bold; vertical-align: middle; text-align: center;">SUMBER ANGGARAN</td>
            </tr>
            @foreach ($data as $key => $item)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }} </td>
                <td style="width: 10%;">{{ $item->tender->nama_paket }} </td>
                <td style="width: 6%; font-size: 8px;">{{ ($item->tender->satker) ? $item->tender->satker->nama_satker : 'Tidak ditemukan' }} </td>
                <td>Rp{{ moneyFormat($item->tender->pagu) }} </td>
                <td>Rp{{ moneyFormat($item->tender->hps) }} </td>
                <td>Rp{{ moneyFormat($item->tender->nilai_kontrak) }} </td>
                <td style="width: 10%; font-size: 9px;">{{ $item->tender->nama_penyedia }} </td>
                <td style="font-size: 9px;">{{ $item->tender->npwp_penyedia }} </td>
                <td>{{ $item->address }} </td>
                <td>{{ $item->mtd_pemilihan }}</td>
                <td style="width: 10%; font-size: 9px;">{{ $item->implementation }} </td>
                <td >{{ $item->ang }} </td>
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