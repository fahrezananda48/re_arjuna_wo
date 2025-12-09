<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $beranda = $this->getDataJson();
        if ($beranda instanceof \Exception) {
            return $beranda->getMessage();
        }
        if (!is_array($beranda)) {
            return [];
        }
        $beranda = $beranda['beranda'];
        return view('user.beranda', compact('beranda'));
    }
}
