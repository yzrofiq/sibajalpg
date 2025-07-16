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

        // Pilih model berdasarkan versi e-Katalog
        $model = $versi === 'V6' ? new EkatalogV6Paket() : new EkatalogV5Paket();
        $query = $model->newQuery()->where('tahun_anggaran', $tahun);

        // Filter status berdasarkan versi

        if ($status !== 'Semua') {
            if ($versi === 'V5') {
                $query->where('paket_status_str', 'Paket ' . $status);
            } else {
                $query->where('status_pkt', strtoupper($status) === 'PROSES' ? 'ON_PROCESS' : 'COMPLETED');
            }
        }

        $rawData = $query->get();


        // Ambil daftar nama satker dari tabel Satker (bukan StrukturAnggaran)
        $daftarSatker = Satker::where('tahun_anggaran', $tahun)
            ->where('kd_satker', '<>', '350504')  // Skip satker dengan kd_satker 350504
            ->pluck('nama_satker', 'kd_satker');

        // Kelompokkan data berdasarkan kd_paket
        $groupedData = $rawData->groupBy('kd_paket');

        // Membuat data rekap per satker
        $data = $groupedData->map(function ($items, $kd_paket) use ($versi, $daftarSatker) {
            $item = $items->first();

            // Ambil nama satker dari tabel Satker, pastikan menghindari kd_satker 350504
            if ($item->satker_id == '350504') {
                return null; // Skip satker dengan kd_satker 350504
            }

            $nama_satker = $daftarSatker->get($item->satker_id, '-');
            $status_raw = $versi === 'V6' ? $item->status_pkt : $item->paket_status_str;

            // Tentukan status paket

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

        })->filter()->values();  // Filter untuk menghapus nilai null (skip satker 350504)

        // Filter satker jika dipilih

        if ($satker !== 'Semua') {
            $data = $data->filter(fn($d) => $d['nama_satker'] === $satker)->values();
        }

        $totalPaket = $data->count();
        $totalNilai = $data->sum('nilai_kontrak');


        // Dropdown satkerList dari tabel Satker
        $satkerList = Satker::where('tahun_anggaran', $tahun)
            ->where('kd_satker', '<>', '350504')  // Skip satker dengan kd_satker 350504
            ->pluck('nama_satker', 'kd_satker')
            ->sort()
            ->values();

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
        $mode   = $request->input('mode', 'I');

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

        $daftarSatker = Satker::where('tahun_anggaran', $tahun)
            ->where('kd_satker', '<>', '350504')  // Skip satker dengan kd_satker 350504
            ->pluck('nama_satker', 'kd_satker');

        $grouped = $rawData->groupBy('kd_paket');
        $dataRekap = [];

        foreach ($grouped as $items) {
            $item = $items->first();
            // Skip satker dengan kd_satker 350504
            if ($item->satker_id == '350504') {
                continue;
            }

            $nama_satker = $daftarSatker[$item->satker_id] ?? '-';
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

        // Filter Satker jika dipilih
        if ($satker !== 'Semua') {
            $dataRekap = collect($dataRekap)->only([$satker])->toArray();
        }

        ksort($dataRekap);

        $tanggal = $tahun == 2024
            ? '31 Desember 2024'
            : Carbon::now()->locale('id')->translatedFormat('d F Y');

        $html = view('E-purchasing.ekatalog-pdf', [
            'data' => $dataRekap,
            'tanggal' => $tanggal,
            'tahun' => $tahun,
            'versi' => $versi,
            'satker' => $satker,
            'status' => $status,
        ])->render();

        $html = '<style>
            body { font-family: sans-serif; font-size: 11px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000; padding: 6px; text-align: left; }
            th { background-color: #eee; }
        </style>' . $html;

        $pdf = new Html2Pdf('P', 'A4', 'fr');
        $pdf->writeHTML($html);
        return $pdf->output("laporan-ekatalog-{$tahun}-{$versi}.pdf", $mode);
    }

}
