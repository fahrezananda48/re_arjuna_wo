<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tbl_transaksi';
    protected $fillable = [
        'katalog_id',
        'customer_id',
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

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function customer()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function katalog()
    {
        return $this->belongsTo(Katalog::class, 'katalog_id');
    }
}
