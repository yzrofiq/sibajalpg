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

        // Ambil nama satker dari StrukturAnggaran, ambil semua satker, dan tambahkan satker yang ada di TokoDaring
        $satkerFromStruktur = StrukturAnggaran::where('tahun_anggaran', $tahun)
            ->pluck('nama_satker', 'kd_satker');

        // Gabungkan nama satker dari TokoDaring
        $satkerFromData = $data->pluck('nama_satker', 'kd_satker');

        // Gabungkan dan filter nama satker unik berdasarkan kd_satker
        $allSatker = $satkerFromStruktur->merge($satkerFromData)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // SatkerList untuk dropdown tetap semua, termasuk yang tidak ada di TokoDaring
        $satkerList = StrukturAnggaran::where('tahun_anggaran', $tahun)->pluck('nama_satker', 'kd_satker');

        // Rekap semua satker, grupkan berdasarkan kd_satker, ambil nama satker dari StrukturAnggaran
        $rekap = collect();
        foreach ($data->groupBy('kd_satker') as $kdSatker => $transactions) {
            // Ambil nama satker dari StrukturAnggaran jika ada, jika tidak ambil dari TokoDaring
            $namaSatker = $satkerFromStruktur->get($kdSatker, $transactions->first()->nama_satker);

            // Gabungkan data transaksi untuk kd_satker yang sama
            $rekap[$kdSatker] = [
                'nama_satker' => $namaSatker, // Menampilkan nama satker
                'total_transaksi' => $transactions->count(),
                'nilai_transaksi' => $transactions->sum('valuasi'),
            ];
        }

        // Masukkan data satker yang tidak ada di TokoDaring (dari StrukturAnggaran)
        $satkerWithoutData = $satkerFromStruktur->diffKeys($satkerFromData);
        foreach ($satkerWithoutData as $kdSatker => $namaSatker) {
            $rekap[$kdSatker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ];
        }

        if ($satker !== 'Semua') {
            $selectedNamaSatker = $satkerList[$satker] ?? 'Satuan Kerja Tidak Diketahui';
        
            // Ambil data dari rekap jika ada, kalau tidak, set total transaksi 0
            $dataSatker = $rekap->get($satker, [
                'nama_satker' => $selectedNamaSatker,
                'total_transaksi' => 0,
                'nilai_transaksi' => 0,
            ]);
        
            // Force nama_satker tetap konsisten dari struktur anggaran (bukan fallback dari transaksi)
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
    $mode  = $request->input('mode', 'I'); // 'I' = inline / lihat, 'D' = download langsung


    // Ambil data Toko Daring
    $data = TokoDaring::where('tahun', $tahun)->get();

    // Gabung nama satker dari Struktur Anggaran + Toko Daring
    $satkerFromStruktur = StrukturAnggaran::where('tahun_anggaran', $tahun)
        ->pluck('nama_satker', 'kd_satker'); // ✅ benar

    $satkerFromData = $data->pluck('nama_satker', 'kd_satker'); // ❌ ini bisa overwrite nama yang valid

    // Gabungkan
    $allSatker = $satkerFromStruktur->merge($satkerFromData)
        ->filter()
        ->unique()
        ->sort()
        ->values(); // ✅ ini hanya untuk dropdown, tidak dipakai di sini

    // Bangun rekap
    $rekap = collect();
    foreach ($data->groupBy('kd_satker') as $kdSatker => $transactions) {
        $namaSatker = $satkerFromStruktur->get($kdSatker, $transactions->first()->nama_satker); // ✅ fallback
        $rekap[$kdSatker] = [
            'nama_satker' => $namaSatker,
            'total_transaksi' => $transactions->count(),
            'nilai_transaksi' => $transactions->sum('valuasi'),
        ];
    }

    // Tambahkan satker yang belum ada di data
    $satkerWithoutData = $satkerFromStruktur->diffKeys($satkerFromData);
    foreach ($satkerWithoutData as $kdSatker => $namaSatker) {
        $rekap[$kdSatker] = [
            'nama_satker' => $namaSatker,
            'total_transaksi' => 0,
            'nilai_transaksi' => 0,
        ];
    }

        // Total
        $totalTransaksi = $rekap->sum('total_transaksi');
        $totalNilai = $rekap->sum('nilai_transaksi');

        $rekap = $rekap->sortBy('nama_satker');
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
        return $pdf->output("laporan-tokodaring-{$tahun}.pdf", $mode);
    }
}


