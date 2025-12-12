<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Katalog extends Model
{
    protected $table = 'tbl_katalog';
    protected $fillable = [
        'nama_katalog',
        'harga_katalog',
        'thumbnail_katalog',
        'deskripsi_katalog',
        'status_katalog',
        'diskon_katalog',
        'item_katalog',
        'data_vendor',
    ];

    protected $appends = [
        'link_thumbnail_katalog',
        'item_array_katalog',
        'data_vendor_array_katalog',
        'harga_after_diskon',
        'harga_rupiah',
    ];

    protected function casts()
    {
        return [
            'item_katalog' => "array",
            'data_vendor' => "array",
        ];
    }

    public function getHargaRupiahAttribute()
    {
        return Number::currency($this->harga_katalog, 'IDR', 'ID', 0);
    }

    public function getDataVendorArrayKatalogAttribute()
    {
        $data = [];

        if (!is_array($this->data_vendor)) {
            return [];
        }
        $kategoriGaun = [
            'gaun_pengantin_temu',
            'gaun_pengantin_akad',
            'gaun_pengantin_resepsi',
        ];

        foreach ($this->data_vendor as $key => $ids) {
            // default: nama class hasil konversi
            $className = $this->formatTextToClass($key);

            // kalau key termasuk kategori gaun, pakai model GaunPengantin
            if (in_array($key, $kategoriGaun)) {
                $className = 'GaunPengantin';
            }

            $modelPath = "App\\Models\\" . $className;

            if (class_exists($modelPath)) {
                $data[$key] = $modelPath::whereIn('id', $ids)->get();
            } else {
                $data[$key] = [];
            }
        }

        return $data;
    }

    private function formatTextToClass($text)
    {
        $text = str_replace('_', ' ', $text); // ubah _ jadi spasi
        $text = ucwords($text);               // kapital setiap kata
        return str_replace(' ', '', $text);   // hapus spasi
    }


    public function getItemArrayKatalogAttribute()
    {
        $result = [];
        if (is_array($this->item_katalog)) {
            $result = $this->item_katalog;
        } else if (is_string($this->item_katalog)) {
            $result = explode(',', strval($this->item_katalog));
        }
        return $result;
    }

    public function getLinkThumbnailKatalogAttribute()
    {
        return asset("storage/foto_katalog/{$this->thumbnail_katalog}");
    }
    public function getThumbnailUrl()
    {
        return asset("storage/foto_katalog/{$this->thumbnail_katalog}");
    }

    public function formatRupiah()
    {
        return Number::currency($this->harga_katalog, 'IDR', 'id_ID', 0);
    }

    public function getHargaAfterDiskon()
    {
        $hargaSetelahDiskon = $this->harga_katalog - ($this->harga_katalog * ($this->diskon_katalog / 100));
        return Number::currency($hargaSetelahDiskon, 'IDR', 'id_ID', 0);
    }

    public function getHargaAfterDiskonAttribute()
    {
        return $this->harga_katalog - ($this->harga_katalog * ($this->diskon_katalog / 100));
    }
}
