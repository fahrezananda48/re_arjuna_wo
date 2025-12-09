<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'tbl_customer';
    protected $fillable = [
        'nama_customer',
        'nomor_telp_customer',
        'alamat_lengkap_customer',
        'email',
    ];
}
