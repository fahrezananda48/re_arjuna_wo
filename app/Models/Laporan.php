<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'tbl_laporan';
    protected $fillable = [
        'nama_laporan',
        'path_export',
        'tgl_export',
    ];

    protected function casts()
    {
        return [
            'tgl_export' => 'datetime'
        ];
    }
}
