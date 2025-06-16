<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonTenderSchedule extends Model
{
    use HasFactory;

    protected $fillable     = [
        "kd_nontender",
        "kd_tahapan",
        "nama_tahapan",
        "kd_akt",
        "nama_akt",
        "tanggal_awal",
        "tanggal_akhir",
    ];

    
}
