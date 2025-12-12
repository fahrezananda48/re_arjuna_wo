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

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function statusTransaksiItem()
    {
        return match ($this->status_transaksi) {
            'menunggu_pembayaran' => '<span class="badge rounded-pill text-bg-warning">MENUNGGU PEMBAYARAN</span>',
            'dp' => '<span class="badge rounded-pill text-bg-info">DP</span>',
            'lunas' => '<span class="badge rounded-pill text-bg-success">LUNAS</span>',
            'batal' => '<span class="badge rounded-pill text-bg-danger">BATAL</span>',
            default => '<span class="badge rounded-pill text-bg-secondary">UNKNOWN</span>',
        };
    }
}
