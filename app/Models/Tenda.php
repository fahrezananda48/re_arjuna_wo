<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenda extends Model
{
    protected $table = 'tbl_tenda';

    protected $fillable = [
        'nama_tenda',
        'foto_tenda',
    ];

    protected $appends = [
        'link_foto_tenda'
    ];

    public function getLinkFotoTendaAttribute()
    {
        return asset('storage/foto_tenda/' . $this->foto_tenda);
    }
}
