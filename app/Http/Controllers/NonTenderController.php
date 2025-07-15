<?php

namespace App\Http\Controllers;

use App\Models\TenderPengumumanData;
use App\Models\NonTenderPengumuman;
use App\Models\StrukturAnggaran;
use App\Models\Satker;
use App\Services\HelperService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spipu\Html2Pdf\Html2Pdf;

class NonTenderController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
{
    $year = $request->input('year', date('Y'));
    $satkers = Satker::select('kd_satker', 'nama_satker')
        ->where('kd_klpd', 'D264')
        ->orderBy('nama_satker')
        ->get();

    $categories = [];
    $categoriesCount = [];

    $code = $request->input('code');
    $name = $request->input('name');
    $satkerCode = $request->input('kd_satker'); // berisi NAMA satker
    $categoryParam = $request->input('category');
    $perPage = $request->input('per_page', 50);

    $query = NonTenderPengumuman::query()
        ->leftJoin('non_tender_selesai', 'non_tender_pengumuman.kd_nontender', '=', 'non_tender_selesai.kd_nontender')
        ->leftJoin('non_tender_contract', 'non_tender_pengumuman.kd_nontender', '=', 'non_tender_contract.kd_nontender')
        ->where('non_tender_pengumuman.tahun_anggaran', $year)
        ->where('non_tender_pengumuman.kd_klpd', 'D264')
        ->whereIn('non_tender_pengumuman.status_nontender', ['Selesai', 'Berlangsung']);

    if ($code) {
        $query->where('non_tender_pengumuman.kd_nontender', 'like', "%$code%");
    }

    if ($name) {
        $query->whereRaw('LOWER(non_tender_pengumuman.nama_paket) LIKE ?', ['%' . strtolower($name) . '%']);
    }

    if ($satkerCode) {
        $query->where('non_tender_pengumuman.nama_satker', $satkerCode);
    }

    if ($categoryParam) {
        $query->where('non_tender_pengumuman.jenis_pengadaan', 'like', "%$categoryParam%");
    }

    $data = $query
        ->select(
            'non_tender_pengumuman.*',
            DB::raw('COALESCE(non_tender_contract.nilai_pdn_kontrak, non_tender_selesai.nilai_pdn_kontrak, 0) as nilai_pdn_kontrak'),
            DB::raw('COALESCE(non_tender_contract.nilai_umk_kontrak, non_tender_selesai.nilai_umk_kontrak, 0) as nilai_umk_kontrak')
        )
        ->orderByRaw("
            CASE 
                WHEN non_tender_pengumuman.status_nontender = 'Berlangsung' THEN 0
                WHEN non_tender_pengumuman.status_nontender = 'Selesai' THEN 1
                ELSE 2
            END
        ")
        ->orderBy('non_tender_pengumuman.nama_paket', 'asc')
        ->paginate($perPage)
        ->appends([
            'code' => $code,
            'name' => $name,
            'kd_satker' => $satkerCode,
            'year' => $year,
            'category' => $categoryParam,
            'per_page' => $perPage,
        ]);

    $years = NonTenderPengumuman::select('tahun_anggaran')->distinct()->pluck('tahun_anggaran')->toArray();
    $categories = NonTenderPengumuman::select('jenis_pengadaan')->distinct()->pluck('jenis_pengadaan')->toArray();

    $total = $query->count();

    $totalFull = NonTenderPengumuman::where('tahun_anggaran', $year)
        ->where('kd_klpd', 'D264')
        ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
        ->count();

    foreach ($categories as $category) {
        $catQuery = NonTenderPengumuman::where('tahun_anggaran', $year)
            ->where('kd_klpd', 'D264')
            ->where('jenis_pengadaan', $category)
            ->whereIn('status_nontender', ['Selesai', 'Berlangsung']);

        if ($code) $catQuery->where('kd_nontender', 'like', "%$code%");
        if ($name) $catQuery->whereRaw('LOWER(nama_paket) LIKE ?', ['%' . strtolower($name) . '%']);
        if ($satkerCode) $catQuery->where('nama_satker', $satkerCode);

        $categoriesCount[$category] = $catQuery->count();
    }

    $url = url()->full();
    if (!str_contains($url, "?")) $url .= "?";

    $filterYear = $request->year;

    $tenderCount = TenderPengumumanData::where('kd_klpd', 'D264')
        ->when($filterYear, fn($q) => $q->where('tahun', $filterYear))
        ->whereIn('status_tender', ['Selesai', 'Berlangsung'])
        ->count();

    $nonTenderCount = NonTenderPengumuman::where('kd_klpd', 'D264')
        ->when($filterYear, fn($q) => $q->where('tahun_anggaran', $filterYear))
        ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
        ->count();

    $view = auth()->user()->role_id == 2
        ? 'users.non-tender.index-lte'
        : 'non-tender.index-lte';

    return view($view, compact(
        'satkers', 'years', 'data', 'total', 'totalFull',
        'code', 'name', 'year', 'satkerCode',
        'categories', 'categoriesCount', 'url', 'categoryParam',
        'tenderCount', 'nonTenderCount'
    ));
}

    public function show($code)
    {
        $url = $this->request->from ?? route('non-tender.list');
        $data = NonTenderPengumuman::where('kd_nontender', 'like', $code)->first();

        if (!$data) {
            return redirect(route('front'));
        }

        $data->pagu = HelperService::moneyFormat($data->pagu);

        $view = auth()->user()->role_id == 1
        ? 'non-tender.show-lte'
        : 'users.non-tender.show-lte';
    
    return view($view, compact('data', 'url'));
        }
   
        public function viewPdf(Request $request)
        {
            return $this->generateNonTenderPdf($request, 'view');
        }
        
        public function downloadPdf(Request $request)
        {
            return $this->generateNonTenderPdf($request, 'download');
        }
        private function generateNonTenderPdf(Request $request, $mode = 'view')
        {
            // Prioritaskan parameter 'end' jika ada
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
        
                // Hitung endDate sesuai kombinasi filter
                if ($month !== 'ALL') {
                    if ($day !== 'ALL') {
                        $endDate = Carbon::createFromDate($year, $month, $day)->endOfDay();
                    } else {
                        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
                    }
                } else {
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                }
            }
        
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
        
            $raw = DB::table('non_tender_pengumuman as p')
                ->leftJoin('non_tender_contract as c', 'p.kd_nontender', '=', 'c.kd_nontender')
                ->leftJoin('non_tender_selesai as s', 'p.kd_nontender', '=', 's.kd_nontender')
                ->select(
                    'p.nama_satker',
                    'p.jenis_pengadaan',
                    'p.pagu',
                    'p.hps',
                    DB::raw('COALESCE(c.nilai_kontrak, s.nilai_kontrak, 0) as nilai_terkontrak')
                )
                ->where('p.kd_klpd', 'D264')
                ->whereBetween('p.tgl_buat_paket', [$startDate, $endDate])
                ->whereIn('p.status_nontender', ['Selesai', 'Berlangsung'])
                ->get();
        
            $satkers = StrukturAnggaran::where('kd_klpd', 'D264')
                ->where('tahun_anggaran', $year)
                ->pluck('nama_satker')
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        
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
        
            $title = "REALISASI PAKET NON TENDER\nOPD PROVINSI LAMPUNG\nTAHUN ANGGARAN {$year} S.D TANGGAL " . strtoupper($endDate->translatedFormat('d F Y'));
        
            $html2pdf = new Html2Pdf('L', 'A3', 'en', true, 'UTF-8', [10, 10, 10, 10]);
            $view = auth()->user()->role_id == 1 ? 'non-tender.realization' : 'users.non-tender.realization';
        
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
                ? $html2pdf->output("realisasi_non_tender_{$year}.pdf", 'D')
                : $html2pdf->output();
        }
        
}