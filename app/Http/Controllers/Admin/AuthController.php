<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {

        return view('admin.login');
    }

    public function daftar()
    {

        return view('admin.daftar');
    }

    public function loginPost(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return $this->backWithAlert('danger', "Akun tidak ditemukan!");
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return $this->backWithAlert('danger', "Password salah!");
        }

        if (!Auth::attempt($validated)) {
            return $this->backWithAlert('danger', "Kredensial tidak sah!");
        }

        if ($user->role === 'admin') {
            return $this->toWithAlert('admin.beranda.index', [], 'success', "Selamat Datang {$user->nama}");
        }
        if ($user->role === 'super_admin') {
            return $this->toWithAlert('admin.beranda.index', [], 'success', "Selamat Datang {$user->nama}");
        }
        return $this->toWithAlert('user.beranda.index', [], 'success', "Selamat Datang {$user->nama}");
    }

    public function daftarPost(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required',
            'nomor_telp' => 'required',
            'password' => 'required',
            'alamat_lengkap' => 'required',
        ]);

        $user = User::create([
            'nama' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member'
        ]);

        Client::create([
            'nama_customer' => $validated['nama_lengkap'],
            'nomor_telp_customer' => $validated['nomor_telp'],
            'alamat_lengkap_customer' => $validated['alamat_lengkap'],
            'email' => $validated['email'],
        ]);

        Auth::login($user);

        return $this->toWithAlert('login', [], 'success', "Berhasil melakukan pendaftaran, Silahkan login!");
    }

    public function logout()
    {
        Auth::logout();

        return $this->toWithAlert(
            'login',
            [],
            'success',
            "Silahkan login kembali!"
        );
    }
}
