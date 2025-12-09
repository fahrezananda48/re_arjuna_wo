<?php

namespace App\Services;

use App\Enums\StatusKatalogEnum;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class CartService
{
    protected string|null $idcustomer;
    protected Keranjang $keranjang;

    public function __construct()
    {
        $this->idcustomer = Auth::user()?->customer->id;
        $this->keranjang =  new Keranjang;
    }
    public function getCartUser()
    {
        return $this->keranjang->where('id_customer', $this->idcustomer)->get();
    }

    public function countAllItemInCart()
    {
        $total = $this->keranjang->where('id_customer', $this->idcustomer)->count();
        if ($total >= 99) {
            $total = "99+";
        }
        return (string) $total;
    }
    public function getTotalHarga()
    {
        $totalHarga = 0;
        foreach ($this->keranjang->where('id_customer', $this->idcustomer)->get() as $item) {
            if ($item->katalog->status_katalog === StatusKatalogEnum::DISKON->value) {
                $totalHarga += $item->katalog->harga_after_diskon;
            } else {
                $totalHarga += $item->katalog->harga_katalog;
            }
        }
        return Number::currency($totalHarga, 'IDR', 'ID', 0);
    }

    public function getTotalHargaNumeric()
    {
        $totalHarga = 0;
        foreach ($this->keranjang->where('id_customer', $this->idcustomer)->get() as $item) {
            if ($item->katalog->status_katalog === StatusKatalogEnum::DISKON->value) {
                $totalHarga += $item->katalog->harga_after_diskon;
            } else {
                $totalHarga += $item->katalog->harga_katalog;
            }
        }
        return $totalHarga;
    }

    public function getTotalDiskonAmount()
    {
        $totalDiskon = 0;
        foreach ($this->keranjang->where('id_customer', $this->idcustomer)->get() as $item) {
            if ($item->katalog->status_katalog === StatusKatalogEnum::DISKON->value) {
                $totalDiskon += $item->katalog->diskon_katalog;
            }
        }
        return "$totalDiskon%";
    }
}
