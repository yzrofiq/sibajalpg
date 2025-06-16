<?php

namespace App\Http\Controllers;

use App\Models\NonTender;
use App\Models\Satker;
use App\Models\Tender;
use App\Models\TenderSchedule;
use App\Services\RupService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;

class SatkerController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    public function sourceReport()
    {
        $now    = Carbon::now();
        $satker     = Satker::orderBy('nama_satker', 'ASC')->get();
        
        $query      = new Tender();

        $names      = [];
        $codes      = [];
        $data       = [];
        $total      = [
            'package_count' => 0,
            'apbd_pagu' => 0,
            'apbd_hps' => 0,
            'blud_pagu' => 0,
            'blud_hps' => 0,
            'apbn_pagu' => 0,
            'apbn_hps' => 0,
            'dak_pagu' => 0,
            'dak_hps' => 0,
        ];

        foreach ($satker as $key => $value) {
            if( !in_array($value->nama_satker, $names) ) {
                array_push($names, $value->nama_satker);
                array_push($codes, $value->kd_satker_str);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'package_count' => 0,
                    'apbd_pagu' => 0,
                    'apbd_hps' => 0,
                    'blud_pagu' => 0,
                    'blud_hps' => 0,
                    'apbn_pagu' => 0,
                    'apbn_hps' => 0,
                    'dak_pagu' => 0,
                    'dak_hps' => 0,
                ]);
            }
            $query  = $query->orWhere('kd_satker', 'like', $value->kd_satker_str);
        }

        $tenders    = $query->get();
        foreach ($tenders as $key => $value) {
            $index  = array_search($value->kd_satker, $codes);
            if( $index !== false ) {
                $source     = trim(strtolower($value->ang));
                if( $source ) {
                    $paguIndex  = $source . '_pagu';
                    $hpsIndex   = $source . '_hps';
                    $data[$index][$paguIndex] += $value->pagu;
                    $data[$index][$hpsIndex] += $value->hps;
                    $total[$paguIndex]  += $value->pagu;
                    $total[$hpsIndex]   += $value->hps;
                    $data[$index]['package_count'] += 1;
                    $total['package_count']  += 1;
                }
            }
        }

        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 15, 9, 15]);

        $render     = view('satker.fund-source', compact('data', 'total'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function categorizeReport()
    {
        $now        = Carbon::now();
        $satker     = Satker::orderBy('nama_satker', 'ASC')->get();
        $tender     = Tender::where('tahun_anggaran', 'like', $now->format("Y"))->where('kd_klpd', '=', 'D264')->whereNotNull('kd_satker')->get();
        $nonTender  = NonTender::where('tanggal_buat_paket', 'like', '%' . $now->format("Y") . '%')->get();

        $satkerCode     = [];
        $data       = [];
        $total      = [
            'package_count' => 0,
            'constructions_process' => 0,
            'constructions_done' => 0,
            'consultations_process' => 0,
            'consultations_done' => 0,
            'goods_process' => 0,
            'goods_done' => 0,
            'services_process' => 0,
            'services_done' => 0,
        ];

        foreach ($satker as $key => $value) {
            if( !in_array($value->kd_satker_str, $satkerCode) ) {
                array_push($satkerCode, $value->kd_satker_str);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'package_count' => 0,
                    'constructions_process' => 0,
                    'constructions_done' => 0,
                    'consultations_process' => 0,
                    'consultations_done' => 0,
                    'goods_process' => 0,
                    'goods_done' => 0,
                    'services_process' => 0,
                    'services_done' => 0,
                ]);
            }
        }

        foreach ($nonTender as $key => $value) {

            $index  = array_search($value->kd_satker, $satkerCode);
            if( $index !== false ) {
                $category   = getCategory($value->kategori_pengadaan);
                if( $category ) {
                    $data[$index]['package_count'] += 1;
                    $total['package_count'] += 1;
                    if( $value->nilai_kontrak ) {
                        // selesai
                        $categoryIndex  = $category . '_done';
                    } else {
                        // proses
                        $categoryIndex  = $category . '_process';
                    }
                    $data[$index][$categoryIndex] += 1;
                    $total[$categoryIndex] += 1;
                }
                
            }
            
        }

        foreach ($tender as $key => $value) {

            $index  = array_search($value->kd_satker, $satkerCode);
            if( $index !== false ) {
                $category   = getCategory($value->jenis_pengadaan);
                if( $category ) {
                    if( $value->nilai_kontrak ) {
                        // selesai
                        $categoryIndex  = $category . '_done';
                    } else {
                        // proses
                        $categoryIndex  = $category . '_process';
                    }
                    $data[$index][$categoryIndex] += 1;
                    $total[$categoryIndex] += 1;
                    $data[$index]['package_count'] += 1;
                    $total['package_count'] += 1;
                }
            }
            
        }

        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 15, 9, 15]);
        $render     = view('satker.categorize', compact('data', 'total'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function rup()
    {
        $now        = Carbon::now();
        $satker     = Satker::orderBy('nama_satker', 'ASC')->get();
        $data       = [];
        $total      = [];
        $codes      = [];

        $rup    = RupService::get();

        if( property_exists($rup, 'aaData') ) {
            $external   = $rup->aaData;
            foreach ($external as $value) {
                array_push($data, [
                    'name' => $value[1],
                    'data' => $value,
                ]);
            }
        }
        
        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 15, 9, 15]);
        $render     = view('satker.rup', compact('data', 'total'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function all()
    {
        $now        = Carbon::now();
        $satker     = Satker::orderBy('nama_satker', 'ASC')->get();
        $tender     = Tender::where('tahun_anggaran', 'like', $now->format("Y"))->where('kd_klpd', '=', 'D264')->whereNotNull('kd_satker')->get();
        $nonTender  = NonTender::where('tanggal_buat_paket', 'like', '%' . $now->format("Y") . '%')->get();

        $satkerCode     = [];
        $methodCode     = ['constructions', 'consultations', 'goods', 'services'];

        $data       = [];
        $method     = [
            'constructions' => ['name' => 'KONSTRUKSI', 'package_count' => 0, 'pagu' => 0, 'hps' => 0],
            'consultations' => ['name' => 'JASA KONSULTASI', 'package_count' => 0, 'pagu' => 0, 'hps' => 0],
            'goods' => ['name' => 'PENGADAAN BARANG', 'package_count' => 0, 'pagu' => 0, 'hps' => 0],
            'services' => ['name' => 'JASA LAINNYA', 'package_count' => 0, 'pagu' => 0, 'hps' => 0],
        ];

        $total      = [
            'package_count' => 0,
            'pagu' => 0,
            'hps' => 0,
        ];

        $totalMethod    = [
            'package_count' => 0,
            'pagu' => 0,
            'hps' => 0,
        ];

        foreach ($satker as $key => $value) {
            if( !in_array($value->kd_satker_str, $satkerCode) ) {
                array_push($satkerCode, $value->kd_satker_str);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'package_count' => 0,
                    'pagu' => 0,
                    'hps' => 0,
                ]);
            }
        }

        foreach ($nonTender as $key => $value) {

            $index  = array_search($value->kd_satker, $satkerCode);
            if( $index !== false ) {
                $category   = getCategory($value->kategori_pengadaan);
                if( $category ) {
                    $data[$index]['package_count'] += 1;
                    $total['package_count'] += 1;
                    $totalMethod['package_count'] += 1;
                    $method[$category]['package_count'] += 1;

                    $data[$index]['pagu'] += $value->pagu;
                    $data[$index]['hps'] += $value->hps;

                    $method[$category]['pagu'] += $value->pagu;
                    $method[$category]['hps'] += $value->hps;

                    $total['pagu'] += $value->pagu;
                    $total['hps'] += $value->hps;

                    $totalMethod['pagu'] += $value->pagu;
                    $totalMethod['hps'] += $value->hps;
                }
            }
        }

        foreach ($tender as $key => $value) {

            $index  = array_search($value->kd_satker, $satkerCode);
            if( $index !== false ) {
                $category   = getCategory($value->jenis_pengadaan);
                if( $category ) {
                    $data[$index]['package_count'] += 1;
                    $total['package_count'] += 1;
                    $totalMethod['package_count'] += 1;

                    $data[$index]['pagu'] += $value->pagu;
                    $data[$index]['hps'] += $value->hps;

                    $method[$category]['pagu'] += $value->pagu;
                    $method[$category]['hps'] += $value->hps;

                    $total['pagu'] += $value->pagu;
                    $total['hps'] += $value->hps;

                    $totalMethod['pagu'] += $value->pagu;
                    $totalMethod['hps'] += $value->hps;
                }
            }
            
        }

        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 12, 9, 12]);
        $render     = view('satker.all', compact('data', 'totalMethod', 'total', 'method'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function review()
    {
        $now    = Carbon::now();
        $satker     = Satker::orderBy('nama_satker', 'ASC')->get();
        $tender     = Tender::where('tahun_anggaran', 'like', $now->format("Y"))->where('kd_klpd', '=', 'D264')->whereNotNull('kd_satker')->get();
        $tenderIDs  = [];
        foreach ($tender as $key => $value) {
            array_push($tenderIDs, $value->id);
        }

        $satkerCode     = [];
        $data   = [];

        foreach ($satker as $key => $value) {
            if( !in_array($value->kd_satker_str, $satkerCode) ) {
                array_push($satkerCode, $value->kd_satker_str);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'packages' => [],
                ]);
            }
        }
        
        foreach ($tender as $key => $value) {
            $index  = array_search($value->kd_satker, $satkerCode);
            if( $index !== false ) {
                array_push($data[$index]['packages'], $value);
            }
        }

        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 12, 9, 12]);
        $render     = view('satker.report', compact('data'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }
}
