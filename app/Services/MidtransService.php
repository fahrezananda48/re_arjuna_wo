<?php

namespace App\Services;

use GuzzleHttp\Client;

class MidtransService
{
  protected static Client $client;
  protected static string $baseUrl;
  protected static bool $initialized = false;

  protected static function init(): void
  {
    if (static::$initialized) return;

    static::$baseUrl = config('services.midtrans.is_production')
      ? config('services.midtrans.production_snap_url')
      : config('services.midtrans.sandbox_snap_url');

    static::$client = new Client([
      'headers' => [
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
        'Authorization' => 'Basic ' . base64_encode(config('services.midtrans.server_key') . ':'),
      ]
    ]);

    static::$initialized = true;
  }

  public static function getSnapToken(array $data)
  {
    static::init(); // penting bre ↓↓↓ (biar client dan baseUrl siap)

    try {
      $response = static::$client->post(static::$baseUrl, [
        'json' => [
          'transaction_details' => [
            'order_id'      => $data['order_id'],
            'gross_amount'  => $data['gross_amount']
          ],
          'customer_details' => $data['customer_details']
        ]
      ]);

      return json_decode($response->getBody()->getContents(), true);
    } catch (\GuzzleHttp\Exception\ClientException $th) {
      return $th;
    }
  }
}
