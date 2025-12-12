@extends('layouts.admin', ['title' => 'Data Booking'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data Booking</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.booking.create') }}" class="btn btn-sm btn-primary float-end">Tambah
                        Data Booking</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Acara</th>
                            <th>Nama CPP</th>
                            <th>Nama CPW</th>
                            <th>Nomor Telp</th>
                            <th>Alamat</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking as $item)
                            <tr role="button" onclick='openModalDetail(@json($item))'>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->booking->formatTanggal() }}</td>
                                <td>{{ $item->booking->nama_cpp }}</td>
                                <td>{{ $item->booking->nama_cpw }}</td>
                                <td>{{ $item->booking->nomor_telp }}</td>
                                <td>{{ $item->booking->alamat }}</td>
                                <td>{!! $item->statusTransaksiItem() !!}
                                </td>
                                <td width="10%">
                                    <div class="dropdown">
                                        <button onclick="event.stopPropagation()" type="button"
                                            class="btn btn-sm btn-warning dropdown-toggle" id="trigger-{{ $item->id }}"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div onclick="event.stopPropagation()" class="dropdown-menu"
                                            aria-labelledby="trigger-{{ $item->id }}">
                                            <button class="dropdown-item" href="#">Edit</button>
                                            <button class="dropdown-item" href="#">Delete</button>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>
        <div class="card-footer">{{ $booking->links() }}</div>
    </div>
@endsection

@push('utils')
    <!-- Modal -->
    <div class="modal fade" id="modalDetailTransaksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi & Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-4">
                        <h6 class="fw-bold">Informasi Transaksi</h6>
                        <table class="table table-bordered">
                            <tbody id="detail-transaksi-table">
                                <!-- JS will fill this -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Informasi Booking</h6>
                        <table class="table table-bordered">
                            <tbody id="detail-booking-table">
                                <!-- JS will fill this -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Detail Item Booking</h6>
                        <div id="detail-items" class="row g-3">
                            <!-- JS will fill the cards -->
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        function getStatusTransaksiBadge(status) {
            switch (status) {
                case 'menunggu_pembayaran':
                    return `<span class="badge rounded-pill text-bg-warning">MENUNGGU PEMBAYARAN</span>`
                case 'dp':
                    return `<span class="badge rounded-pill text-bg-info">DP</span>`
                case 'lunas':
                    return `<span class="badge rounded-pill text-bg-success">LUNAS</span>`
                case 'batal':
                    return `<span class="badge rounded-pill text-bg-danger">BATAL</span>`
                default:
                    return '<span class="badge rounded-pill text-bg-warning">MENUNGGU PEMBAYARAN</span>'
            }
        }

        function openModalDetail(raw) {
            const data = typeof raw === "string" ? JSON.parse(raw) : raw;

            const transaksi = data;
            const booking = data.booking;
            // -------------------------
            // Bagian 1: Tabel Transaksi
            // -------------------------
            const transaksiRows = `
            <tr><th style="width:30%">ID</th><td>${transaksi.id}</td></tr>
            <tr><th>Kode Transaksi</th><td>${transaksi.kode_transaksi}</td></tr>
            <tr><th>Status</th><td>${getStatusTransaksiBadge(transaksi.status_transaksi)}</td></tr>
            <tr><th>Total Transaksi</th><td>Rp ${Number(transaksi.total_transaksi).toLocaleString()}</td></tr>
            <tr><th>Total DP</th><td>${transaksi.total_dp ? 'Rp ' + Number(transaksi.total_dp).toLocaleString() : '-'}</td></tr>
            <tr><th>Snap Token</th><td>${transaksi.detail_transaksi.snap_token}</td></tr>
            <tr><th>Dibuat</th><td>${transaksi.created_at}</td></tr>
        `;
            document.getElementById("detail-transaksi-table").innerHTML = transaksiRows;

            // -------------------------
            // Bagian 2: Tabel Booking
            // -------------------------
            const bookingRows = `
            <tr><th>ID Booking</th><td>${booking.id}</td></tr>
            <tr><th>Nama CPP</th><td>${booking.nama_cpp}</td></tr>
            <tr><th>Nama CPW</th><td>${booking.nama_cpw}</td></tr>
            <tr><th>Nama Ayah</th><td>${booking.nama_ayah}</td></tr>
            <tr><th>Nama Ibu</th><td>${booking.nama_ibu}</td></tr>
            <tr><th>Nomor Telepon</th><td>${booking.nomor_telp}</td></tr>
            <tr><th>Alamat</th><td>${booking.alamat}</td></tr>
            <tr><th>Tanggal Acara</th><td>${booking.tanggal_acara}</td></tr>
            <tr><th>Total Pembayaran</th><td>Rp ${Number(booking.total_pembayaran).toLocaleString()}</td></tr>
        `;
            document.getElementById("detail-booking-table").innerHTML = bookingRows;

            // -------------------------
            // Bagian 3: Detail Item (Cards)
            // -------------------------
            const detailArray = booking.detail_booking_array || {};
            const container = document.getElementById("detail-items");
            container.innerHTML = "";

            Object.entries(detailArray).forEach(([key, items]) => {

                if (!items.length) {
                    container.innerHTML += `
                    <div class="col-12">
                        <div class="alert alert-secondary">Tidak ada data untuk ${key.replaceAll('_',' ')}</div>
                    </div>
                `;
                    return;
                }

                items.forEach(item => {
                    const fotoField = Object.keys(item).find(x => x.startsWith("link_foto"));
                    const namaField = Object.keys(item).find(x => x.startsWith("nama_"));

                    container.innerHTML += `
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <img src="${item[fotoField]}" class="card-img-top rounded" alt="${item[namaField]}" style="height:420px; object-fit:cover; object-position: center;">
                            <div class="card-body">
                                <p class="mb-1 fw-bold text-capitalize">${key.replaceAll("_", " ")}</p>
                                <p class="mb-0">${item[namaField]}</p>
                            </div>
                        </div>
                    </div>
                `;
                });
            });

            // -------------------------
            // Buka Modal
            // -------------------------
            const modal = new bootstrap.Modal('#modalDetailTransaksi');
            modal.show();
        }
    </script>
@endpush
