<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handler()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $order_id = $input['order_id'];
        $status_code = $input['status_code'];
        $gross_amount = $input['gross_amount'];
        $server_key = env('MIDTRANS_SERVER_KEY');
        $signature_key = $input['signature_key'];

        $build_payload = $order_id . $status_code . $gross_amount . $server_key;
        $own_sig = openssl_digest($build_payload, 'sha512');

        if ($signature_key === $own_sig) {
            $transaksi = Transaksi::where('kode_transaksi', $order_id)->first();

            if (empty($transaksi->total_dp)) {
                $transaksi->total_transaksi = $gross_amount;
                $transaksi->status_transaksi = convertStatusTransaksiMidtrans($input['transaction_status']);
                $transaksi->save();
            } else {
                $transaksi->total_dp = $gross_amount;
                $transaksi->status_transaksi = 'dp';
                $transaksi->save();
            }
        }

        return response()->json('ok');
    }
}
