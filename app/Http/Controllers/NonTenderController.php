<?php

namespace App\Http\Controllers;

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
    // Ambil tahun dari request atau set ke tahun sekarang jika tidak ada
    $year = $request->input('year', date('Y'));

    $satkers = DB::table('non_tender_pengumuman')
        ->select('kd_satker', 'nama_satker')
        ->where('kd_klpd', 'D264')
        ->whereNotNull('kd_satker')
        ->groupBy('kd_satker', 'nama_satker')
        ->orderBy('nama_satker')
        ->get();

    $categories = [];
    $categoriesCount = [];

    $code = $request->input('code');
    $name = $request->input('name');
    $satkerCode = $request->input('kd_satker');
    $categoryParam = $request->input('category');
    $perPage = $request->input('per_page', 50);

    // Pastikan filter tahun diterapkan pada query
    $query = NonTenderPengumuman::query()
        ->leftJoin('non_tender_selesai', 'non_tender_pengumuman.kd_nontender', '=', 'non_tender_selesai.kd_nontender')
        ->leftJoin('non_tender_contract', 'non_tender_pengumuman.kd_nontender', '=', 'non_tender_contract.kd_nontender')
        ->where('non_tender_pengumuman.tahun_anggaran', $year) // Filter berdasarkan tahun
        ->where('non_tender_pengumuman.kd_klpd', 'D264')
        ->whereIn('non_tender_pengumuman.status_nontender', ['Selesai', 'Berlangsung']);

    if ($code) {
        $query->where('non_tender_pengumuman.kd_nontender', 'like', "%$code%");
    }

    if ($name) {
        $query->whereRaw('LOWER(non_tender_pengumuman.nama_paket) LIKE ?', ['%' . strtolower($name) . '%']);
    }

    if ($satkerCode) {
        $query->where('non_tender_pengumuman.kd_satker', $satkerCode);
    }

    if ($categoryParam) {
        $query->where('non_tender_pengumuman.jenis_pengadaan', 'like', "%$categoryParam%");
    }

    // Ambil data sesuai filter tahun dan query lainnya
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

    // Ambil daftar tahun dari NonTenderPengumuman untuk dropdown tahun
    $years = NonTenderPengumuman::select('tahun_anggaran')
        ->distinct()
        ->pluck('tahun_anggaran')
        ->toArray();

    // Ambil kategori dari NonTenderPengumuman untuk dropdown kategori
    $categories = NonTenderPengumuman::select('jenis_pengadaan')
        ->distinct()
        ->pluck('jenis_pengadaan')
        ->toArray();

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
        if ($satkerCode) $catQuery->where('kd_satker', $satkerCode);

        $categoriesCount[] = $catQuery->count();
    }

    $url = url()->full();
    if (!str_contains($url, "?")) $url .= "?";

    // Menampilkan tampilan sesuai dengan role user (role_id == 2)
    if (auth()->user()->role_id == 2) {
        return view('users.non-tender.index-lte', compact(
            'satkers', 'years', 'data', 'total', 'totalFull',
            'code', 'name', 'year', 'satkerCode',
            'categories', 'categoriesCount', 'url', 'categoryParam'
        ));
    }

    return view('non-tender.index-lte', compact(
        'satkers', 'years', 'data', 'total', 'totalFull',
        'code', 'name', 'year', 'satkerCode',
        'categories', 'categoriesCount', 'url', 'categoryParam'
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
    $year = $request->input('year', date('Y'));
    $month = $request->input('month');
    $day = $request->input('day');

    // Query to fetch the relevant data based on year, month, and day filters
    $query = DB::table('non_tender_pengumuman as p')
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
        ->whereYear('p.tgl_buat_paket', $year);

    // Apply month and day filters if provided
    if ($month !== 'ALL') {
        $query->whereMonth('p.tgl_buat_paket', $month);
    }
    if ($day !== 'ALL') {
        $query->whereDay('p.tgl_buat_paket', $day);
    }

    // Fetch data based on the filters
    $raw = $query->whereIn('p.status_nontender', ['Selesai', 'Berlangsung'])->get();

    // Fetch all satkers
    $satkers = StrukturAnggaran::where('kd_klpd', 'D264')
        ->where('tahun_anggaran', $year)
        ->pluck('nama_satker')
        ->unique()
        ->sort()
        ->values()
        ->toArray();

    // Initialize the data and total arrays
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

    // Process the raw data
    foreach ($raw as $value) {
        $satker = $value->nama_satker;
        if (!isset($data[$satker])) continue;

        $data[$satker]['package_count'] += 1;
        $total['package_count'] += 1;

        $category = getCategory($value->jenis_pengadaan);
        if ($category && isset($data[$satker][$category])) {
            $data[$satker][$category] += 1;
            $total[$category] += 1;
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

    // Determine the title based on the filters
    $monthName = strtoupper(getMonthName($month));
    $title = "REALISASI PAKET NON TENDER\nOPD PROVINSI LAMPUNG\n";

    if ($day !== 'ALL' && $day !== null) {
        $title .= "TAHUN ANGGARAN {$year} TANGGAL {$day} {$monthName} {$year}";
    } elseif ($month !== 'ALL') {
        $title .= "TAHUN ANGGARAN {$year} {$monthName} {$year}";
    } else {
        $title .= "TAHUN ANGGARAN {$year} S.D TANGGAL " . strtoupper(now()->translatedFormat('d F Y'));
    }

    $html2pdf = new Html2Pdf('L', 'A3', 'en', true, 'UTF-8', [10, 10, 10, 10]);
    $view = auth()->user()->role_id == 1
        ? 'non-tender.realization'
        : 'users.non-tender.realization';

    $render = view($view, [
        'data' => $finalData,
        'total' => $total,
        'title' => $title,
        'month' => $month,  // Make sure these variables are passed to the view
        'day' => $day,
        'year' => $year,
    ]);
    $html2pdf->pdf->SetAutoPageBreak(true, 10);
    $html2pdf->writeHTML($render);
    $html2pdf->output();
}







    
public function downloadPdf(Request $request)
{
    $year = $request->input('year', date('Y'));
    $month = $request->input('month');
    $day = $request->input('day');

    // Query to fetch the relevant data based on year, month, and day filters
    $query = DB::table('non_tender_pengumuman as p')
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
        ->whereYear('p.tgl_buat_paket', $year);

    // Apply month and day filters if provided
    if ($month !== 'ALL') {
        $query->whereMonth('p.tgl_buat_paket', $month);
    }

    if ($day !== 'ALL') {
        $query->whereDay('p.tgl_buat_paket', $day);
    }

    $raw = $query->whereIn('p.status_nontender', ['Selesai', 'Berlangsung'])->get();

    // Fetch all satkers
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

        $data[$satker]['package_count'] += 1;
        $total['package_count'] += 1;

        $category = getCategory($value->jenis_pengadaan);
        if ($category && isset($data[$satker][$category])) {
            $data[$satker][$category] += 1;
            $total[$category] += 1;
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

    // Determine the title based on the filters
    $monthName = strtoupper(getMonthName($month));
    $title = "REALISASI PAKET NON TENDER\nOPD PROVINSI LAMPUNG\n";

    // If the selected year is in the past (before current year)
    if ($year < date('Y')) { 
        $title .= "TAHUN ANGGARAN {$year}";
    } elseif ($year == date('Y')) {  // For current year
        if ($month !== 'ALL' && $day !== 'ALL') {
            // If both month and day are selected
            $title .= "TAHUN ANGGARAN {$year} TANGGAL {$day} {$monthName} {$year}";
        } elseif ($month !== 'ALL') {
            // If only month is selected
            $title .= "TAHUN ANGGARAN {$year} {$monthName} {$year}";
        } else {
            // If only year is selected, show up to today
            $title .= "TAHUN ANGGARAN {$year} S.D TANGGAL " . strtoupper(now()->translatedFormat('d F Y'));
        }
    } else {
        // For future years, it will display the year
        $title .= "TAHUN ANGGARAN {$year}";
    }

    // Generate the PDF
    $html2pdf = new Html2Pdf('L', 'A3', 'en', true, 'UTF-8', [10, 10, 10, 10]);
    $view = auth()->user()->role_id == 1
        ? 'non-tender.realization'
        : 'users.non-tender.realization';

    // Render the view with the necessary data
    $render = view($view, [
        'data' => $finalData,
        'total' => $total,
        'title' => $title, // Pass title dynamically to the view
        'month' => $month,
        'day' => $day,
        'year' => $year,
    ]);
    
    // Set auto page breaks and generate the PDF
    $html2pdf->pdf->SetAutoPageBreak(true, 10);
    $html2pdf->writeHTML($render);
    
    // Return the generated PDF as download
    return $html2pdf->output("realisasi_non_tender_{$year}.pdf", 'D');
}




    
}