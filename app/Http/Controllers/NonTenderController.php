<?php

namespace App\Http\Controllers;

use App\Models\NonTenderPengumuman;
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

    public function index()
    {
        $satkers = DB::table('non_tender_pengumuman')
            ->select('kd_satker', 'nama_satker')
            ->where('kd_klpd', 'D264')
            ->whereNotNull('kd_satker')
            ->groupBy('kd_satker', 'nama_satker')
            ->orderBy('nama_satker')
            ->get();

        $years = [];
        $categories = [];
        $categoriesCount = [];

        $code = $this->request->code;
        $name = $this->request->name;
        $satkerCode = $this->request->kd_satker;
        $categoryParam = $this->request->category;
        $perPage = $this->request->per_page ?: 50;
        $year = $this->request->year ?: date('Y');

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
            $query->where('non_tender_pengumuman.kd_satker', $satkerCode);
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
        'kd_satker' => $this->request->kd_satker,
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
            if ($satkerCode) $catQuery->where('kd_satker', $satkerCode);

            $categoriesCount[] = $catQuery->count();
        }

        $url = url()->full();
        if (!str_contains($url, "?")) $url .= "?";

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

        return view('non-tender.show-lte', compact('data', 'url'));
    }

    public function realization()
    {
        $now = Carbon::now();

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
            ->whereYear('p.tgl_buat_paket', $now->year)
            ->whereIn('p.status_nontender', ['Selesai', 'Berlangsung'])
            ->get();

        $satkers = DB::table('non_tender_pengumuman')
            ->select('nama_satker')
            ->where('kd_klpd', 'D264')
            ->whereNotNull('nama_satker')
            ->groupBy('nama_satker')
            ->orderBy('nama_satker')
            ->pluck('nama_satker')
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

        $html2pdf = new Html2Pdf('L', 'A3', 'en', true, 'UTF-8', [10, 10, 10, 10]);
        $render = view('non-tender.realization', [
            'data' => $finalData,
            'total' => $total
        ]);
        $html2pdf->writeHTML($render);
        $html2pdf->output();
    }
}