<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TokoDaring;
use App\Models\StrukturAnggaran;
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

// Ambil nama satker dari StrukturAnggaran dan dari TokoDaring → gabungkan
$satkerFromStruktur = StrukturAnggaran::where('tahun_anggaran', $tahun)->pluck('nama_satker');
$satkerFromData = $data->pluck('nama_satker');
$allSatker = $satkerFromStruktur->merge($satkerFromData)
    ->filter()
    ->unique()
    ->sort()
    ->values();

// SatkerList untuk dropdown tetap semua
$satkerList = $allSatker;

// Rekap semua satker
$rekap = collect();
foreach ($allSatker as $namaSatker) {
    $filtered = $data->where('nama_satker', $namaSatker);
    $rekap[$namaSatker] = [
        'total_transaksi' => $filtered->count(),
        'nilai_transaksi' => $filtered->sum('valuasi'),
    ];
}

// Jika user memilih satu satker saja
if ($satker !== 'Semua') {
    $rekap = $rekap->only([$satker]);
}

$totalTransaksi = $rekap->sum('total_transaksi');
$totalNilai = $rekap->sum('nilai_transaksi');
$tahunTersedia = collect([2024, 2025]);

return view('E-purchasing.tokodaring', compact(
    'rekap', 'tahun', 'satker', 'totalTransaksi', 'totalNilai', 'tahunTersedia', 'satkerList'
));

}


public function exportPdf(Request $request)
{
    $tahun = $request->input('tahun', date('Y'));

    // Ambil data Toko Daring
    $data = TokoDaring::where('tahun', $tahun)->get();

    // Gabung nama satker dari Struktur Anggaran + TokoDaring
    $satkerFromStruktur = StrukturAnggaran::where('tahun_anggaran', $tahun)->pluck('nama_satker');
    $satkerFromData = $data->pluck('nama_satker');
    $allSatker = $satkerFromStruktur->merge($satkerFromData)
        ->filter()
        ->unique()
        ->sort()
        ->values();

    // Bangun rekap
    $rekap = collect();
    foreach ($allSatker as $namaSatker) {
        $filtered = $data->where('nama_satker', $namaSatker);
        $rekap[$namaSatker] = [
            'total_transaksi' => $filtered->count(),
            'nilai_transaksi' => $filtered->sum('valuasi'),
        ];
    }

    // Total
    $totalTransaksi = $rekap->sum('total_transaksi');
    $totalNilai = $rekap->sum('nilai_transaksi');

    // Tanggal Judul
    $tanggal = $tahun == 2024 
        ? 'Per Desember 2024'
        : 'Per ' . Carbon::now()->translatedFormat('d F Y');

    $html = view('E-purchasing.tokodaring-pdf', [
        'rekap' => $rekap,
        'tanggal' => $tanggal,
        'tahun' => $tahun,
    ])->render();

    $html = '<style>
        body { font-family: sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>' . $html;

    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->writeHTML($html);
    return $pdf->output("laporan-tokodaring-{$tahun}.pdf", 'I');
}




}
