<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaunPengantin extends Model
{
    protected $table = 'tbl_gaun_pengantin';

    protected $fillable = [
        'nama_gaun',
        'foto_gaun',
    ];

    protected $appends = [
        'link_foto_gaun'
    ];

    public function getLinkFotoGaunAttribute()
    {
        return asset('storage/foto_gaun/' . $this->foto_gaun);
    }
}
