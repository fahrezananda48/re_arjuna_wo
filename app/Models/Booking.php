<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'tbl_booking';
    protected $fillable = [
        'nama_cpp',
        'nama_cpw',
        'nama_ayah',
        'nama_ibu',
        'alamat',
        'nomor_telp',
        'tanggal_acara',
        'customer_id',
        'katalog_id',
        'detail_booking',
        'total_pembayaran',
    ];


    protected function casts()
    {
        return [
            'detail_booking' => 'array'
        ];
    }
}
