<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EkatalogV5Paket;
use App\Models\EkatalogV6Paket;
use App\Models\Satker;
use Spipu\Html2Pdf\Html2Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EkatalogReportController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->input('tahun', date('Y'));
        $versi  = $request->input('versi', 'V5');
        $satker = $request->input('satker', 'Semua');
        $status = $request->input('status', 'Semua');  

        $model = $versi === 'V6' ? new EkatalogV6Paket() : new EkatalogV5Paket();
        $query = $model->newQuery()->where('tahun_anggaran', $tahun);

        if ($versi === 'V6') {
            $query->whereNotNull('nama_satker');
        }

        if ($status !== 'Semua') {
            if ($versi === 'V5') {
                $query->where('paket_status_str', 'Paket ' . $status);
            } else {
                $query->where('status_pkt', strtoupper($status) === 'PROSES' ? 'ON_PROCESS' : 'COMPLETED');
            }
        }

        $rawData = $query->get();

        $daftarSatker = $versi === 'V5'
            ? Satker::where('kd_klpd', 'D264')->pluck('nama_satker', 'kd_satker')
            : collect();

        $groupedData = $rawData->groupBy('kd_paket');

        $data = $groupedData->map(function ($items, $kd_paket) use ($versi, $daftarSatker) {
            $item = $items->first();

            $nama_satker = $versi === 'V5'
                ? ($daftarSatker[$item->satker_id] ?? '-')
                : $item->nama_satker;

            $status_raw = $versi === 'V6' ? $item->status_pkt : $item->paket_status_str;

            $status_label = $versi === 'V6'
                ? (strtoupper($status_raw) === 'ON_PROCESS' ? 'Paket Proses' :
                   (strtoupper($status_raw) === 'COMPLETED' ? 'Paket Selesai' : $status_raw))
                : $status_raw;

            return [
                'id_rup'        => $item->kd_rup ?? '-',
                'nama_satker'   => $nama_satker,
                'nama_paket'    => $versi === 'V6' ? $item->rup_nama_pkt : $item->nama_paket,
                'status'        => $status_label,
                'nilai_kontrak' => $items->sum('total_harga'),
            ];
        })->values();

        if ($satker !== 'Semua') {
            $data = $data->filter(fn($d) => $d['nama_satker'] === $satker)->values();
        }

        $totalPaket = $data->count();
        $totalNilai = $data->sum('nilai_kontrak');

        $satkerList = $versi === 'V5'
            ? Satker::where('kd_klpd', 'D264')->pluck('nama_satker')
            : EkatalogV6Paket::where('tahun_anggaran', $tahun)
                ->whereNotNull('nama_satker')
                ->pluck('nama_satker');

        $satkerList = $satkerList->filter()->unique()->sort()->values();
        $tahunTersedia = collect([2024, 2025]);

        if (auth()->user()->role_id == 2) {
            return view('users.E-purchasing.ekatalog', compact(
                'data', 'tahun', 'versi', 'satker', 'status',
                'totalPaket', 'totalNilai', 'tahunTersedia', 'satkerList'
            ));
        }

        return view('E-purchasing.ekatalog', compact(
            'data', 'tahun', 'versi', 'satker', 'status',
            'totalPaket', 'totalNilai', 'tahunTersedia', 'satkerList'
        ));
    }

    public function exportPdf(Request $request)
{
    $tahun  = $request->input('tahun', date('Y'));
    $versi  = $request->input('versi', 'V5');
    $satker = $request->input('satker', 'Semua');
    $status = $request->input('status', 'Semua');
    $mode   = $request->input('mode', 'I'); // 'I' = inline, 'D' = download

    $model = $versi === 'V6' ? new EkatalogV6Paket() : new EkatalogV5Paket();
    $query = $model->newQuery()->where('tahun_anggaran', $tahun);

    if ($versi === 'V6') {
        $query->whereNotNull('nama_satker');
    }

    // Filter status
    if ($status !== 'Semua') {
        if ($versi === 'V5') {
            $query->where('paket_status_str', 'Paket ' . $status);
        } else {
            $query->where('status_pkt', strtoupper($status) === 'PROSES' ? 'ON_PROCESS' : 'COMPLETED');
        }
    }

    $rawData = $query->get();

    $daftarSatker = $versi === 'V5'
        ? Satker::where('kd_klpd', 'D264')->pluck('nama_satker', 'kd_satker')
        : collect();

    // Group by kd_paket
    $grouped = $rawData->groupBy('kd_paket');

    $dataRekap = [];

    foreach ($grouped as $items) {
        $item = $items->first();

        $nama_satker = $versi === 'V5'
            ? ($daftarSatker[$item->satker_id] ?? '-')
            : $item->nama_satker;

        $total_harga = $items->sum('total_harga');

        if (!isset($dataRekap[$nama_satker])) {
            $dataRekap[$nama_satker] = [
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }

        $dataRekap[$nama_satker]['total_transaksi'] += 1;
        $dataRekap[$nama_satker]['nilai_transaksi'] += $total_harga;
    }

    // Jika filter satker digunakan, batasi rekap hanya pada satker tersebut
    if ($satker !== 'Semua') {
        $dataRekap = collect($dataRekap)
            ->filter(fn($v, $k) => $k === $satker)
            ->all();

        // Jika tidak ada data tapi satker dipilih, tetap tampilkan nol
        if (empty($dataRekap)) {
            $dataRekap[$satker] = [
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }
    }

    ksort($dataRekap);

    // Tanggal judul
    $tanggal = $tahun == 2024
        ? '31 Desember 2024'
        : Carbon::now()->locale('id')->translatedFormat('d F Y');

    // Render view ke HTML
    $html = view('E-purchasing.ekatalog-pdf', [
        'data' => $dataRekap,
        'tanggal' => $tanggal,
        'tahun' => $tahun,
        'versi' => $versi,
    ])->render();

    // Tambahkan CSS inline
    $html = '<style>
        body { font-family: sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>' . $html;

    // Buat PDF
    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->writeHTML($html);
    return $pdf->output("laporan-ekatalog-{$tahun}-{$versi}.pdf", $mode);
}

}
