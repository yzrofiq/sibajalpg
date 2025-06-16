<?php

use App\Models\NonTender;
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
    return NonTender::where('kd_klpd', '=', 'D264')->where('nama_status_nontender', '=', 'aktif')->count();
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

function getBela() {
    $setting    = Setting::where('setting_code', '=', 'bela')->first();
    if( !$setting ) {
        $setting    = Setting::create(['setting_code' => 'bela', 'setting_value' => '0']);
    }
    return $setting->setting_value;
}