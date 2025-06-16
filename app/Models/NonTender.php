<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonTender extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "tahun_anggaran",
        "kd_klpd",
        "nama_klpd",
        "jenis_klpd",
        "kd_satker",
        "nama_satker",
        "kd_lpse",
        "nama_lpse",
        "kd_nontender",
        "kd_rup_paket",
        "nama_paket",
        "pagu",
        "hps",
        "nilai_penawaran",
        "nilai_terkoreksi",
        "nilai_negosiasi",
        "nilai_kontrak",
        "anggaran",
        "kualifikasi_paket",
        "kategori_pengadaan",
        "metode_pengadaan",
        "tanggal_buat_paket",
        "tanggal_pengumuman_nontender",
        "tanggal_selesai_nontender",
        "kd_penyedia",
        "nama_penyedia",
        "npwp_penyedia",
        "kode_mak",
        "nama_status_nontender",
        "versi_nontender",
        "ket_diulang",
        "ket_ditutup",
        "lokasi_pekerjaan",
        "nilai_terkontrak",
    ];

    public function schedules()
    {
        return $this->hasMany(NonTenderSchedule::class, 'kd_nontender', 'kd_nontender');
    }

    public function getCurrentScheduleAttribute()
    {
        $schedule  = $this->schedules()->where('tanggal_awal', '<=', now())->where('tanggal_akhir', '>=', now())->first();
        if( $schedule ) {
            return $schedule->nama_tahapan;
        }
        $schedule  = $this->schedules()->latest()->first();
        if( $schedule ) {
            return $schedule->nama_tahapan;   
        }
        return null;
    }
}
