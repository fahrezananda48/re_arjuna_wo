<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tbl_transaksi';
    protected $fillable = [
        'id_booking',
        'kode_transaksi',
        'total_transaksi',
        'status_transaksi',
        'total_dp',
        'detail_transaksi',
    ];

    protected function casts()
    {
        return [
            'detail_transaksi' => 'array'
        ];
    }
}
