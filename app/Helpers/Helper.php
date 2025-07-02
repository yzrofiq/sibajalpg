<?php

use App\Models\NonTenderPengumuman;
use App\Models\Setting;
use App\Models\Tender;
use App\Services\HelperService;
use Carbon\Carbon;

function moneyFormat($number) {
    return HelperService::moneyFormat($number);
}

function getMonthName($monthNumber) {
    $months = [
        'januari', 'februari', 'maret', 'april',
        'mei', 'juni', 'juli', 'agustus',
        'september', 'oktober', 'november', 'desember',
    ];

    $monthIndex     = intval($monthNumber) - 1;
    if( $monthIndex < 0 ) {
        return $monthNumber;
    }
    return $months[$monthIndex];
}

function getIndonesianDate($date) {
    $exploded   = explode("-", $date);
    if( count($exploded) != 3 ) {
        return $date;
    }
    $year   = $exploded[0];
    $month  = getMonthName($exploded[1]);
    $day    = $exploded[2];

    return $day . " " . ucwords($month) . " " . $year; 
}

function getCurrentDate() {
    return getIndonesianDate(Carbon::now()->addHours(7)->format("Y-m-d H:i:s"));
}

function getCurrentDateTime() {
    return Carbon::now()->addHours(7)->format("Y-m-d H:i:s");
}


function getNonTenderCount() {
    return NonTenderPengumuman::where('kd_klpd', 'D264')
        ->whereIn('status_nontender', ['Selesai', 'Berlangsung'])
        ->count();
}


function getTenderCount() {
    return Tender::where('kd_klpd', '=', 'D264')->where('nama_status_tender', '=', 'aktif')->whereNotNull('nama_paket')->count();
}

function getCategory($category) {
    $category   = strtolower($category);

    if( strpos($category, "barang") !== false ) {
        return "goods";
    }

    if( strpos($category, "pekerjaan konstruksi") !== false ) {
        return "constructions";
    }

    if( strpos($category, "konsul") !== false ) {
        return "consultations";
    }

    if( strpos($category, "lainnya") !== false ) {
        return "services";
    }

    return null;
}

function getBela($tahun = null) {
    $tahun = $tahun ?? date('Y');

    $total = \App\Models\TokoDaring::where('tahun', $tahun)->sum('valuasi');

    if ($total > 0) {
        return $total;
    }

    // fallback jika belum ada data
    $setting = \App\Models\Setting::where('setting_code', 'bela')->first();
    return $setting ? $setting->setting_value : 0;
}


function getEkatalogCount() {
    $v5 = \App\Models\EkatalogV5Paket::whereIn('tahun_anggaran', [2024, 2025])->count();
    $v6 = \App\Models\EkatalogV6Paket::whereIn('tahun_anggaran', [2024, 2025])->count();
    return $v5 + $v6;
}


