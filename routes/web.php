<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

// Routing User
Route::controller(User\BerandaController::class)
    ->as('user.beranda.')
    ->middleware('web')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::controller(User\PortofolioController::class)
    ->as('user.portofolio.')
    ->middleware('web')
    ->prefix('portofolio')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::controller(User\KeranjangController::class)
    ->as('user.keranjang.')
    ->middleware('web')
    ->prefix('keranjang')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/hapus/{keranjang}', 'deleteItem')->name('remove');
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout/proses', 'prosesCheckout')->name('checkout.proses');
    });

Route::controller(User\KatalogController::class)
    ->as('user.katalog.')
    ->middleware('web')
    ->prefix('katalog')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{katalog}', 'show')->name('show');
        Route::get('/booking', 'booking')->name('booking');
        Route::post('/charge', 'charge')->name('charge');
        Route::post('/store/data_transaksi', 'store')->name('store');
        Route::post('/tambah_keranjang', 'addToCart')->name('add.to.cart');
        Route::post('/tambah_keranjang_store', 'tambahKeranjang')->name('add_to_cart');
    });

Route::controller(User\AboutController::class)
    ->as('user.tentang.')
    ->middleware('web')
    ->prefix('tentang-kami')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });



// Routing Untuk Admin Panel
Route::controller(Admin\AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginPost')->name('login.post');
        Route::get('/daftar', 'daftar')->name('daftar');
        Route::post('/daftar', 'daftarPost')->name('daftar.post');
    });

// Route Admin
Route::prefix('admin')
    ->middleware('auth')
    ->as('admin.')
    ->group(function () {
        Route::controller(Admin\BerandaController::class)
            ->as('beranda.')
            ->prefix('beranda')
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });

        Route::controller(Admin\KatalogController::class)
            ->as('katalog.')
            ->prefix('katalog')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{katalog}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{katalog}/update', 'update')->name('update');
                Route::get('/{katalog}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\CustomerController::class)
            ->as('customer.')
            ->prefix('customer')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{customer}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{customer}/update', 'update')->name('update');
                Route::get('/{customer}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\Portofoliocontroller::class)
            ->as('portofolio.')
            ->prefix('portofolio')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{portofolio}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{portofolio}/update', 'update')->name('update');
                Route::get('/{portofolio}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\GaunPengantinController::class)
            ->as('gaun-pengantin.')
            ->prefix('gaun_pengantin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/fetch/data', 'getData')->name('getData');
                Route::get('/fetch/data/gaun_akad', 'getData')->name('getData.akad');
                Route::get('/fetch/data/gaun_resepsi', 'getData')->name('getData.resepsi');
                Route::get('/fetch/data/gaun_temu', 'getData')->name('getData.temu');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{gaun_pengantin}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{gaun_pengantin}/update', 'update')->name('update');
                Route::get('/{gaun_pengantin}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\DekorasiController::class)
            ->as('dekorasi.')
            ->prefix('dekorasi')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/fetch/data', 'getData')->name('getData');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{dekorasi}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{dekorasi}/update', 'update')->name('update');
                Route::get('/{dekorasi}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\TendaController::class)
            ->as('tenda.')
            ->prefix('tenda')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/fetch/data', 'getData')->name('getData');
                Route::get('/tambah', 'create')->name('create');
                Route::get('/{tenda}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('/{tenda}/update', 'update')->name('update');
                Route::get('/{tenda}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\TransaksiController::class)
            ->as('transaksi.')
            ->prefix('transaksi')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::post('/{transaksi}/update', 'update')->name('update');
                Route::delete('/{transaksi}/destroy', 'destroy')->name('destroy');
            });

        Route::controller(Admin\LaporanController::class)
            ->as('laporan.')
            ->prefix('laporan')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::post('/{laporan}/update', 'update')->name('update');
                Route::delete('/{laporan}/destroy', 'destroy')->name('destroy');
            });



        Route::get('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
    });
