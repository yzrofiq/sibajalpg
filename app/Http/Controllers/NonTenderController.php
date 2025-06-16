<?php

namespace App\Http\Controllers;

use App\Models\NonTender;
use App\Models\Satker;
use App\Services\HelperService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;

class NonTenderController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    public function index()
    {
        $satkers    = Satker::all();
        $years  = [];
        $categories     = [];
        $categoriesCount    = [];
        $code   = $this->request->code;
        $name   = $this->request->name;
        $satkerCode     = $this->request->kd_satker;
        $categoryParam  = $this->request->category;
        $perPage    = $this->request->per_page;
        if( !$perPage ) {
            $perPage    = 50;
        }

        $year   = $this->request->year;
        if( !$year ) {
            $year   = date('Y');
        }

        $query  = NonTender::where('tahun_anggaran', 'like', $year);

        if( $code ) {
            $query  = $query->where('kd_nontender', 'like', $code);
        }

        if( $name ) {
            $query  = $query->where('nama_paket', 'like', '%'.$name.'%');
        }

        if( $satkerCode ) {
            $query  = $query->where('kd_satker', 'like', $satkerCode);
        }

        $totalFull  = $query->where('kd_klpd', '=', 'D264')->where('nama_status_nontender', '=', 'aktif')->count();

        if( $categoryParam ) {
            $query  = $query->where('kategori_pengadaan', 'like', $categoryParam);
        }

        $total  = $query->where('kd_klpd', '=', 'D264')->where('nama_status_nontender', '=', 'aktif')->count();
        
        $queryYear  = NonTender::select('tahun_anggaran')->distinct('tahun_anggaran')->get();
        foreach ($queryYear as $item) {
            array_push($years, $item->tahun_anggaran);
        }

        $queryYear  = NonTender::select('kategori_pengadaan')->distinct('kategori_pengadaan')->get();
        foreach ($queryYear as $item) {
            $category   = strtolower($item->kategori_pengadaan);
            if( !in_array($category, $categories) ) {
                array_push($categories, $item->kategori_pengadaan);
            }
        }

        foreach ($categories as $category) {
            $categoryQuery  = NonTender::where('kd_klpd', '=', 'D264')->where('kategori_pengadaan', 'like', '%'.$category.'%')->where('nama_status_nontender', '=', 'aktif')->where('tahun_anggaran', 'like', $year);
            if( $code ) {
                $categoryQuery  = $categoryQuery->where('kd_nontender', 'like', $code);
            }
    
            if( $name ) {
                $categoryQuery  = $categoryQuery->where('nama_paket', 'like', '%'.$name.'%');
            }
    
            if( $satkerCode ) {
                $categoryQuery  = $categoryQuery->where('kd_satker', 'like', $satkerCode);
            }

            $count  = $categoryQuery->count();
            array_push($categoriesCount, $count);
        }

        $data   = $query->where('kd_klpd', '=', 'D264')->where('nama_status_nontender', '=', 'aktif')->paginate($perPage)->withQueryString();
        foreach ($data as $key => $value) {
            $data[$key]->hps    = HelperService::moneyFormat($value->hps);
        }
        $url    = url()->full();
        if( strpos($url, "?") === false ) {
            $url    .= "?";
        }
        return view('non-tender.index-lte', compact('satkers', 'years', 'data', 'total', 'code', 'name', 'year', 'satkerCode', 'categories', 'categoriesCount', 'url', 'categoryParam', 'totalFull'));
    }

    public function show($code)
    {
        $url    = $this->request->from;
        if( !$url ) {
            $url    = route('non-tender.list');
        }
        $data   = NonTender::where('kd_nontender', 'like', $code)->first();
        $nonTender  = $data;
        if( !$data ) {
            return redirect( route('front') );
        }

        $data->pagu     = HelperService::moneyFormat($data->pagu);

        return view('non-tender.show-lte', compact('data', 'url', 'nonTender'));
    }

    public function realization()
    {
        $now    = Carbon::now();
        $raw        = NonTender::where('kd_klpd', '=', 'D264')->where('tanggal_buat_paket', 'like', '%' . $now->format("Y") . '%')->get();
        $satkers    = Satker::orderBy('nama_satker', 'ASC')->get();
        $namaSatker = [];
        $data       = [];
        $total      = [
            'package_count' => 0,
            'constructions' => 0,
            'consultations' => 0,
            'goods' => 0,
            'services' => 0,
            'pagu' => 0,
            'hps' => 0,
            'efficiency' => 0,
        ];

        foreach ($satkers as $key => $value) {
            if( !in_array($value->nama_satker, $namaSatker) ) {
                array_push($namaSatker, $value->nama_satker);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'package_count' => 0,
                    'constructions' => 0,
                    'consultations' => 0,
                    'goods' => 0,
                    'services' => 0,
                    'pagu' => 0,
                    'hps' => 0,
                    'efficiency' => 0,
                ]);
            }
        }

        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 15, 9, 15]);

        foreach ($raw as $key => $value) {

            $index  = array_search($value->nama_satker, $namaSatker);
            if( $index !== false ) {
                $data[$index]['package_count'] += 1;
                $total['package_count'] += 1;
                $category   = getCategory($value->kategori_pengadaan);
                if( $category ) {
                    $data[$index][$category] += 1;
                    $total[$category] += 1;
                }

                $data[$index]['pagu']   += $value->pagu;
                $total['pagu']          += $value->pagu;
                $data[$index]['hps']    += $value->hps;
                $total['hps']           += $value->hps;
                $efficiency             = $value->pagu - $value->hps;
                $data[$index]['efficiency'] += $efficiency;
                $total['efficiency'] += $efficiency;
            }
            
        }

        $render     = view('non-tender.realization', compact('data', 'total'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }
}
