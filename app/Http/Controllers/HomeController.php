<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonTenderPengumuman;
use App\Models\TokoDaring;
use App\Models\Tender;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EkatalogV5Paket;
use App\Models\EkatalogV6Paket;
use App\Models\StrukturAnggaran;
use App\Models\Swakelola;
use App\Models\Penyedia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        $kategoriChart2 = $request->input('kategori_chart2', 'non_tender'); // default: non_tender
        
        // ✅ Box summary count
        $nonTenderCount = getNonTenderCount();
        $tenderCount    = getTenderCount();
        $ekatalogCount  = getEkatalogCount();
        $belaCount      = getBelaCount();
        
        // ✅ CHART 1: Perbandingan Total Data Tender vs Non Tender per Tahun
        $totalNonTender = NonTenderPengumuman::where('tahun_anggaran', $tahun)
            ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
            ->count();        
        $totalTender = Tender::where('tahun_anggaran', $tahun)->count();
        $chart1Data = [
            'Non Tender' => $totalNonTender,
            'Tender' => $totalTender,
        ];
        
        // ✅ CHART 2: Distribusi Jenis Pengadaan Berdasarkan Kategori (Tender / Non Tender)
        if ($kategoriChart2 == 'tender') {
            $chart2Data = Tender::where('tahun_anggaran', $tahun)
                ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan');
        } else {
            $chart2Data = NonTenderPengumuman::where('tahun_anggaran', $tahun)
                ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan');
        }
        
        // ✅ Menghitung Total untuk Non Tender
        $totalNonTenderData = NonTenderPengumuman::where('tahun_anggaran', $tahun)
            ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
            ->select(
                DB::raw('COUNT(*) as package_count'),
                DB::raw('SUM(pagu) as total_pagu'),
                DB::raw('SUM(hps) as total_hps'),
                DB::raw('SUM(pagu - hps) as total_efficiency') // Calculate efficiency from pagu - hps
            )
            ->first();

        $totalNonTender = [
            'package_count' => $totalNonTenderData->package_count,
            'pagu' => $totalNonTenderData->total_pagu,
            'hps' => $totalNonTenderData->total_hps,
            'efficiency' => $totalNonTenderData->total_efficiency,
        ];

        // ✅ Menghitung Total untuk e-Katalog V5
        $totalEkatalogV5Data = EkatalogV5Paket::where('tahun_anggaran', $tahun)
            ->select(
                DB::raw('COUNT(DISTINCT kd_paket) as package_count'),  // DISTINCT untuk menghitung paket yang unik
                DB::raw('SUM(total_harga) as total_transaksi') // Total transaksi untuk V5
            )
            ->first();

        $totalEkatalogV5 = [
            'package_count' => $totalEkatalogV5Data->package_count,
            'total_transaksi' => $totalEkatalogV5Data->total_transaksi,
        ];

        // ✅ Menghitung Total untuk e-Katalog V6
        $totalEkatalogV6Data = EkatalogV6Paket::where('tahun_anggaran', $tahun)
            ->select(
                DB::raw('COUNT(DISTINCT kd_paket) as package_count'),  // DISTINCT untuk menghitung paket yang unik
                DB::raw('SUM(total_harga) as total_transaksi') // Total transaksi untuk V6
            )
            ->first();

        $totalEkatalogV6 = [
            'package_count' => $totalEkatalogV6Data->package_count,
            'total_transaksi' => $totalEkatalogV6Data->total_transaksi,
        ];

        // ✅ Menghitung Total untuk RUP (StrukturAnggaran, Swakelola, Penyedia)
        $strukturAnggaran = StrukturAnggaran::where('tahun_anggaran', $tahun)
            ->where('kd_klpd', 'D264')
            ->where('nama_klpd', 'Provinsi Lampung')
            ->get();
        
        $swakelola = Swakelola::where('tahun_anggaran', $tahun)
            ->where('kd_klpd', 'D264')
            ->where('nama_klpd', 'Provinsi Lampung')
            ->get();

        $penyedia = Penyedia::where('tahun_anggaran', $tahun)
            ->where('kd_klpd', 'D264')
            ->where('nama_klpd', 'Provinsi Lampung')
            ->get();

        // Menghitung total untuk Penyedia, Swakelola, Penyedia dalam Swakelola
        $totalRup = [
            'paket_penyedia' => $penyedia->count(),
            'pagu_penyedia' => $penyedia->sum('pagu'),
            'paket_swakelola' => $swakelola->count(),
            'pagu_swakelola' => $swakelola->sum('pagu'),
            'paket_dalam' => 0, // Placeholder, jika ada data lain terkait Penyedia dalam Swakelola
            'pagu_dalam' => 0,  // Placeholder
            'paket_total' => $penyedia->count() + $swakelola->count(),
            'pagu_total' => $penyedia->sum('pagu') + $swakelola->sum('pagu'),
        ];

        // ✅ Menghitung Total untuk Toko Daring (similar to TokoDaringReportController logic)
        $dataTokoDaring = TokoDaring::where('tahun', $tahun)
            ->get();

        // Gabungkan nama satker dari StrukturAnggaran
        $satkerFromStruktur = StrukturAnggaran::where('tahun_anggaran', $tahun)
            ->pluck('nama_satker', 'kd_satker');
        
        // Gabungkan nama satker dari TokoDaring
        $satkerFromData = $dataTokoDaring->pluck('nama_satker', 'kd_satker');
        
        // Gabungkan dan filter nama satker unik berdasarkan kd_satker
        $allSatker = $satkerFromStruktur->merge($satkerFromData)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Rekap data Toko Daring
        $rekapTokoDaring = collect();
        foreach ($dataTokoDaring->groupBy('kd_satker') as $kdSatker => $transactions) {
            $namaSatker = $satkerFromStruktur->get($kdSatker, $transactions->first()->nama_satker);

            $rekapTokoDaring[$kdSatker] = [
                'nama_satker' => $namaSatker,
                'total_transaksi' => $transactions->count(),
                'nilai_transaksi' => $transactions->sum('valuasi'),
            ];
        }

        // Menghitung total transaksi dan nilai transaksi
        $totalTokoDaringTransaksi = $rekapTokoDaring->sum('total_transaksi');
        $totalTokoDaringNilai = $rekapTokoDaring->sum('nilai_transaksi');

        // ✅ Tahun tersedia
        $availableYears = DB::table('non_tender_pengumuman')
            ->select(DB::raw("CAST(tahun_anggaran AS TEXT) AS tahun"))
            ->distinct()
            ->union(
                DB::table('tenders')
                    ->select(DB::raw("CAST(tahun_anggaran AS TEXT) AS tahun"))
                    ->distinct()
            )
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('users.home', compact(
            'tahun',
            'kategoriChart2',
            'nonTenderCount',
            'tenderCount',
            'ekatalogCount',
            'belaCount',
            'chart1Data',
            'chart2Data',
            'totalNonTender', // Non Tender totals
            'totalEkatalogV5', // E-Katalog V5 totals
            'totalEkatalogV6', // E-Katalog V6 totals
            'totalRup', // RUP totals
            'rekapTokoDaring', // Toko Daring rekap
            'totalTokoDaringTransaksi', // Total Transaksi Toko Daring
            'totalTokoDaringNilai', // Total Nilai Transaksi Toko Daring
            'availableYears'
        ));
    }
}
