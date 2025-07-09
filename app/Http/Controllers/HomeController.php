<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonTenderPengumuman;
use App\Models\Tender;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EkatalogV5Paket;
use App\Models\EkatalogV6Paket;

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
        ->count();        $totalTender    = Tender::where('tahun_anggaran', $tahun)->count();
        $chart1Data = [
            'Non Tender' => $totalNonTender,
            'Tender' => $totalTender,
        ];
    
        // ✅ CHART 2: Distribusi Jenis Pengadaan Berdasarkan Kategori (Tender / Non Tender)
        if ($kategoriChart2 == 'tender') {
            $chart2Data = Tender::where('tahun_anggaran', $tahun)
                ->whereIn('status_tender', ['Selesai', 'Berlangsung']) // penting!
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan');
        } else {
            $chart2Data = NonTenderPengumuman::where('tahun_anggaran', $tahun)
                ->whereIn('status_nontender', ['Selesai', 'Berlangsung']) // penting!
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan');
        }
        
    
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
            'availableYears'
        ));
    }
}    