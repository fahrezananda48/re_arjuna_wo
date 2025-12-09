<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'tbl_keranjang';

    protected $fillable = [
        'id_katalog',
        'id_customer',
        'detail_keranjang',
    ];

    protected $with = [
        'customer',
        'katalog',
    ];


    protected function casts()
    {
        return [
            'detail_keranjang' => 'array'
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Client::class, 'id_customer');
    }

    public function katalog()
    {
        return $this->belongsTo(Katalog::class, 'id_katalog');
    }
}
