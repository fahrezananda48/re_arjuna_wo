<?php

namespace App\Http\Controllers\User;

use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

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

    public function hitung(Request $request)
    {
        $ids = $request->checked_item ?? [];

        if (empty($ids)) {
            return response()->json([
                'total_item' => 0,
                'total_harga' => 0,
                'total_diskon' => 0
            ]);
        }

        $items = Keranjang::whereIn('id', $ids)->get();

        $totalHarga = 0;
        $totalDiskon = 0;

        foreach ($items as $item) {
            $hargaAsli = $item->katalog->harga_katalog;
            $discount = $item->katalog->diskon_katalog;

            if ($discount) {
                $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $discount / 100);
                $totalDiskon += ($hargaAsli - $hargaSetelahDiskon);
                $totalHarga += $hargaSetelahDiskon;
            } else {
                $totalHarga += $hargaAsli;
            }
        }

        return response()->json([
            'total_item' => count($ids),
            'total_harga' => Number::currency($totalHarga, 'IDR', 'ID', 0),
            'total_diskon' => Number::currency($totalDiskon, 'IDR', 'ID', 0)
        ]);
    }


    public function booking(Request $request)
    {
        $data = $request->validate([
            'checked_item' => 'required|array',
            'checked_item.*' => 'integer|exists:tbl_keranjang,id',
            'nama_cpp' => 'required|string',
            'nama_cpw' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'tanggal_acara' => 'required|date',
            'nomor_telp_customer' => 'required|string',
            'alamat_lengkap_customer' => 'required|string',
            'dp_persen' => 'nullable|numeric',
            'total_dp' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Batasi kuota tanggal: max 2
            if (Booking::whereDate('tanggal_acara', $data['tanggal_acara'])->count() >= 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal sudah penuh!, Silahkan pilih tanggal lain'
                ], 422);
            }

            // Ambil keranjang + katalog sekaligus
            $keranjangs = Keranjang::with('katalog')
                ->whereIn('id', $data['checked_item'])
                ->get();

            if ($keranjangs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item keranjang tidak ditemukan'
                ], 404);
            }

            // Helper untuk hitung harga satu item
            $sumHarga = fn($k) => !empty($k->katalog->diskon_katalog)
                ? $k->katalog->harga_after_diskon
                : $k->katalog->harga_katalog;

            // Hitung total harga
            $totalTransaksi = $keranjangs->sum($sumHarga);

            // Jika pakai DP
            if (!empty($data['dp_persen']) && !empty($data['total_dp'])) {
                $totalHarga = $this->clearAngka($data['total_dp']);
                $statusTransaksi = 'dp';
            } else {
                $totalHarga = $totalTransaksi;
                $statusTransaksi = 'menunggu_pembayaran';
            }

            $first = $keranjangs->first();

            // Invoice ID
            $inv = "inv-" . Str::slug($data['nama_cpp']) . '-' .
                Str::slug($data['nama_cpw']) . "-" . Str::random(10);

            // Data untuk midtrans
            $dataCharge = [
                'order_id' => $inv,
                'gross_amount' => $totalHarga,
                'customer_details' => [
                    'first_name' => $data['nama_cpp'],
                    'last_name'  => $data['nama_cpw'],
                    'email'      => Str::slug($data['nama_cpp']) . '-' .
                        Str::slug($data['nama_cpw']) . "@gmail.com",
                    'phone'      => $data['nomor_telp_customer'],
                    'billing_address' => [
                        'address' => $data['alamat_lengkap_customer']
                    ]
                ]
            ];

            $snapToken = MidtransService::getSnapToken($dataCharge);

            if (!$snapToken || !isset($snapToken['token'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan token pembayaran'
                ], 500);
            }
            // Simpan booking
            $booking = Booking::create([
                'customer_id'      => $first->id_customer,
                'nama_cpp'         => $data['nama_cpp'],
                'nama_cpw'         => $data['nama_cpw'],
                'nama_ayah'        => $data['nama_ayah'],
                'nama_ibu'         => $data['nama_ibu'],
                'tanggal_acara'    => $data['tanggal_acara'],
                'alamat'           => $data['alamat_lengkap_customer'],
                'nomor_telp'       => $data['nomor_telp_customer'],
                'total_pembayaran' => $totalTransaksi,
                'katalog_id'       => $first->id_katalog,
                'detail_booking'   => [
                    'detail_booking' => $keranjangs->pluck('detail_keranjang')->toArray(),
                    'snap_token' => $snapToken['token']
                ]
            ]);
            Transaksi::create([
                'katalog_id'        => $first->id_katalog,
                'customer_id'       => $first->id_customer,
                'kode_transaksi'    => $inv,
                'total_transaksi'   => $totalTransaksi,
                'status_transaksi'  => $statusTransaksi,
                'total_dp'          => $totalHarga,
                'detail_transaksi'  => [
                    'snap_token' => $snapToken['token']
                ],
                'id_booking' => $booking->id
            ]);

            // Hapus keranjang milik customer
            Keranjang::whereIn('id', $data['checked_item'])
                ->where('id_customer', Auth::user()->customer->id)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken['token'],
                'booking' => $booking->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat proses booking',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function pembayaran(Booking $booking, Request $request)
    {
        return view('user.pembayaran', [
            'booking' => $booking,
            'snap_token' => $booking->detail_booking['snap_token']
        ]);
    }

    public function prosesCheckout(Request $request)
    {
        // 
    }
}
