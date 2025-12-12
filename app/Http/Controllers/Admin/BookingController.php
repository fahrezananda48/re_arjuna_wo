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
}
