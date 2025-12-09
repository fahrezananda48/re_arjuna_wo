<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    protected $table = 'tbl_portofolio';
    protected $fillable = [
        'judul_portofolio',
        'deskripsi_portofolio',
        'foto_portofolio',
    ];

    protected $appends = [
        'link_foto_portofolio'
    ];

    public function getLinkFotoPortofolioAttribute()
    {
        return asset('storage/foto_portofolio/' . $this->foto_portofolio);
    }
}
