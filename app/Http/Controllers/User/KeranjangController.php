<?php

namespace App\Http\Controllers\User;

use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{

    public function index()
    {
        return view('user.keranjang', [
            'keranjangs' => Cart::getCartUser()
        ]);
    }

    public function deleteItem(Keranjang $keranjang)
    {
        $keranjang->delete();
        return $this->backWithAlert('success', 'Berhasil menghapus item dari keranjang!');
    }

    public function checkout()
    {
        return view('user.checkout');
    }
}
