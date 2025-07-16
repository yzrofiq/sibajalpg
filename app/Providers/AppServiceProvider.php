<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\NonTenderPengumuman; // Import model yang diperlukan

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set default string length untuk kompatibilitas database
        Schema::defaultStringLength(191);

        // Ambil data tahun anggaran dari tabel NonTenderPengumuman
        $years = NonTenderPengumuman::select('tahun_anggaran')
                                       ->distinct()
                                       ->pluck('tahun_anggaran')
                                       ->toArray();

        // Ambil tahun sekarang untuk filter default
        $year = date('Y');

        // Share data years dan year ke seluruh view
        view()->share('years', $years);
        view()->share('year', $year);
    }
}
