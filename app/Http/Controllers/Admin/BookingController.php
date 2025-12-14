<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Transaksi::with('booking')->paginate(25);
        return view('admin.booking.index', [
            'booking' => $booking
        ]);
    }

    public function updateStatusTransaksi(Request $request)
    {
        $data = $request->validate([
            'id_transaksi' => 'required|exists:tbl_transaksi,id',
            'status_transaksi' => 'required|in:menunggu_pembayaran,dp,lunas,batal'
        ]);

        try {
            $transaksi = Transaksi::find($data['id_transaksi']);
            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'error_message' => "Data transaksi tidak ditemukan!"
                ], 404);
            }

            $transaksi->status_transaksi = $data['status_transaksi'];
            $transaksi->save();

            return response()->json([
                'success' => true,
                'success_message' => "Berhasil memperbarui status transaksi!"
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'error_message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        try {
            $transaksi->delete();
            return $this->backWithAlert('success', 'Berhasil menghapus data!');
        } catch (\Exception $th) {
            return $this->backWithAlert('danger', 'Terjadi kesalahan!');
        }
    }
}
