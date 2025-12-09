<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Katalog;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KatalogController extends Controller
{
    public function index()
    {
        return view('user.katalog', [
            'katalog' => Katalog::where('status_katalog', '<>', 'belum_aktif')->paginate(20)
        ]);
    }

    public function booking(Request $request)
    {
        return view('user.booking');
    }

    public function show(Katalog $katalog)
    {
        return view('user.detail-katalog', compact('katalog'));
    }

    public function tambahKeranjang(Request $request)
    {
        $detail_keranjang = $request->except(['_token', 'id_katalog']);


        try {
            Keranjang::create([
                'id_katalog' => $request->id_katalog,
                'id_customer' => Auth::user()->customer->id,
                'detail_keranjang' => $detail_keranjang
            ]);
            return response()->json([
                'success' => true,
                'message' => "Berhasil memasukan katalog ke keranjang!"
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function charge(Request $request)
    {
        $data = $request->validate([
            'id_katalog' => 'required|exists:tbl_katalog,id',
            'nama_cpp' => 'required',
            'nama_cpw' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'tanggal_acara' => 'required|date',
            'nomor_telp_customer' => 'required',
            'alamat_lengkap_customer' => 'required',
        ]);

        DB::beginTransaction(); // Mulai transaksi dari awal

        try {

            $bookings = Booking::whereDate('tanggal_acara', $data['tanggal_acara'])->get();
            if ($bookings->count() >= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal sudah penuh!'
                ], 422);
            }

            // Buat client baru
            $client = Client::updateOrCreate([
                'nomor_telp_customer' => $data['nomor_telp_customer'],
            ], [
                'nama_customer' => $data['nama_cpp'] . '-' . $data['nama_cpw'],
                'nomor_telp_customer' => $data['nomor_telp_customer'],
                'alamat_lengkap_customer' => $data['alamat_lengkap_customer'],
            ]);

            $booking = Booking::create([
                'customer_id' => $client->id,
                'nama_cpp' => $data['nama_cpp'],
                'nama_cpw' => $data['nama_cpw'],
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ayah'],
                'tanggal_acara' => $data['tanggal_acara'],
                'alamat' => $data['alamat_lengkap_customer'],
                'nomor_telp' => $data['nomor_telp_customer'],
            ]);

            // Ambil katalog
            $katalog = Katalog::findOrFail($data['id_katalog']);
            $inv  = "inv-" . str_replace(' ', '_', $data['nama_cpp']) . '-' . $data['nama_cpw'] . Str::random(10);
            $harga = 0;
            if (isset($katalog->diskon_katalog)) {
                $harga += $katalog->harga_after_diskon;
            } else {
                $harga += $katalog->harga_katalog;
            }
            // Data charge Midtrans
            $dataCharge = [
                'order_id' => $inv,
                'gross_amount' => $harga,
                'customer_details' => [
                    'first_name' => $client->nama_customer,
                    'last_name' => "",
                    'email' => Str::replace(' ', "-", $data['nama_cpp']) . '-' . $data['nama_cpw'] . "@gmail.com",
                    'phone' => $client->nomor_telp_customer,
                    'billing_address' => [
                        'address' => $client->alamat_lengkap_customer
                    ]
                ]
            ];

            // Ambil snap token
            $snapToken = MidtransService::getSnapToken($dataCharge);

            // Jika Midtrans error
            if ($snapToken instanceof \GuzzleHttp\Exception\ClientException) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'error'   => json_decode($snapToken->getResponse()->getBody()->getContents(), true)
                ], 500);
            }

            // Jika sukses → commit
            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'data_client' => $client
            ]);
        } catch (\Exception $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $filtered = array_diff_key($data, array_flip([
            'id_katalog',
            'id_client',
            '_token'
        ]));

        try {
            DB::beginTransaction();
            $transaksi = Transaksi::create([
                'katalog_id' => $data['id_katalog'],
                'customer_id' => $data['id_client'],
                'kode_transaksi' => $data['order_id'],
                'total_transaksi' => floatval($data['gross_amount']),
                'status_transaksi' => convertStatusTransaksiMidtrans($data['transaction_status']),
                'detail_transaksi' => $filtered,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'transaksi' => $transaksi
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ]);
        }
    }

    public function addTOCart(Request $request)
    {
        try {
            $idkatalog = $request->id_katalog;

            $keranjang = Keranjang::updateOrCreate([
                'id_katalog' => $idkatalog,
            ], [
                'id_customer' => Auth::user()->customer->id,
            ]);
            return response()->json([
                'berhasil' => true,
                'total_item_keranjang' => $keranjang->count(),
                'message' => "Berhasil menambahkan item ke keranjang"
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'berhasil' => false,
                'error_message' => $th->getMessage()
            ], 500);
        }
    }
}
