<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('isCurrentUrl')) {
  function isCurrentUrl($currentRouteName)
  {
    $route = Route::currentRouteName();
    if ($route === $currentRouteName) {
      return "active";
    }
    return "";
  }
}

if (!function_exists('isRoutePrefix')) {
  function isRoutePrefix($currentRouteName)
  {
    $route = Route::is($currentRouteName);
    if ($route) {
      return "active";
    } else {
      return "";
    }
  }
}


if (!function_exists('getDataJson')) {
  function getDataJson()
  {
    $file = file_get_contents(public_path('data.json'));
    if (!file_exists(public_path('data.json'))) {
      return new Exception("File Tidak JSON Ditemukan!", 40004);
    }
    return json_decode($file, true);
  }
}
if (!function_exists('convertStatusTransaksiMidtrans')) {
  function convertStatusTransaksiMidtrans($status)
  {
    return match ($status) {
      'settlement' => 'lunas',
      'pending' => 'menunggu_pembayaran',
      'cancel' => 'batal',
    };
  }
}
