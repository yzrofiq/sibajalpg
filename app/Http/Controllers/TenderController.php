<?php

namespace App\Http\Controllers;

use App\Models\TenderPengumumanData;
use App\Models\NonTenderPengumuman;
use App\Models\Tender;
use App\Models\StrukturAnggaran;
use Carbon\Carbon;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Satker; 


class TenderController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        // 1. Ambil daftar Satker valid berdasarkan nama_satker
        $satkers = Satker::select('nama_satker')
            ->where('kd_klpd', 'D264')
            ->whereNotNull('nama_satker')
            ->orderBy('nama_satker')
            ->get();
    
        // Gunakan nama_satker untuk whereIn ke pengumuman
        $validSatkerNames = $satkers->pluck('nama_satker')->toArray();
    
        // 2. Ambil parameter request
        $code = $this->request->code;
        $name = $this->request->name;
        $satkerCode = $this->request->satker; // sekarang berupa nama_satker
        $categoryParam = $this->request->category;
        $perPage = $this->request->per_page ?: 50;
        $year = $this->request->year ?: date('Y');
    
        // 3. Query data utama (pakai nama_satker)
        $query = TenderPengumumanData::query()
            ->leftJoin('tender_selesai_data', 'tender_pengumuman_data.kd_tender', '=', 'tender_selesai_data.kd_tender')
            ->leftJoin('kontrak_data', 'tender_pengumuman_data.kd_tender', '=', 'kontrak_data.kd_tender')
            ->where('tender_pengumuman_data.tahun', $year)
            ->where('tender_pengumuman_data.kd_klpd', 'D264')
            ->whereIn('tender_pengumuman_data.status_tender', ['Selesai', 'Berlangsung'])
            ->whereIn('tender_pengumuman_data.nama_satker', $validSatkerNames);
    
        // 4. Filter tambahan
        if ($code) {
            $query->where('tender_pengumuman_data.kd_tender', 'like', "%$code%");
        }
    
        if ($name) {
            $query->whereRaw('LOWER(tender_pengumuman_data.nama_paket) LIKE ?', ['%' . strtolower($name) . '%']);
        }
    
        if (!empty($satkerCode) && $satkerCode !== 'ALL') {
            $query->where('tender_pengumuman_data.nama_satker', $satkerCode);
        }
    
        if ($categoryParam) {
            $query->where('tender_pengumuman_data.jenis_pengadaan', 'like', "%$categoryParam%");
        }
    
        // 5. Ambil data paginasi
        $data = $query
            ->select(
                'tender_pengumuman_data.*',
                DB::raw('COALESCE(kontrak_data.nilai_pdn_kontrak, tender_selesai_data.nilai_pdn_kontrak, 0) as nilai_pdn_kontrak'),
                DB::raw('COALESCE(kontrak_data.nilai_umk_kontrak, tender_selesai_data.nilai_umk_kontrak, 0) as nilai_umk_kontrak')
            )
            ->orderByRaw("CASE WHEN tender_pengumuman_data.status_tender = 'Berlangsung' THEN 0 WHEN tender_pengumuman_data.status_tender = 'Selesai' THEN 1 ELSE 2 END")
            ->orderBy('tender_pengumuman_data.nama_paket', 'asc')
            ->paginate($perPage)
            ->appends(compact('code', 'name', 'satkerCode', 'year', 'categoryParam', 'perPage'));
    
        // 6. Kategori yang tersedia
        $categories = TenderPengumumanData::select('jenis_pengadaan')
            ->distinct()
            ->whereNotNull('jenis_pengadaan')
            ->where('tahun', $year)
            ->where('kd_klpd', 'D264')
            ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
            ->whereIn('nama_satker', $validSatkerNames)
            ->pluck('jenis_pengadaan')
            ->toArray();
    
            foreach ($categories as $category) {
                $catQuery = TenderPengumumanData::where('tahun', $year)
                    ->where('kd_klpd', 'D264')
                    ->where('jenis_pengadaan', $category)
                    ->whereIn('status_tender', ['Selesai', 'Berlangsung']);
            
                // Pastikan gunakan filter berdasarkan nama_satker (bukan kd_satker)
                if (!empty($satkerCode) && $satkerCode !== 'ALL') {
                    $catQuery->where('nama_satker', $satkerCode);
                }
            
                if ($code) $catQuery->where('kd_tender', 'like', "%$code%");
                if ($name) $catQuery->whereRaw('LOWER(nama_paket) LIKE ?', ['%' . strtolower($name) . '%']);
            
                $categoriesCount[$category] = $catQuery->count();
            }
            
    
        // 8. Tahun unik dari DB
        $years = TenderPengumumanData::select('tahun')->distinct()->pluck('tahun')->toArray();
    
        // 9. Statistik
        $total = $query->count();
        $totalFull = TenderPengumumanData::where('tahun', $year)
            ->where('kd_klpd', 'D264')
            ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
            ->count();
    
        // 10. View dan role
        $url = url()->full();
        if (!str_contains($url, "?")) $url .= "?";
        $view = auth()->user()->role_id == 1 ? 'tender.index-lte' : 'users.tender.index-lte';
    
        // 11. Untuk indikator tab
        $tenderCount = TenderPengumumanData::where('kd_klpd', 'D264')
            ->when($year, fn($q) => $q->where('tahun', $year))
            ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
            ->count();
    
        $nonTenderCount = \App\Models\NonTenderPengumuman::where('kd_klpd', 'D264')
            ->when($year, fn($q) => $q->where('tahun_anggaran', $year))
            ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
            ->count();
    
        return view($view, compact(
            'satkers', 'years', 'data', 'total', 'totalFull',
            'code', 'name', 'year', 'satkerCode', 'categories', 'categoriesCount', 'url', 'categoryParam',
            'tenderCount', 'nonTenderCount'
        ));
    }
    
    public function show($code)
    {
        $url = $this->request->from ?: route('tender.list');
        $data = Tender::where('kd_tender', 'like', $code)->first();
        if (!$data) return redirect(route('tender.list'));

        $view = auth()->user()->role_id == 1 ? 'tender.show-lte' : 'users.tender.show-lte';

        return view($view, compact('data', 'url', 'data'));
    }

        public function realization(Request $request)
        {
            $year = $request->get('year', date('Y'));
            $month = $request->get('month', 'ALL');
            $day = $request->get('day', 'ALL');
    
            // Ambil daftar tahun dari dua tabel, gabungkan, dan urutkan
            $dbYearsStruktur = DB::table('struktur_anggarans')
                ->where('kd_klpd', 'D264')
                ->distinct()
                ->pluck('tahun_anggaran')
                ->toArray();
    
            $dbYearsTender = DB::table('tender_pengumuman_data')
                ->distinct()
                ->pluck('tahun')
                ->toArray();
    
            $years = array_unique(array_merge($dbYearsStruktur, $dbYearsTender, [date('Y')]));
            sort($years);
    
            $result = $this->getRealizationData($year, $month, $day);
    
            $view = auth()->user()->role_id == 1 ? 'tender.realization' : 'users.tender.realization';
    
            return view($view, [
                'data' => $result['data'],
                'total' => $result['total'],
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'years' => $years,
                'today' => now(),
            ]);
        }

        public function viewPdf(Request $request)
        {
            return $this->generateTenderPdf($request, 'view');
        }

        public function downloadPdf(Request $request)
        {
            return $this->generateTenderPdf($request, 'download');
        }

        private function generateTenderPdf(Request $request, $mode = 'view')
        {
            $endParam = $request->input('end');
        
            if ($endParam) {
                try {
                    $endDate = Carbon::createFromFormat('Y-m-d', $endParam)->endOfDay();
                    $year = $endDate->year;
                    $month = $endDate->month;
                    $day = $endDate->day;
                } catch (\Exception $e) {
                    $endDate = now()->endOfDay();
                    $year = $endDate->year;
                    $month = 'ALL';
                    $day = 'ALL';
                }
            } else {
                $year = $request->input('year') ?? now()->year;
                $month = $request->input('month') ?? 'ALL';
                $day = $request->input('day') ?? 'ALL';
        
                if ($month !== 'ALL' && $day !== 'ALL') {
                    $endDate = Carbon::create($year, $month, $day)->endOfDay();
                } elseif ($month !== 'ALL') {
                    $endDate = Carbon::create($year, $month)->endOfMonth()->endOfDay();
                } else {
                    $endDate = now()->endOfDay();
                }
            }
        
            $startDate = Carbon::create($year, 1, 1)->startOfDay();
        
            // Ambil data dari DB
            $raw = DB::table('tender_pengumuman_data as p')
                ->leftJoin('kontrak_data as c', 'p.kd_tender', '=', 'c.kd_tender')
                ->leftJoin('tender_selesai_data as s', 'p.kd_tender', '=', 's.kd_tender')
                ->select(
                    'p.nama_satker',
                    'p.jenis_pengadaan',
                    'p.pagu',
                    'p.hps',
                    DB::raw('COALESCE(c.nilai_kontrak, s.nilai_kontrak, 0) as nilai_terkontrak')
                )
                ->where('p.kd_klpd', 'D264')
                ->whereBetween('p.tgl_buat_paket', [$startDate, $endDate])
                ->whereIn('p.status_tender', ['Selesai', 'Berlangsung'])
                ->get();
        
            // Ambil daftar satker
            $satkers = StrukturAnggaran::where('kd_klpd', 'D264')
                ->where('tahun_anggaran', $year)
                ->pluck('nama_satker')
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        
            // Inisialisasi
            $data = [];
            $total = [
                'package_count' => 0,
                'constructions' => 0,
                'consultations' => 0,
                'goods' => 0,
                'services' => 0,
                'pagu' => 0,
                'hps' => 0,
                'nilai_terkontrak' => 0,
                'efficiency' => 0,
            ];
        
            foreach ($satkers as $nama) {
                $data[$nama] = array_merge(['name' => $nama], array_fill_keys(array_keys($total), 0));
            }
        
            foreach ($raw as $value) {
                $satker = $value->nama_satker;
                if (!isset($data[$satker])) continue;
        
                $data[$satker]['package_count']++;
                $total['package_count']++;
        
                $category = getCategory($value->jenis_pengadaan);
                if ($category && isset($data[$satker][$category])) {
                    $data[$satker][$category]++;
                    $total[$category]++;
                }
        
                $data[$satker]['pagu'] += $value->pagu;
                $total['pagu'] += $value->pagu;
        
                $data[$satker]['hps'] += $value->hps;
                $total['hps'] += $value->hps;
        
                $data[$satker]['nilai_terkontrak'] += $value->nilai_terkontrak;
                $total['nilai_terkontrak'] += $value->nilai_terkontrak;
        
                $efficiency = $value->pagu - $value->nilai_terkontrak;
                $data[$satker]['efficiency'] += $efficiency;
                $total['efficiency'] += $efficiency;
            }
        
            $finalData = array_values($data);
        
            // Judul laporan
            $title = "REALISASI PAKET TENDER\nOPD PROVINSI LAMPUNG\nTAHUN ANGGARAN {$year} S.D TANGGAL " . strtoupper($endDate->translatedFormat('d F Y'));
        
            // Generate PDF
            $html2pdf = new Html2Pdf('L', 'A3', 'en', true, 'UTF-8', [10, 10, 10, 10]);
            $view = auth()->user()->role_id == 1 ? 'tender.realization' : 'users.tender.realization';
        
            $render = view($view, [
                'data' => $finalData,
                'total' => $total,
                'title' => $title,
                'month' => $month,
                'day' => $day,
                'year' => $year,
            ]);
        
            $html2pdf->pdf->SetAutoPageBreak(true, 10);
            $html2pdf->writeHTML($render);
        
            return $mode === 'download'
                ? $html2pdf->output("realisasi_tender_{$year}.pdf", 'D')
                : $html2pdf->output();
        }
        
    }
    

