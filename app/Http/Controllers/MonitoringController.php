<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Satker;

use App\Models\SwakelolaRealisasi;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ INI yang benar   
class MonitoringController extends Controller
{
 

    public function presentaseRealisasi(Request $request)
{
    $tahunParam = $request->get('tahun');

    // Tangani kondisi jika hanya satker dipilih, tahun kosong → ambil semua tahun yang tersedia
    if (empty($tahunParam) && $request->filled('satker')) {
        $tahunList = DB::table('struktur_anggarans')
            ->select('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran')
            ->pluck('tahun_anggaran')
            ->toArray();
    } elseif (!empty($tahunParam)) {
        $tahunList = is_array($tahunParam) ? $tahunParam : [$tahunParam];
    } else {
        $tahunList = [2024, 2025]; // Default
    }

    $tahunAkhir = end($tahunList);

    // Ambil seluruh satker dari model Satker
    $satkers = Satker::select('kd_satker', 'nama_satker')
        ->where('kd_klpd', 'D264')
        ->orderBy('nama_satker')
        ->get();

    // Hitung belanja_pengadaan dan realisasi per satker
    $data = $satkers->map(function ($satker) use ($tahunList) {
        // Ambil belanja_pengadaan dari struktur_anggarans
        if (count($tahunList) > 1) {
            $belanja = DB::table('struktur_anggarans')
                ->where('kd_satker', $satker->kd_satker)
                ->whereIn('tahun_anggaran', $tahunList)
                ->sum('belanja_pengadaan');
        } else {
            $belanja = DB::table('struktur_anggarans')
                ->where('kd_satker', $satker->kd_satker)
                ->where('tahun_anggaran', $tahunList[0])
                ->value('belanja_pengadaan') ?? 0;
        }

        // Tender
        $nilaiTender = DB::table('tender_pengumuman_data')
            ->where('kd_satker', $satker->kd_satker)
            ->whereIn('tahun', $tahunList)
            ->whereNotNull('pagu')
            ->where('pagu', '>', 0)
            ->sum('pagu');

        // Non Tender
        $nilaiNonTender = DB::table('non_tender_pengumuman')
            ->where('kd_satker', $satker->kd_satker)
            ->whereIn('tahun_anggaran', $tahunList)
            ->whereNotNull('pagu')
            ->where('pagu', '>', 0)
            ->sum('pagu');

        // E-Katalog V5
        $nilaiV5 = DB::table('ekatalog_v5_pakets as v5')
            ->join('struktur_anggarans as s', DB::raw('CAST(v5.satker_id AS TEXT)'), '=', 's.kd_satker')
            ->where('s.kd_satker', $satker->kd_satker)
            ->whereIn('v5.tahun_anggaran', $tahunList)
            ->whereNotNull('v5.total_harga')
            ->where('v5.total_harga', '>', 0)
            ->sum('v5.total_harga');

        // E-Katalog V6
        $nilaiV6 = DB::table('ekatalog_v6_pakets')
            ->where('nama_satker', $satker->nama_satker)
            ->whereIn('tahun_anggaran', $tahunList)
            ->whereNotNull('total_harga')
            ->where('total_harga', '>', 0)
            ->sum('total_harga');

        // Toko Daring
        $nilaiToko = DB::table('toko_darings')
            ->where('kd_satker', $satker->kd_satker)
            ->whereIn('tahun', $tahunList)
            ->whereNotNull('valuasi')
            ->where('valuasi', '>', 0)
            ->sum('valuasi');

        // Total
        $totalTransaksi = $nilaiTender + $nilaiNonTender + $nilaiV5 + $nilaiV6 + $nilaiToko;

        // Hitung persen
        $presentaseRealisasi = $belanja > 0
            ? round(($totalTransaksi / $belanja) * 100, 2)
            : 0;

        return (object)[
            'nama_satker' => $satker->nama_satker,
            'belanja_pengadaan' => $belanja,
            'total_transaksi' => $totalTransaksi,
            'presentase_realisasi' => $presentaseRealisasi,
        ];
    });

    // Dropdown list dari model Satker (bukan dari struktur_anggaran)
    $listSatker = $satkers->pluck('nama_satker')->unique()->sort()->values();

    // Filter satker jika dipilih
    $data = $data->filter(function ($item) use ($request) {
        if ($request->filled('satker')) {
            return $item->nama_satker === $request->get('satker');
        }
        return true;
    });

    // Return ke view
    $viewData = [
        'data' => $data,
        'tahun' => implode(', ', $tahunList),
        'listSatker' => $listSatker,
    ];

    return view(auth()->user()->role === 'admin'
        ? 'monitoring.presentase-realisasi'
        : 'users.monitoring.presentase-realisasi', $viewData);
}


public function exportRealisasiToPDF(Request $request)
{
    $tahunList = $request->get('tahun', [2024, 2025]);
    $satkerFilter = $request->get('satker');
    $mode = $request->get('mode', 'V'); // ✅ Ambil mode dari request, default 'V' (View)

    if (!is_array($tahunList)) {
        $tahunList = [$tahunList];
    }

    $tahunAkhir = end($tahunList);

    $struktur = DB::table('struktur_anggarans as s')
        ->select('s.nama_satker', 's.belanja_pengadaan', 's.kd_satker')
        ->whereIn('s.tahun_anggaran', $tahunList)
        ->where('s.kd_klpd', 'D264')
        ->get();

    // Map per Satker
    $data = $struktur->map(function ($item) use ($tahunAkhir) {
        $nilaiTender = DB::table('tender_pengumuman_data')
            ->where('kd_satker', $item->kd_satker)
            ->where('tahun', $tahunAkhir)
            ->sum('pagu');

        $nilaiNonTender = DB::table('non_tender_pengumuman')
            ->where('kd_satker', $item->kd_satker)
            ->where('tahun_anggaran', $tahunAkhir)
            ->sum('pagu');

        $nilaiV5 = DB::table('ekatalog_v5_pakets as v5')
            ->join('struktur_anggarans as s', DB::raw('CAST(v5.satker_id AS TEXT)'), '=', 's.kd_satker')
            ->where('s.kd_satker', $item->kd_satker)
            ->where('v5.tahun_anggaran', $tahunAkhir)
            ->sum('v5.total_harga');

        $nilaiV6 = DB::table('ekatalog_v6_pakets')
            ->where('nama_satker', $item->nama_satker)
            ->where('tahun_anggaran', $tahunAkhir)
            ->sum('total_harga');

        $nilaiToko = DB::table('toko_darings')
            ->where('kd_satker', $item->kd_satker)
            ->where('tahun', $tahunAkhir)
            ->sum('valuasi');

        $totalTransaksi = $nilaiTender + $nilaiNonTender + $nilaiV5 + $nilaiV6 + $nilaiToko;

        return (object)[
            'nama_satker' => $item->nama_satker,
            'belanja_pengadaan' => $item->belanja_pengadaan,
            'total_transaksi' => $totalTransaksi,
            'presentase_realisasi' => $item->belanja_pengadaan > 0
                ? round(($totalTransaksi / $item->belanja_pengadaan) * 100, 2)
                : 0,
        ];
    });

    // Filter by nama_satker (jika dipilih)
    if ($satkerFilter) {
        $data = $data->filter(function ($item) use ($satkerFilter) {
            return $item->nama_satker === $satkerFilter;
        });
    }

    $pdf = Pdf::loadView('exports.realisasi-presentase', [
        'data' => $data,
        'tahun' => implode(', ', $tahunList),
    ])->setPaper('A4', 'landscape');

    $fileName = 'Realisasi_Pengadaan_' . implode('_', $tahunList) . '.pdf';

    return $mode === 'D'
        ? $pdf->download($fileName) // Jika mode download
        : $pdf->stream($fileName);  // Jika mode view
}

    
public function rekapRealisasi(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $filterSatker = $request->get('satker');
  

    // Daftar satker filter
    $listSatker = DB::table('struktur_anggarans')
        ->where('tahun_anggaran', $tahun)
        ->where('kd_klpd', 'D264')
        ->pluck('nama_satker')
        ->unique()
        ->sort()
        ->values();

    // Ambil semua satker yang aktif di struktur_anggarans
    $satkerList = DB::table('struktur_anggarans')
        ->select('kd_satker', 'nama_satker')
        ->where('tahun_anggaran', $tahun)
        ->where('kd_klpd', 'D264')
        ->when($filterSatker, fn($q) => $q->where('nama_satker', $filterSatker))
        ->get();

    $data = $satkerList->map(function ($satker) use ($tahun) {
        $namaSatker = $satker->nama_satker;
        $kdSatker = $satker->kd_satker;

        return $this->getSatkerRealisasi($tahun, $kdSatker, $namaSatker);
    });

    // Pilih view berdasarkan role user
    if (auth()->user()->role === 'admin') {
        return view('monitoring.rekap-realisasi', [
            'data' => $data,
            'tahun' => $tahun,
            'listSatker' => $listSatker,
        ]);
    } else {
        return view('users.monitoring.rekap-realisasi', [
            'data' => $data,
            'tahun' => $tahun,
            'listSatker' => $listSatker,
        ]);
    }
}


public function exportRekapRealisasiToPDF(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $mode = $request->get('mode', 'V');
    $satkerList = DB::table('struktur_anggarans')
        ->select('kd_satker', 'nama_satker')
        ->where('tahun_anggaran', $tahun)
        ->where('kd_klpd', 'D264')
        ->get();

    $data = $satkerList->map(function ($satker) use ($tahun) {
        return $this->getSatkerRealisasi($tahun, $satker->kd_satker, $satker->nama_satker);
    });

    $pdf = Pdf::loadView('exports.rekap-realisasi', compact('data', 'tahun'))
        ->setPaper('A4', 'landscape');

    $fileName = "rekap-realisasi-{$tahun}.pdf";

    return $mode === 'D'
        ? $pdf->download($fileName) // Jika download
        : $pdf->stream($fileName);  // Jika view
}

protected function getSatkerRealisasi($tahun, $kdSatker, $namaSatker)
{
    return [
        'nama_satker' => $namaSatker,
        'total_paket_tender' => DB::table('tender_selesai_data')->where('tahun', $tahun)->where('nama_satker', $namaSatker)->where('status_tender', 'Selesai')->count(),
        'total_nilai_tender' => DB::table('tender_selesai_nilai_data')->where('tahun', $tahun)->where('nama_satker', $namaSatker)->sum('nilai_kontrak'),

        'total_paket_nontender' => DB::table('non_tender_selesai')->where('tahun_anggaran', $tahun)->where('nama_satker', $namaSatker)->where('status_nontender', 'Selesai')->count(),
        'total_nilai_nontender' => DB::table('non_tender_selesai')->where('tahun_anggaran', $tahun)->where('nama_satker', $namaSatker)->sum('nilai_kontrak'),

        'total_paket_ekatalog' => DB::table('ekatalog_v5_pakets')->where('tahun_anggaran', $tahun)->where('satker_id', $kdSatker)->where('paket_status_str', 'Paket Selesai')->count()
            + DB::table('ekatalog_v6_pakets')->where('tahun_anggaran', $tahun)->where('nama_satker', $namaSatker)->where('status_pkt', 'COMPLETED')->count(),
        'total_nilai_ekatalog' => DB::table('ekatalog_v5_pakets')->where('tahun_anggaran', $tahun)->where('satker_id', $kdSatker)->where('paket_status_str', 'Paket Selesai')->sum('total_harga')
            + DB::table('ekatalog_v6_pakets')->where('tahun_anggaran', $tahun)->where('nama_satker', $namaSatker)->where('status_pkt', 'COMPLETED')->sum('total_harga'),

        'total_paket_tokodaring' => DB::table('toko_darings')->where('tahun', $tahun)->where('nama_satker', $namaSatker)->where('status_verif', 'verified')->count(),
        'total_nilai_tokodaring' => DB::table('toko_darings')->where('tahun', $tahun)->where('nama_satker', $namaSatker)->where('status_verif', 'verified')->sum('valuasi'),

        'total_paket_swakelola' => DB::table('swakelola_realisasi')->where('tahun_anggaran', $tahun)->where('kd_satker', $kdSatker)->count(),
        'total_nilai_swakelola' => DB::table('swakelola_realisasi')->where('tahun_anggaran', $tahun)->where('kd_satker', $kdSatker)->sum('nilai_realisasi'),
    ];
}

public function rekapRealisasiBerlangsung(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $filterSatker = $request->get('satker');

    // List semua Satker untuk filter dropdown
    $listSatker = DB::table('struktur_anggarans')
        ->where('tahun_anggaran', $tahun)
        ->where('kd_klpd', 'D264')
        ->pluck('nama_satker')
        ->unique()
        ->sort()
        ->values();

    // Daftar satker utama
    $satkerList = DB::table('struktur_anggarans')
        ->select('kd_satker', 'nama_satker')
        ->where('tahun_anggaran', $tahun)
        ->where('kd_klpd', 'D264')
        ->when($filterSatker, fn($q) => $q->where('nama_satker', $filterSatker))
        ->get();

    // Loop per Satker
    $data = $satkerList->map(function ($satker) use ($tahun) {
        $namaSatker = $satker->nama_satker;
        $kdSatker = $satker->kd_satker;

        // Hitung data tender, non-tender, e-katalog, dan toko daring (seperti yang sudah kamu tulis)
        $totalPaketTender = DB::table('tender_pengumuman_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_tender', 'Berlangsung')
            ->count();

        $totalNilaiTender = DB::table('tender_pengumuman_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_tender', 'Berlangsung')
            ->sum('pagu');

        $totalPaketNonTender = DB::table('non_tender_pengumuman')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_nontender', 'Berlangsung')
            ->count();

        $totalNilaiNonTender = DB::table('non_tender_pengumuman')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_nontender', 'Berlangsung')
            ->sum('pagu');

        $totalPaketEkatalogV5 = DB::table('ekatalog_v5_pakets')
            ->where('tahun_anggaran', $tahun)
            ->where('satker_id', $kdSatker)
            ->where('paket_status_str', 'Paket Proses')
            ->count();

        $totalNilaiEkatalogV5 = DB::table('ekatalog_v5_pakets')
            ->where('tahun_anggaran', $tahun)
            ->where('satker_id', $kdSatker)
            ->where('paket_status_str', 'Paket Proses')
            ->sum('total_harga');

        $totalPaketEkatalogV6 = DB::table('ekatalog_v6_pakets')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_pkt', 'PROGRESS')
            ->count();

        $totalNilaiEkatalogV6 = DB::table('ekatalog_v6_pakets')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_pkt', 'PROGRESS')
            ->sum('total_harga');

        $totalPaketTokoDaring = DB::table('toko_darings')
            ->where('tahun', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_verif', 'unverified')
            ->count();

        $totalNilaiTokoDaring = DB::table('toko_darings')
            ->where('tahun', $tahun)
            ->where('nama_satker', $namaSatker)
            ->where('status_verif', 'unverified')
            ->sum('valuasi');

        return [
            'nama_satker' => $namaSatker,
            'total_paket_tender' => $totalPaketTender,
            'total_nilai_tender' => $totalNilaiTender,
            'total_paket_nontender' => $totalPaketNonTender,
            'total_nilai_nontender' => $totalNilaiNonTender,
            'total_paket_ekatalog' => $totalPaketEkatalogV5 + $totalPaketEkatalogV6,
            'total_nilai_ekatalog' => $totalNilaiEkatalogV5 + $totalNilaiEkatalogV6,
            'total_paket_tokodaring' => $totalPaketTokoDaring,
            'total_nilai_tokodaring' => $totalNilaiTokoDaring,
        ];
    });

    // Pilih view berdasarkan role user
    if (auth()->user()->role === 'admin') {
        return view('monitoring.rekap-realisasi-berlangsung', [
            'data' => $data,
            'tahun' => $tahun,
            'listSatker' => $listSatker,
        ]);
    } else {
        return view('users.monitoring.rekap-realisasi-berlangsung', [
            'data' => $data,
            'tahun' => $tahun,
            'listSatker' => $listSatker,
        ]);
    }
}

public function exportRealisasiBerlangsungPdf(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $mode = $request->get('mode', 'V'); // ✅ Tambahkan ini untuk menangkap mode

    // Ambil data realisasi berlangsung
    $data = $this->getRealisasiBerlangsungData($tahun);

    // Buat PDF
    $pdf = Pdf::loadView('exports.rekap-realisasi-berlangsung', compact('data', 'tahun'))
        ->setPaper('A4', 'landscape');

    $fileName = "rekap-realisasi-berlangsung-{$tahun}.pdf";

    // Return sesuai mode
    return $mode === 'D'
        ? $pdf->download($fileName) // Jika download
        : $pdf->stream($fileName);  // Jika view
}

    protected function getRealisasiBerlangsungData($tahun)
    {
        $satkerList = DB::table('struktur_anggarans')
            ->select('kd_satker', 'nama_satker')
            ->where('tahun_anggaran', $tahun)
            ->where('kd_klpd', 'D264')
            ->get();
    
        return $satkerList->map(function ($satker) use ($tahun) {
            $namaSatker = $satker->nama_satker;
            $kdSatker = $satker->kd_satker;
    
            // Tender Pengumuman
            $totalPaketTender = DB::table('tender_pengumuman_data')
                ->where('tahun', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_tender', 'Berlangsung')
                ->count();
    
            $totalNilaiTender = DB::table('tender_pengumuman_data')
                ->where('tahun', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_tender', 'Berlangsung')
                ->sum('pagu');
    
            // Non-Tender Pengumuman
            $totalPaketNonTender = DB::table('non_tender_pengumuman')
                ->where('tahun_anggaran', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_nontender', 'Berlangsung')
                ->count();
    
            $totalNilaiNonTender = DB::table('non_tender_pengumuman')
                ->where('tahun_anggaran', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_nontender', 'Berlangsung')
                ->sum('pagu');
    
            // E-Katalog V5
            $totalPaketEkatalogV5 = DB::table('ekatalog_v5_pakets')
                ->where('tahun_anggaran', $tahun)
                ->where('satker_id', $kdSatker)
                ->where('paket_status_str', 'Paket Proses')
                ->count();
    
            $totalNilaiEkatalogV5 = DB::table('ekatalog_v5_pakets')
                ->where('tahun_anggaran', $tahun)
                ->where('satker_id', $kdSatker)
                ->where('paket_status_str', 'Paket Proses')
                ->sum('total_harga');
    
            // E-Katalog V6
            $totalPaketEkatalogV6 = DB::table('ekatalog_v6_pakets')
                ->where('tahun_anggaran', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_pkt', 'PROGRESS')
                ->count();
    
            $totalNilaiEkatalogV6 = DB::table('ekatalog_v6_pakets')
                ->where('tahun_anggaran', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_pkt', 'PROGRESS')
                ->sum('total_harga');
    
            // Toko Daring
            $totalPaketTokoDaring = DB::table('toko_darings')
                ->where('tahun', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_verif', 'unverified')
                ->count();
    
            $totalNilaiTokoDaring = DB::table('toko_darings')
                ->where('tahun', $tahun)
                ->where('nama_satker', $namaSatker)
                ->where('status_verif', 'unverified')
                ->sum('valuasi');
    
            return [
                'nama_satker' => $namaSatker,
                'total_paket_tender' => $totalPaketTender,
                'total_nilai_tender' => $totalNilaiTender,
                'total_paket_nontender' => $totalPaketNonTender,
                'total_nilai_nontender' => $totalNilaiNonTender,
                'total_paket_ekatalog' => $totalPaketEkatalogV5 + $totalPaketEkatalogV6,
                'total_nilai_ekatalog' => $totalNilaiEkatalogV5 + $totalNilaiEkatalogV6,
                'total_paket_tokodaring' => $totalPaketTokoDaring,
                'total_nilai_tokodaring' => $totalNilaiTokoDaring,
            ];
        });
    }
    public function kontrak(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $filterSatker = $request->get('nama_satker', '');
    
        // Daftar tahun
        $tahunList = range(date('Y') - 1, date('Y'));
    
        // Ambil semua Satker dari struktur anggaran & tender
        $allSatker = DB::table('struktur_anggarans')
            ->select('nama_satker')
            ->where('tahun_anggaran', $tahun)
            ->union(
                DB::table('tender_selesai_nilai_data')
                    ->select('nama_satker')
                    ->where('tahun', $tahun)
            )
            ->orderBy('nama_satker')
            ->pluck('nama_satker')
            ->unique()
            ->toArray();
    
        // Jika filter satker kosong, pakai semua Satker
        $targetSatker = empty($filterSatker) ? $allSatker : [$filterSatker];
    
        // Hitung jumlah paket selesai tender
        $tenderCount = DB::table('tender_selesai_nilai_data')
            ->select('nama_satker', DB::raw('count(*) as total_paket'))
            ->where('tahun', $tahun)
            ->whereIn('nama_satker', $targetSatker)
            ->groupBy('nama_satker')
            ->pluck('total_paket', 'nama_satker');
    
        // Hitung total pagu per satker
        $paguPerSatker = DB::table('tender_selesai_nilai_data')
            ->select('nama_satker', DB::raw('sum(pagu) as total_pagu'))
            ->where('tahun', $tahun)
            ->whereIn('nama_satker', $targetSatker)
            ->groupBy('nama_satker')
            ->pluck('total_pagu', 'nama_satker');
    
        // Hitung kontrak (bapbast) per satker
        $bapbastPerSatker = DB::table('bapbast_data')
            ->select('nama_satker', DB::raw('count(*) as total_kontrak'))
            ->where('tahun', $tahun)
            ->whereIn('nama_satker', $targetSatker)
            ->groupBy('nama_satker')
            ->pluck('total_kontrak', 'nama_satker');
    
        // Bangun array hasil
        $result = [];
        foreach ($targetSatker as $satker) {
            $paket = $tenderCount[$satker] ?? 0;
            $pagu = $paguPerSatker[$satker] ?? 0;
            $kontrak = $bapbastPerSatker[$satker] ?? 0;
    
            $result[] = [
                'nama_satker' => $satker,
                'total_paket' => $paket,
                'total_pagu' => $pagu,
                'total_kontrak' => $paket - $kontrak,
            ];
        }
    
        // Total keseluruhan
        $totals = [
            'total_paket' => array_sum(array_column($result, 'total_paket')),
            'total_pagu' => array_sum(array_column($result, 'total_pagu')),
            'total_kontrak' => array_sum(array_column($result, 'total_kontrak')),
        ];
    
        // Total global
        $totalTenderSelesai = DB::table('tender_selesai_nilai_data')->where('tahun', $tahun)->count();
        $totalBapbast = DB::table('bapbast_data')->where('tahun', $tahun)->count();
        $selisih = $totalTenderSelesai - $totalBapbast;
    
        // Tentukan view berdasarkan role
        $view = auth()->user()->role === 'admin'
            ? 'monitoring.kontrak'
            : 'users.monitoring.kontrak';
    
        return view($view, [
            'data' => $result,
            'tahun' => $tahun,
            'tahunList' => $tahunList,
            'namaSatkerList' => $allSatker,
            'filterSatker' => $filterSatker,
            'totals' => $totals,
            'totalTenderSelesai' => $totalTenderSelesai,
            'totalBapbast' => $totalBapbast,
            'selisih' => $selisih,
        ]);
    }
    
    public function kontrakDetail($satker, Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $satker = urldecode($satker); // decode nama satker dari URL
    
        // Cari tender selesai untuk satker dan tahun tertentu
        $tenderSelesai = DB::table('tender_selesai_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $satker)
            ->pluck('kd_tender')
            ->toArray();
    
        if (empty($tenderSelesai)) {
            $data = collect();
            $totalPagu = 0;
        } else {
            // Cari kontrak yang sudah ada untuk tender tersebut
            $kontrak = DB::table('kontrak_data')
                ->where('tahun', $tahun)
                ->where('nama_satker', $satker)
                ->whereIn('kd_tender', $tenderSelesai)
                ->pluck('kd_tender')
                ->toArray();
    
            // Ambil data tender pengumuman yang belum ada kontraknya
            $data = DB::table('tender_pengumuman_data')
                ->select('kd_tender', 'nama_paket', 'pagu')
                ->where('tahun', $tahun)
                ->where('nama_satker', $satker)
                ->whereIn('kd_tender', $tenderSelesai)
                ->whereNotIn('kd_tender', $kontrak)
                ->orderBy('nama_paket')
                ->get();
    
            // Hitung total pagu
            $totalPagu = $data->sum('pagu');
        }
    
        // Tampilkan view sesuai role user
        if (auth()->user()->role === 'admin') {
            return view('monitoring.kontrak-detail', compact('data', 'satker', 'tahun', 'totalPagu'));
        } else {
            return view('users.monitoring.kontrak-detail', compact('data', 'satker', 'tahun', 'totalPagu'));
        }
    }
    

    public function exportKontrakDetailPdf($satker, Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $satker = urldecode($satker);
        $mode = $request->get('mode', 'V'); // Tambahkan mode
    
        // Cari tender selesai untuk satker dan tahun tertentu
        $tenderSelesai = DB::table('tender_selesai_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $satker)
            ->pluck('kd_tender')
            ->toArray();
    
        if (empty($tenderSelesai)) {
            $data = collect();
            $totalPagu = 0;
        } else {
            $kontrak = DB::table('kontrak_data')
                ->where('tahun', $tahun)
                ->where('nama_satker', $satker)
                ->whereIn('kd_tender', $tenderSelesai)
                ->pluck('kd_tender')
                ->toArray();
    
            $data = DB::table('tender_pengumuman_data')
                ->select('kd_tender', 'nama_paket', 'pagu')
                ->where('tahun', $tahun)
                ->where('nama_satker', $satker)
                ->whereIn('kd_tender', $tenderSelesai)
                ->whereNotIn('kd_tender', $kontrak)
                ->orderBy('nama_paket')
                ->get();
    
            $totalPagu = $data->sum('pagu');
        }
    
        $pdf = Pdf::loadView('monitoring.kontrak-detail-pdf', compact('data', 'satker', 'tahun', 'totalPagu'))
            ->setPaper('A4', 'landscape');
    
        $fileName = "detail_kontrak_{$satker}_{$tahun}.pdf";
    
        return $mode === 'D'
            ? $pdf->download($fileName)
            : $pdf->stream($fileName);
    }
    
    
public function kontrakNonTender(Request $request)
{
    $tahun = $request->get('tahun_anggaran', date('Y'));
    $filterSatker = $request->get('nama_satker', '');

    $tahunList = range(date('Y') - 1, date('Y'));

    // Ambil semua satker (struktur anggaran + non tender selesai)
    $allSatker = DB::table('struktur_anggarans')
        ->select('nama_satker')
        ->where('tahun_anggaran', $tahun)
        ->union(
            DB::table('non_tender_selesai')
                ->select('nama_satker')
                ->where('tahun_anggaran', $tahun)
        )
        ->orderBy('nama_satker')
        ->pluck('nama_satker')
        ->unique()
        ->toArray();

    // Kalau filter kosong, pakai semua satker
    $targetSatker = empty($filterSatker) ? $allSatker : [$filterSatker];

    // Hitung data per satker
    $totalNonTender = DB::table('non_tender_selesai')
        ->select('nama_satker', DB::raw('count(*) as total_non_tender_selesai'))
        ->where('tahun_anggaran', $tahun)
        ->whereIn('nama_satker', $targetSatker)
        ->groupBy('nama_satker')
        ->pluck('total_non_tender_selesai', 'nama_satker');

    $paguPerSatker = DB::table('non_tender_pengumuman')
        ->select('nama_satker', DB::raw('sum(pagu) as total_pagu'))
        ->where('tahun_anggaran', $tahun)
        ->whereIn('nama_satker', $targetSatker)
        ->groupBy('nama_satker')
        ->pluck('total_pagu', 'nama_satker');

    $totalKontrak = DB::table('non_tender_bapbast')
        ->select('nama_satker', DB::raw('count(*) as total_kontrak'))
        ->where('tahun_anggaran', $tahun)
        ->whereIn('nama_satker', $targetSatker)
        ->groupBy('nama_satker')
        ->pluck('total_kontrak', 'nama_satker');

    // Bangun hasil per satker
    $result = [];
    foreach ($targetSatker as $satker) {
        $totalSelesai = $totalNonTender[$satker] ?? 0;
        $totalPagu = $paguPerSatker[$satker] ?? 0;
        $totalKontr = $totalKontrak[$satker] ?? 0;

        $result[] = [
            'nama_satker' => $satker,
            'total_non_tender_selesai' => $totalSelesai,
            'total_pagu' => $totalPagu,
            'kontrak_belum_input' => $totalSelesai - $totalKontr,
        ];
    }

    // Hitung total keseluruhan
    $totals = [
        'total_non_tender_selesai' => array_sum(array_column($result, 'total_non_tender_selesai')),
        'total_pagu' => array_sum(array_column($result, 'total_pagu')),
        'kontrak_belum_input' => array_sum(array_column($result, 'kontrak_belum_input')),
    ];

    // Pilih view sesuai role
    $view = auth()->user()->role === 'admin'
        ? 'monitoring.non-tender'
        : 'users.monitoring.non-tender';

    return view($view, [
        'data' => $result,
        'tahun' => $tahun,
        'tahunList' => $tahunList,
        'satkerList' => $allSatker,
        'filterSatker' => $filterSatker,
        'totals' => $totals,
    ]);
}

public function kontrakNonTenderDetail($satker, Request $request)
{
    $tahun = $request->get('tahun_anggaran', date('Y'));
    $satker = urldecode($satker);

    // Ambil semua kd_nontender yang selesai
    $nonTenderSelesai = DB::table('non_tender_selesai')
        ->where('tahun_anggaran', $tahun)
        ->where('nama_satker', $satker)
        ->pluck('kd_nontender')
        ->toArray();

    if (empty($nonTenderSelesai)) {
        $data = collect();
        $totalPagu = 0;
    } else {
        // Cari kd_nontender yang sudah dikontrak
        $kontrak = DB::table('kontrak_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $satker)
            ->whereIn('kd_tender', $nonTenderSelesai)
            ->pluck('kd_tender')
            ->toArray();

        // Ambil data non-tender pengumuman yang belum dikontrak
        $data = DB::table('non_tender_pengumuman')
            ->select('kd_nontender', 'nama_paket', 'pagu')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $satker)
            ->whereIn('kd_nontender', $nonTenderSelesai)
            ->whereNotIn('kd_nontender', $kontrak)
            ->orderBy('nama_paket')
            ->get();

        // Hitung total pagu
        $totalPagu = $data->sum('pagu');
    }

    // Tampilkan view sesuai role user
    if (auth()->user()->role === 'admin') {
        return view('monitoring.non-tender-detail', compact('data', 'satker', 'tahun', 'totalPagu'));
    } else {
        return view('users.monitoring.non-tender-detail', compact('data', 'satker', 'tahun', 'totalPagu'));
    }
}
public function exportNonTenderDetailPdf($satker, Request $request)
{
    $tahun = $request->get('tahun_anggaran', date('Y'));
    $satker = urldecode($satker);

    // Ambil semua kd_nontender yang sudah selesai
    $nonTenderSelesai = DB::table('non_tender_selesai')
        ->where('tahun_anggaran', $tahun)
        ->where('nama_satker', $satker)
        ->pluck('kd_nontender')
        ->toArray();

    if (empty($nonTenderSelesai)) {
        $data = collect();
        $totalPagu = 0;
    } else {
        // Ambil semua kd_tender (kontrak) yang sudah ada
        $kontrak = DB::table('kontrak_data')
            ->where('tahun', $tahun)
            ->where('nama_satker', $satker)
            ->whereIn('kd_tender', $nonTenderSelesai)
            ->pluck('kd_tender')
            ->toArray();

        // Ambil semua data non-tender pengumuman yang belum dikontrak
        $data = DB::table('non_tender_pengumuman')
            ->select('kd_nontender', 'nama_paket', 'pagu')
            ->where('tahun_anggaran', $tahun)
            ->where('nama_satker', $satker)
            ->whereIn('kd_nontender', $nonTenderSelesai)
            ->whereNotIn('kd_nontender', $kontrak)
            ->orderBy('nama_paket')
            ->get();

        // Hitung total pagu
        $totalPagu = $data->sum('pagu');
    }

    // Generate PDF menggunakan view yang sesuai
    $pdf = Pdf::loadView('monitoring.non-tender-detail-pdf', compact('data', 'satker', 'tahun', 'totalPagu'));

    return $pdf->stream("detail_non_tender_{$satker}_{$tahun}.pdf");
}

public function summaryRealisasi(Request $request)
{
    // Ambil data satker + transaksi pakai presentaseRealisasi yang sudah ada
    $data = $this->presentaseRealisasi($request);

    // Hitung total belanja dan transaksi
    $totalBelanja = $data->sum('belanja_pengadaan');
    $totalTransaksi = $data->sum('total_transaksi');
    $avgPresentase = $data->count() > 0 ? round($data->avg('presentase_realisasi'), 2) : 0;

    return [
        'total_belanja' => $totalBelanja,
        'total_transaksi' => $totalTransaksi,
        'avg_presentase' => $avgPresentase,
    ];
}

}    
    



    
    

 

