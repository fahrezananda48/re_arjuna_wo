<?php

namespace App\Enums;

enum StatusKatalogEnum: string
{
  case AKTIF = "aktif";
  case DISKON = 'diskon';
  case BELUM_AKTIF = 'belum_aktif';
}
