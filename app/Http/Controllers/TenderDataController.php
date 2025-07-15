<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenderPengumumanData;
use App\Models\TenderSelesaiData;
use App\Models\TenderSelesaiNilaiData;
use App\Models\SPPBJData;
use App\Models\SPMKSPPData;
use App\Models\BAPBASTData;
use App\Models\KontrakData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TenderDataController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $sumber = $request->input('sumber_api');
        $keyword = $request->input('keyword');
        $namaPaketFilter = $request->input('nama_paket');

        $models = [
            TenderPengumumanData::class,
            TenderSelesaiData::class,
            TenderSelesaiNilaiData::class,
            SPPBJData::class,
            SPMKSPPData::class,
            BAPBASTData::class,
            KontrakData::class,
        ];

        $allData = collect();

        foreach ($models as $model) {
            $query = $model::query();

            if ($tahun) {
                $query->where('tahun', $tahun);
            }

            if ($sumber) {
                $query->where('sumber_api', $sumber);
            }

            if ($keyword) {
                $query->where('nama_paket', 'ilike', '%' . $keyword . '%');
            }

            $allData = $allData->merge($query->get());
        }

        // Gabungkan berdasarkan nama_paket
        $groupedByPaket = $allData->groupBy('nama_paket');

        $mergedData = $groupedByPaket->map(function ($items, $nama_paket) {
            return [
                'kd_tender'         => $items->pluck('kd_tender')->first(),
                'nama_paket'        => $nama_paket,
                'nama_klpd'         => $items->pluck('nama_klpd')->filter()->first(),
                'status_tender'     => $items->pluck('status_tender')->filter()->first(),
                'hps'               => $items->pluck('hps')->filter()->max() ?? 0,
                'nilai_pdn_kontrak' => $items->pluck('nilai_pdn_kontrak')->sum() ?? 0,
                'nilai_umk_kontrak' => $items->pluck('nilai_umk_kontrak')->sum() ?? 0,
                'tahun'             => $items->pluck('tahun')->filter()->first(),
                'sumber_api'        => implode(', ', $items->map(fn($i) => class_basename(get_class($i)))->unique()->toArray()),
                'nama_satker'       => $items->pluck('nama_satker')->filter()->first(),
            ];
        })->values();

        if ($namaPaketFilter) {
            $mergedData = $mergedData->filter(function ($item) use ($namaPaketFilter) {
                return $item['nama_paket'] === $namaPaketFilter;
            })->values();
        }        

        // Pagination
        $perPage = 15;
        $page = request()->get('page', 1);
        $paginatedData = new LengthAwarePaginator(
            $mergedData->forPage($page, $perPage),
            $mergedData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

                        // Ambil hanya satu nama_paket untuk setiap kd_tender
            $namaPaketList = $allData
            ->groupBy('kd_tender')
            ->map(fn($items) => $items->first()->nama_paket)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Dropdown untuk sumber_api
        $sumberList = collect($models)->map(fn($m) => class_basename($m));

        // Summary cards
        $summary = [
            'Tender' => TenderPengumumanData::count(),
            'Non Tender' => 0,
            'Total E-Catalog' => 0,
            'Toko Daring' => 0,
        ];

        return view('tender-data.index', [
            'data' => $paginatedData,
            'summary' => $summary,
            'sumberList' => $sumberList,
            'namaPaketList' => $namaPaketList,
        ]);
    }
}
