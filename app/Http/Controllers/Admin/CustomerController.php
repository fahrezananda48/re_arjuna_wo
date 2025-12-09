<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customer.index', [
            'customer' => Client::paginate(25)
        ]);
    }

    public function create()
    {
        return view('admin.customer.form', [
            'type' => 'add'
        ]);
    }

    public function show(Client $customer)
    {
        return view('admin.customer.form', [
            'type' => 'update',
            'customer' => $customer
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_customer' => 'required',
            'nomor_telp_customer' => 'required|min_digits:9|max_digits:14',
            'alamat_lengkap_customer' => 'nullable',
        ]);

        try {
            Client::create($data);
            return $this->toWithAlert(
                'admin.customer.index',
                [],
                'success',
                "Berhasil menambahkan data customer"
            );
        } catch (\Exception $th) {
            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    public function update(Client $customer, Request $request)
    {
        $data = $request->validate([
            'nama_customer' => 'required',
            'nomor_telp_customer' => 'required|min_digits:9|max_digits:14',
            'alamat_lengkap_customer' => 'nullable',
        ]);

        try {
            $customer->update($data);
            return $this->toWithAlert(
                'admin.customer.index',
                [],
                'success',
                "Berhasil mengupdate data customer"
            );
        } catch (\Exception $th) {
            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    public function destroy(Client $customer)
    {
        try {
            $customer->delete();
            return $this->toWithAlert(
                'admin.customer.index',
                [],
                'success',
                "Berhasil menghapus data customer"
            );
        } catch (\Exception $th) {
            return $this->backWithAlert('danger', $th->getMessage());
        }
    }
}
