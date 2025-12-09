<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dekorasi extends Model
{
    protected $table = 'tbl_dekorasi';

    protected $fillable = [
        'nama_dekorasi',
        'foto_dekorasi',
    ];

    protected $appends = [
        'link_foto_dekorasi'
    ];

    public function getLinkFotoDekorasiAttribute()
    {
        return asset('storage/foto_dekorasi/' . $this->foto_dekorasi);
    }
}
