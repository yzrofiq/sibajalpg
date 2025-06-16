<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use App\Models\StrategicPackage;
use App\Models\Tender;
use App\Services\HelperService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spipu\Html2Pdf\Html2Pdf;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TenderController extends Controller
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

        $year   = $this->request->year;
        $perPage    = $this->request->per_page;
        if( !$perPage ) {
            $perPage    = 50;
        }
        if( !$year ) {
            $year   = date('Y');
        }

        $query  = Tender::where('tahun_anggaran', 'like', $year)->whereNotNull('nama_paket');

        if( $code ) {
            $query  = $query->where('kd_tender', 'like', $code);
        }

        if( $name ) {
            $query  = $query->where('nama_paket', 'like', '%'.$name.'%');
        }

        if( $satkerCode ) {
            $query  = $query->where('kd_satker', 'like', $satkerCode);
        }

        $totalFull  = $query->where('kd_klpd', '=', 'D264')->where('nama_status_tender', '=', 'aktif')->count();

        if( $categoryParam ) {
            $query  = $query->where('jenis_pengadaan', 'like', $categoryParam);
        }

        $total  = $query->where('kd_klpd', '=', 'D264')->where('nama_status_tender', '=', 'aktif')->count();
        
        $queryYear  = Tender::select('tahun_anggaran')->distinct('tahun_anggaran')->whereNotNull('nama_paket')->where('kd_klpd', '=', 'D264')->get();
        foreach ($queryYear as $item) {
            array_push($years, $item->tahun_anggaran);
        }

        $queryYear  = Tender::select('jenis_pengadaan')->distinct('jenis_pengadaan')->whereNotNull('nama_paket')->where('kd_klpd', '=', 'D264')->get();
        foreach ($queryYear as $item) {
            $category   = strtolower($item->jenis_pengadaan);
            if( $category AND !in_array($category, $categories) ) {
                array_push($categories, $item->jenis_pengadaan);
            }
        }

        foreach ($categories as $category) {
            $categoryQuery  = Tender::where('kd_klpd', '=', 'D264')->where('jenis_pengadaan', 'like', '%'.$category.'%')->where('nama_status_tender', '=', 'aktif')->where('tahun_anggaran', 'like', $year);
            if( $code ) {
                $categoryQuery  = $categoryQuery->where('kd_tender', 'like', '%' . $code . '%');
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

        $data   = $query->where('kd_klpd', '=', 'D264')->where('nama_status_tender', '=', 'aktif')->paginate($perPage)->withQueryString();
        foreach ($data as $key => $value) {
            $data[$key]->hps    = HelperService::moneyFormat($value->hps);
        }
        $url    = url()->full();
        if( strpos($url, "?") === false ) {
            $url    .= "?";
        }

        return view('tender.index-lte', compact('satkers', 'years', 'data', 'total', 'code', 'name', 'year', 'satkerCode', 'categories', 'categoriesCount', 'url', 'categoryParam', 'totalFull'));
    }

    public function show($code)
    {
        $url    = $this->request->from;
        if( !$url ) {
            $url    = route('tender.list');
        }
        $data     = Tender::where('kd_tender', 'like', $code)->first();
        $tender     = $data;
        if( !$data ) {
            return redirect( route('tender.list') );
        }

        return view('tender.show-lte', compact('data', 'url', 'tender'));
    }

    public function strategicPackage()
    {
        $data   = StrategicPackage::all();
        $tenderIDs  = [];
        $query  = Tender::where('kd_klpd', '=', 'D264')->where('nama_status_tender', '=', 'aktif');
        if( count($data) ) {
            $query  = $query->where(function($q) use($data) {
                foreach ($data as $key => $value) {
                    $q  = $q->orWhere('kd_tender', 'NOT LIKE', $value->code);
                }
                return $q;
            });
        }
        $tenders    = $query->get();

        return view('tender.strategic.list-lte', compact('data', 'tenders'));
    }

    public function strategicPackageDelete($id)
    {
        $strategicPackage   = StrategicPackage::find($id);
        if( !$strategicPackage ) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $strategicPackage->delete();
        return response()->json(['data' => $strategicPackage]);
    }

    public function formStrategicPackage()
    {
        return view('tender.strategic.add');
    }

    public function list()
    {
        $search     = $this->request->search;
        if( !$search ) {
            return response()->json(['message' => 'Field search required'], 400);
        }
        return Tender::where('nama_paket', 'like', '%'.$search.'%')->orWhere('kd_tender', 'like', '%'.$search.'%')->orWhere('kd_paket', 'like', '%'.$search.'%')->paginate(25);
    }

    public function strategicPackageAdd()
    {
        $id     = $this->request->id;
        $tender     = Tender::find($id);
        if(!$tender) {
            return response()->json(['message' => 'Tender tidak ditemukan'], 404);
        }
        $exist  = StrategicPackage::where('code', $tender->kd_tender)->first();
        if( $exist ) {
            return response()->json(['message' => 'Tender sudah dimasukkan'], 400);
        }

        $payloads   = [
            'code' => $tender->kd_tender,
            'created_by' => Auth::user()->id,
        ];

        $strategic  = StrategicPackage::create($payloads);
        return response()->json(['data' => $strategic]);
    }

    public function strategicPackageDownload()
    {
        $data   = StrategicPackage::all();
        $html2pdf = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 12, 9, 12]);
        $render     = view('tender.strategic.download', compact('data'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function realization()
    {
        $now    = Carbon::now();
        $raw        = Tender::where('tahun_anggaran', 'like', $now->format("Y"))->where('kd_klpd', '=', 'D264')->whereNotNull('kd_satker')->get();
        $satkers    = Satker::orderBy('nama_satker')->get();
        $html2pdf   = new Html2Pdf('L', 'F4', 'en', true, 'UTF-8', [9, 15, 9, 20]);

        $namaSatker = [];
        $data       = [];
        $total      = [
            'total_package_count' => 0,
            'total_dpa' => 0,
            'total_hps' => 0,
            'done_package_count' => 0,
            'done_pagu' => 0,
            'done_hps' => 0,
            'process_value' => 0,
            'process_package_count' => 0,
            'value' => 0,
            'efficiency' => 0,
            'efficiency_percentage' => 0,
        ];
        $total['source'] = [];

        foreach ($satkers as $key => $value) {
            if( !in_array($value->kd_satker_str, $namaSatker) ) {
                array_push($namaSatker, $value->kd_satker_str);
                array_push($data, [
                    'name' => $value->nama_satker,
                    'code' => $value->kd_satker,
                    'total_package_count' => 0,
                    'total_dpa' => 0,
                    'total_hps' => 0,
                    'done_package_count' => 0,
                    'done_pagu' => 0,
                    'done_hps' => 0,
                    'process_value' => 0,
                    'process_package_count' => 0,
                    'value' => 0,
                    'efficiency' => 0,
                    'efficiency_percentage' => 0,
                    'source' => [],
                ]);
            }
        }

        foreach ($raw as $key => $value) {
            
            $index  = array_search($value->kd_satker, $namaSatker);

            if( $index !== false ) {
                $data[$index]['total_package_count']    += 1;
                $data[$index]['total_dpa']    += $value->pagu;
                $data[$index]['total_hps']    += $value->hps;
                $total['total_package_count']    += 1;
                $total['total_dpa']    += $value->pagu;
                $total['total_hps']    += $value->hps;
                if( $value->nilai_kontrak ) {
                    // done
                    $data[$index]['done_package_count']    += 1;
                    $data[$index]['done_pagu']  += $value->pagu;
                    $data[$index]['done_hps']   += $value->hps;
                    $data[$index]['process_value']   += $value->nilai_kontrak;
                    $total['done_package_count']    += 1;
                    $total['done_pagu']  += $value->pagu;
                    $total['done_hps']   += $value->hps;
                    $total['process_value']   += $value->nilai_kontrak;
                } else {
                    // process
                    $data[$index]['process_package_count']   += 1;
                    $data[$index]['value']   += $value->pagu;
                    $total['process_package_count']   += 1;
                    $total['value']   += $value->pagu;
                }
    
                $category   = getCategory($value->kategori_pengadaan);
                if( $category ) {
                    $data[$index][$category] += 1;
                }
                $source     = $data[$index]['source'];
                if( !in_array($value->ang, $source)
                    AND $value->ang ) {
                    array_push($data[$index]['source'], $value->ang);
                }
                if( !in_array($value->ang, $total['source'])
                    AND $value->ang ) {
                    array_push($total['source'], $value->ang);
                }
            }

        }

        foreach ($data as $key => $item) {
            if( $item['done_pagu'] > 0 ) {
                $data[$key]['efficiency_percentage'] = intval(($item['done_pagu'] - $item['process_value']) / $item['done_pagu'] * 100);
            }
        }

        if( $total['done_pagu'] > 0 ) {
            $total['efficiency_percentage'] = intval(($total['done_pagu'] - $total['process_value']) / $total['done_pagu'] * 100);
        }

        $auth   = Auth::user();
        if( $auth->role_id == 1 ) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->getColumnDimension('A')->setWidth(15, 'px');
            $sheet->getColumnDimension('B')->setWidth(15, 'px');

            $sheet->getColumnDimension('Q')->setWidth(15, 'px');
            $sheet->getColumnDimension('R')->setWidth(15, 'px');

            $rows   = 2;

            $cellCode           = 'C' . $rows;
            $sheet->setCellValue($cellCode, 'No.');
            $sheet->mergeCells('C' . $rows . ':C' . ($rows + 1));

            $cellCode           = 'D' . $rows;
            $sheet->setCellValue($cellCode, 'OPD');
            $sheet->mergeCells('D' . $rows . ':D' . ($rows + 1));

            $cellCode           = 'E' . $rows;
            $sheet->setCellValue($cellCode, 'Total Paket Tender');
            $sheet->mergeCells('E' . $rows . ':G' . $rows);

            $cellCode           = 'E' . ($rows + 1);
            $sheet->setCellValue($cellCode, "Total Paket\nKeseluruhan");
            $cellCode           = 'F' . ($rows + 1);
            $sheet->setCellValue($cellCode, "Nilai DPA\n (Rp)");
            $cellCode           = 'G' . ($rows + 1);
            $sheet->setCellValue($cellCode, "HPS\n(Rp)");

            $cellCode           = 'H' . $rows;
            $sheet->setCellValue($cellCode, 'Paket Selesai');
            $sheet->mergeCells('H' . $rows . ':J' . $rows);

            $cellCode           = 'H' . ($rows + 1);
            $sheet->setCellValue($cellCode, "Total Paket\nSelesai");
            $cellCode           = 'I' . ($rows + 1);
            $sheet->setCellValue($cellCode, "Nilai Pagu DPA\n (Rp)");
            $cellCode           = 'J' . ($rows + 1);
            $sheet->setCellValue($cellCode, "HPS\n(Rp)");

            $cellCode           = 'K' . $rows;
            $sheet->setCellValue($cellCode, 'Proses Tender');
            $sheet->mergeCells('K' . $rows . ':M' . $rows);

            $cellCode           = 'N' . $rows;
            $sheet->setCellValue($cellCode, 'Efisiensi');
            $sheet->mergeCells('N' . $rows . ':N' . ($rows + 1));

            $cellCode           = 'O' . $rows;
            $sheet->setCellValue($cellCode, '%');
            $sheet->mergeCells('O' . $rows . ':O' . ($rows + 1));

            $cellCode           = 'P' . $rows;
            $sheet->setCellValue($cellCode, 'Sumber Dana');
            $sheet->mergeCells('P' . $rows . ':P' . ($rows + 1));

            $sheet->getRowDimension($rows + 1)->setRowHeight(29, 'pt');

            $rows = 4;

            foreach ($data as $index => $item) {
                $cellCode           = 'C' . $rows;
                $sheet->setCellValue($cellCode, $index + 1);

                $cellCode           = 'D' . $rows;
                $sheet->setCellValue($cellCode, $item['name']);

                $cellCode           = 'E' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['total_package_count']));

                $cellCode           = 'F' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['total_dpa']));

                $cellCode           = 'G' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['total_hps']));

                $cellCode           = 'H' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['done_package_count']));

                $cellCode           = 'I' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['done_pagu']));

                $cellCode           = 'J' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['done_hps']));

                $cellCode           = 'K' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['process_value']));

                $cellCode           = 'L' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['process_package_count']));

                $cellCode           = 'M' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['value']));

                $cellCode           = 'N' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['done_pagu'] - $item['process_value']));

                $cellCode           = 'O' . $rows;
                $sheet->setCellValue($cellCode, moneyFormat($item['efficiency_percentage']));

                $cellCode           = 'P' . $rows;
                $sheet->setCellValue($cellCode, implode("\n", $item['source']));

                $rows++;
            }

            $cellCode           = 'C' . $rows;
            $sheet->setCellValue($cellCode, 'Total');
            $sheet->mergeCells('C' . $rows . ':D' . $rows);

            $cellCode           = 'E' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['total_package_count']));

            $cellCode           = 'F' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['total_dpa']));

            $cellCode           = 'G' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['total_hps']));

            $cellCode           = 'H' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['done_package_count']));

            $cellCode           = 'I' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['done_pagu']));

            $cellCode           = 'J' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['done_hps']));

            $cellCode           = 'K' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['process_value']));

            $cellCode           = 'L' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['process_package_count']));

            $cellCode           = 'M' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['value']));

            $cellCode           = 'N' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['done_pagu'] - $total['process_value']));

            $cellCode           = 'O' . $rows;
            $sheet->setCellValue($cellCode, moneyFormat($total['efficiency_percentage']));

            $cellCode           = 'P' . $rows;
            $sheet->setCellValue($cellCode, implode("\n", $total['source']));

            $lastRows   = 14.5;
            if( count($total['source']) > 1 ) {
                $lastRows   = count($total['source']) * $lastRows;
            }
            $sheet->getRowDimension($rows)->setRowHeight($lastRows, 'pt');

            $sheet->getStyle('C2:P' . $rows)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $rows   = $rows + 3;
            $cellCode           = 'M' . $rows;
            $sheet->setCellValue($cellCode, "KEPALA BIRO PENGADAAN BARANG DAN JASA");
            $sheet->mergeCells('M' . $rows . ':O' . $rows);
            $rows++;

            for ($i=0; $i < 3; $i++) { 
                $sheet->getRowDimension($rows)->setRowHeight(29, 'pt');
                $rows++;
            }

            $cellCode           = 'M' . $rows;
            $sheet->setCellValue($cellCode, "SLAMET RIADI, S.Sos");
            $sheet->mergeCells('M' . $rows . ':O' . $rows);
            $rows++;

            $cellCode           = 'M' . $rows;
            $sheet->setCellValue($cellCode, "PEMBINA UTAMA MUDA");
            $sheet->mergeCells('M' . $rows . ':O' . $rows);
            $rows++;

            $cellCode           = 'M' . $rows;
            $sheet->setCellValue($cellCode, "NIP. 19670828 199903 1 005");
            $sheet->mergeCells('M' . $rows . ':O' . $rows);
            $rows++;

            // <p style="margin: 0;">SLAMET RIADI, S.Sos</p>
            // <p style="margin: 0;">PEMBINA UTAMA MUDA</p>
            // <p style="margin: 0;">NIP. 19670828 199903 1 005</p>

            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);

            $sheet->getStyle('C2'  . ':P' . ($rows + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2' . ':P' . ($rows + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D4'  . ':D' . ($rows))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Realisasi Tender.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            die();
        }
        $render     = view('tender.realization', compact('data', 'total'));
        $html2pdf->writeHTML($render);

        $html2pdf->output();
    }

    public function update($code)
    {
        $tender     = Tender::where('kd_tender', '=', $code)->first();
        if( !$tender ) {
            return redirect( route('tender.list') );
        }
        $payloads   = $this->request->only(['implementation', 'letter_number', 'news_number', 'note']);

        $tender->update($payloads);

        return redirect( route('tender.show', ['code' => $code]) );
    }
}
