<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Exception;
use Illuminate\Http\Request;

class PortofolioController extends Controller
{
    public function index()
    {
        $portofolio = $this->getDataJson();
        if ($portofolio instanceof Exception) {
            throw new Exception($portofolio->getMessage(), $portofolio->getCode());
        }
        if (!isset($portofolio['portofolio'])) {
            throw new Exception("Array Kunci Portofolio Tidak Ditemukan!", 40015);
        }
        $portofolio = $portofolio['portofolio'];

        $portofolios = Portofolio::all();

        return view('user.portofolio', compact('portofolio', 'portofolios'));
    }
}
