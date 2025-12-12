<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $appends = [
        'detail_booking_array'
    ];


    protected function casts()
    {
        return [
            'detail_booking' => 'array'
        ];
    }

    public function getDetailBookingArrayAttribute()
    {
        $data = [];
        $kategoriGaun = [
            'gaun_pengantin_temu',
            'gaun_pengantin_akad',
            'gaun_pengantin_resepsi',
        ];
        foreach ($this->detail_booking['detail_booking'] ?? [] as $key => $ids) {
            // ubah nama key jadi camelCase class

            $className = $this->formatTextToClass($key); // ex: tenda => Tenda, gaun_pengantin => GaunPengantin
            if (in_array($key, $kategoriGaun)) {
                $className = 'GaunPengantin';
            }
            $modelPath = "App\\Models\\" . $className;

            if (class_exists($modelPath)) {
                // fetch data dari model berdasarkan id di array
                if (is_array($ids)) {
                    $data[$key] = $modelPath::whereIn('id', $ids)->get();
                } else {
                    $data[$key] = $modelPath::where('id', $ids)->get();
                }
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

    public function formatTanggal()
    {
        return Carbon::parse($this->tanggal_acara)->translatedFormat('l, d F Y');
    }
}
