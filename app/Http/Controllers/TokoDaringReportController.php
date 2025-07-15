<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TokoDaring;
use App\Models\Satker;
use Carbon\Carbon;
use Spipu\Html2Pdf\Html2Pdf;

class TokoDaringReportController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $satker = $request->input('satker', 'Semua');

        // Ambil semua data transaksi Toko Daring untuk tahun tertentu
        $data = TokoDaring::where('tahun', $tahun)->get();

        // Ambil nama satker dari tabel master Satker (bukan dari StrukturAnggaran)
        $satkerMaster = Satker::where('kd_klpd', 'D264')
            ->pluck('nama_satker', 'kd_satker');

        // Gabungkan nama satker dari TokoDaring (jika ada nama baru)
        $satkerFromData = $data->pluck('nama_satker', 'kd_satker');
        $allSatker = $satkerMaster->merge($satkerFromData)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Dropdown Satker pakai sumber tetap dari master
        $satkerList = $satkerMaster;

        // Bangun rekap
        $rekap = collect();
        foreach ($data->groupBy('kd_satker') as $kdSatker => $transactions) {
            $namaSatker = $satkerMaster->get($kdSatker, $transactions->first()->nama_satker);
            $rekap[$kdSatker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => $transactions->count(),
                'nilai_transaksi' => $transactions->sum('valuasi'),
            ];
        }

        // Tambahkan Satker yang tidak ada transaksi
        $satkerWithoutData = $satkerMaster->diffKeys($satkerFromData);
        foreach ($satkerWithoutData as $kdSatker => $namaSatker) {
            $rekap[$kdSatker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }

        if ($satker !== 'Semua') {
            $selectedNamaSatker = $satkerList[$satker] ?? 'Satuan Kerja Tidak Diketahui';

            $dataSatker = $rekap->get($satker, [
                'nama_satker' => $selectedNamaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ]);

            $dataSatker['nama_satker'] = $selectedNamaSatker;

            $rekap = collect([
                $satker => $dataSatker
            ]);
        }

        $totalTransaksi = $rekap->sum('total_transaksi');
        $totalNilai = $rekap->sum('nilai_transaksi');
        $tahunTersedia = collect([2024, 2025]);

        if (auth()->user()->role_id == 2) {
            return view('users.E-purchasing.tokodaring', compact(
                'rekap', 'tahun', 'satker', 'totalTransaksi', 'totalNilai', 'tahunTersedia', 'satkerList'
            ));
        }

        return view('E-purchasing.tokodaring', compact(
            'rekap', 'tahun', 'satker', 'totalTransaksi', 'totalNilai', 'tahunTersedia', 'satkerList'
        ));
    }

    public function exportPdf(Request $request)
{
    $tahun = $request->input('tahun', date('Y'));
    $satker = $request->input('satker', 'Semua');
    $mode   = $request->input('mode', 'I'); // 'I' = inline / lihat, 'D' = download

    $data = TokoDaring::where('tahun', $tahun);

    if ($satker !== 'Semua') {
        $data = $data->where('kd_satker', $satker);
    }

    $data = $data->get();

    // Master Satker dari referensi
    $satkerMaster = Satker::where('kd_klpd', 'D264')->pluck('nama_satker', 'kd_satker');

    $satkerFromData = $data->pluck('nama_satker', 'kd_satker');

    $rekap = collect();
    foreach ($data->groupBy('kd_satker') as $kdSatker => $transactions) {
        $namaSatker = $satkerMaster->get($kdSatker, $transactions->first()->nama_satker);
        $rekap[$kdSatker] = [
            'nama_satker' => $namaSatker,
            'total_transaksi' => $transactions->count(),
            'nilai_transaksi' => $transactions->sum('valuasi'),
        ];
    }

    // Jika filter satker = Semua, tampilkan juga yang 0
    if ($satker === 'Semua') {
        $satkerWithoutData = $satkerMaster->diffKeys($satkerFromData);
        foreach ($satkerWithoutData as $kdSatker => $namaSatker) {
            $rekap[$kdSatker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }
    } else {
        // Satker dipilih spesifik, tampilkan walaupun tidak ada data
        $namaSatker = $satkerMaster[$satker] ?? $satkerFromData[$satker] ?? 'Satuan Kerja Tidak Diketahui';
        if (!isset($rekap[$satker])) {
            $rekap[$satker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }
    }

    $rekap = $rekap->sortBy('nama_satker');
    $totalTransaksi = $rekap->sum('total_transaksi');
    $totalNilai = $rekap->sum('nilai_transaksi');

    $tanggal = $tahun == 2024
        ? 'Per Desember 2024'
        : 'Per ' . Carbon::now()->translatedFormat('d F Y');

    $html = view('E-purchasing.tokodaring-pdf', [
        'rekap' => $rekap,
        'tanggal' => $tanggal,
        'tahun' => $tahun,
        'totalTransaksi' => $totalTransaksi,
        'totalNilai' => $totalNilai,
        'judul' => $satker !== 'Semua' ? $rekap->first()['nama_satker'] : null,
    ])->render();

    $html = '<style>
        body { font-family: sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>' . $html;

    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->writeHTML($html);
    return $pdf->output("laporan-tokodaring-{$tahun}.pdf", $mode);
}

}
