<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    protected $fillable = [
        'kd_satker',
        'kd_satker_lpse', // ✅ Tambahkan ini
        'kd_satker_str',
        'nama_satker',
        'alamat',
        'telepon',
        'fax',
        'kodepos',
        'status_satker',
        'ket_satker',
        'jenis_satker',
        'kd_klpd',
        'nama_klpd',
        'jenis_klpd',
        'kode_eselon',
    ];
}
