<?php

namespace App\Http\Controllers; // ✅ Diperlukan agar Laravel mengenali controller ini

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TenderPengumumanData;
use App\Models\NonTenderPengumuman;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $kategoriChart2 = $request->input('kategori_chart2', 'non_tender');
    
        // Total Tender
        $totalTender = TenderPengumumanData::where('tahun', $tahun)
            ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
            ->count();
    
        // Total Non Tender
        $totalNonTender = NonTenderPengumuman::where('tahun_anggaran', $tahun)
            ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
            ->count();
    
        $chart1Data = [
            'Tender' => $totalTender,
            'Non Tender' => $totalNonTender,
        ];
    
        // Chart 2 - Berdasarkan Kategori
        if ($kategoriChart2 === 'tender') {
            $chart2Data = TenderPengumumanData::where('tahun', $tahun)
                ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan')
                ->toArray(); // Pastikan array, bukan Collection
        } else {
            $chart2Data = NonTenderPengumuman::where('tahun_anggaran', $tahun)
                ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
                ->select('jenis_pengadaan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jenis_pengadaan')
                ->pluck('jumlah', 'jenis_pengadaan')
                ->toArray(); // Pastikan array
        }
    
        return view('dashboard.index', compact('tahun', 'kategoriChart2', 'chart1Data', 'chart2Data'));
    }
    
}
