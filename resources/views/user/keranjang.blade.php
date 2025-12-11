@extends('layouts.main', ['title' => 'Keranjang'])

@section('content')
    <section class="section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Keranjang</h2>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            {{-- Jika keranjang kosong --}}
            @if ($keranjangs->isEmpty())
                <div class="empty-cart-box text-center">
                    <h1 class="fw-bold text-danger">Keranjang Anda Masih Kosong!</h1>
                    <p class="text-muted">Yuk mulai belanja dulu, biar halaman ini ada isinya.</p>
                    <a href="{{ route('user.katalog.index') }}" class="btn btn-primary mt-3 px-4 py-2">Mulai Belanja</a>
                </div>
            @else
                <div class="row g-4 mb-5">

                    {{-- Daftar Item --}}
                    <div class="col-lg-8">
                        @foreach ($keranjangs as $item)
                            <div onclick="toggle('{{ $item }}')" role="button" for="checkbox-{{ $item->id }}"
                                class="cart-card p-3 mb-3">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-1 d-none d-md-block">
                                        <input type="checkbox" id="checkbox-{{ $item->id }}"
                                            data-id="{{ $item->id }}" class="checkbox checkbox-{{ $item->id }}">
                                    </div>
                                    <div class="col-md-2">
                                        <img src="{{ $item->katalog->link_thumbnail_katalog ?? '/placeholder.jpg' }}"
                                            class="cart-item-img">
                                    </div>

                                    <div class="col-md-8">
                                        <h5 class="fw-bold">{{ $item->katalog->nama_katalog }}</h5>
                                        <p class="text-muted mb-1">{{ Str::limit($item->katalog->deskripsi_katalog, 80) }}
                                        </p>
                                        <span
                                            class="fw-bold {{ $item->katalog->diskon_katalog ? 'harga-asli' : 'text-primary' }} ">
                                            {{ $item->katalog->harga_rupiah }}
                                        </span>
                                        @if ($item->katalog->diskon_katalog)
                                            <span class="fw-bold  harga-diskon ms-1">
                                                {{ $item->katalog->gethargaAfterDiskon() }}
                                            </span>
                                            <span class="fw-bold ms-2">
                                                Diskon: {{ $item->katalog->diskon_katalog }}%
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-1 text-end">

                                        <form action="{{ route('user.keranjang.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-block d-md-none">
                                                <input id="checkbox-{{ $item->id }}" type="checkbox"
                                                    data-id="{{ $item->id }}"
                                                    class="checkbox checkbox-{{ $item->id }}">
                                            </div>
                                            <button class="btn-remove" title="Hapus dari keranjang">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total Harga --}}
                    <div class="col-lg-4">
                        <div class="cart-total-box mb-4">
                            <h5 class="fw-bold mb-3">Ringkasan Pembayaran</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Item</span>
                                <div>
                                    <div class="spinner-border text-primary spinner-border-sm d-none loader-pembayaran"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <strong id="total-item">-</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Diskon</span>
                                <div>
                                    <div class="spinner-border text-primary spinner-border-sm d-none loader-pembayaran"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <strong id="total-diskon">-</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Harga</span>
                                <div>
                                    <div class="spinner-border text-primary spinner-border-sm d-none loader-pembayaran"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <strong id="total-harga" class="text-primary">-</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Pilih Pembayaran DP</label>
                                <select id="dp-option" class="form-select">
                                    <option value="">-- Pilih DP --</option>
                                    <option value="25">DP 25%</option>
                                    <option value="50">DP 50%</option>
                                    <option value="75">DP 75%</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <span>Total DP</span>
                                <strong id="total-dp" class="text-warning">-</strong>
                            </div>


                            <hr>
                            <button type="button" id="btn-checkout" class="btn btn-success w-100 py-2 fw-bold mt-2">
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </section>
@endsection

@push('utils')
    {{-- Modal Detail Item --}}
    <div class="modal fade" id="modal-detail-customer" tabindex="-1" role="dialog"
        aria-labelledby="title-modal-detail-customer" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form method="POST" id="form-submit-pembayaran" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal-detail-customer">Form Data Diri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap CPP <small class="text-danger">*</small></label>
                        <input type="text" class="form-control" placeholder="Masukan nama lengkap calon pengantin pria"
                            name="nama_cpp" id="nama_cpp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap CPW <small class="text-danger">*</small></label>
                        <input type="text" class="form-control"
                            placeholder="Masukan nama lengkap calon pengantin wanita" name="nama_cpw" id="nama_cpw">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Ayah <small class="text-danger">*</small></label>
                        <input type="text" class="form-control"
                            placeholder="Masukan nama lengkap ayah calon pengantin wanita" name="nama_ayah"
                            id="nama_ayah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Ibu <small class="text-danger">*</small></label>
                        <input type="text" class="form-control"
                            placeholder="Masukan nama lengkap ibu calon pengantin wanita" name="nama_ibu" id="nama_ibu">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Acara <small class="text-danger">*</small></label>
                        <input type="date" class="form-control" placeholder="Masukan tanggal acara"
                            name="tanggal_acara" id="tanggal_acara">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telpon <small class="text-danger">*</small></label>
                        <input type="number" placeholder="Masukan nomor telpon anda" class="form-control"
                            name="nomor_telp_customer" id="nomor_telp_customer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap <small class="text-danger">*</small></label>
                        <textarea placeholder="Masukan alamat lengkap anda" class="form-control" name="alamat_lengkap_customer"
                            id="alamat_lengkap_customer"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('js')
    <script>
        let selectedItems = {},
            checked_item = [],
            detail_item = {};

        $('#modal-detail-customer').on('shown.bs.modal', function() {
            $('.btn-fab-wrapper').addClass('d-none')
        })

        function updateDP() {
            const dp = $('#dp-option').val();

            let totalHargaText = $('#total-harga').text();
            totalHargaText = totalHargaText.replace(/\D/g, ''); // tetap angka doang

            if (!dp || totalHargaText === '-' || totalHargaText === '') {
                $('#total-dp').text('-');
                return;
            }

            const total = parseInt(totalHargaText);
            const nilaiDP = total * (dp / 100);

            $('#total-dp').text(
                nilaiDP.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                })
            );
        }

        $('#dp-option').on('change', function() {
            if (checked_item.length === 0) {
                alertToast('danger', "Silahkan pilih item dahulu!");
                return;
            }
            const totalHarga = $('#total-harga').text();
            const totalItem = $('#total-item').text();
            const totalDiskon = $('#total-diskon').text();
            if (totalHarga === '-' || totalDiskon === '-' || totalItem === '-') {
                alertToast('danger', "Tunggu sebentar!")
                return;
            }
            updateDP()
        });

        $('#btn-dp').click(function() {
            if (checked_item.length === 0) {
                alertToast('danger', "Silahkan pilih item dahulu!");
                return;
            }

            const dp = $('#dp-option').val();

            if (!dp) {
                alertToast('danger', "Pilih nominal DP dulu!");
                return;
            }

            $('#modal-detail-customer').data('mode', 'dp'); // tandai mode DP
            $('#modal-detail-customer').modal('show');

        })

        // Fungsi khusus hitung total harga berdasarkan item terpilih
        function updateTotal() {
            $.ajax({
                url: `{{ route('user.keranjang.hitung') }}`,
                method: 'POST',
                data: {
                    checked_item: checked_item,
                    _token: `{{ csrf_token() }}`
                },
                beforeSend: function() {
                    $('.loader-pembayaran').removeClass('d-none')
                    $('#total-item').text('');
                    $('#total-harga').text('');
                    $('#total-diskon').text('');
                },
                success: function(res) {
                    $('#total-item').text(res.total_item);
                    $('#total-harga').text(res.total_harga);
                    $('#total-diskon').text(res.total_diskon);
                    updateDP()
                },
                complete: function() {
                    $('.loader-pembayaran').addClass('d-none')
                }
            });
        }

        function toggle(data) {
            const {
                id
            } = JSON.parse(data)
            const toggled = $(`.checkbox-${id}`).is(':checked');

            if (toggled) {
                $(`.checkbox-${id}`).prop('checked', false);
                checked_item = checked_item.filter(item => item !== id);
            } else {
                $(`.checkbox-${id}`).prop('checked', true);
                if (!checked_item.includes(id)) {
                    checked_item.push(id);
                }
            }

            updateTotal();
        }

        $('.checkbox').on('change', function() {
            const toggled = $(this).is(':checked');
            const id = $(this).data('id')

            if (toggled) {
                if (!checked_item.includes(id)) {
                    checked_item.push(id);
                }
            } else {
                checked_item = checked_item.filter(item => item !== id);
            }

            updateTotal();
        });


        $('#btn-checkout').click(function() {
            if (checked_item.length === 0) {
                alertToast('danger', "Silahkan pilih item dahulu!")
                return;
            }
            const totalHarga = $('#total-harga').text();
            const totalItem = $('#total-item').text();
            const totalDiskon = $('#total-diskon').text();
            const dp = $('#dp-option').val();
            if (totalHarga === '-' || totalDiskon === '-' || totalItem === '-') {
                alertToast('danger', "Tunggu sebentar!")
                return;
            }
            if (dp) {
                $('#modal-detail-customer').data('mode', 'dp');
            } else {
                $('#modal-detail-customer').data('mode', 'full');
            }

            $('#modal-detail-customer').modal('show')
        })

        $('#form-submit-pembayaran').submit(function(e) {
            e.preventDefault();
            const nama_cpp = $('#nama_cpp').val();
            const nama_cpw = $('#nama_cpw').val();
            const nama_ayah = $('#nama_ayah').val();
            const nama_ibu = $('#nama_ibu').val();
            const tanggal_acara = $('#tanggal_acara').val();
            const nomor_telp_customer = $('#nomor_telp_customer').val();
            const alamat_lengkap_customer = $('#alamat_lengkap_customer').val();
            const mode = $('#modal-detail-customer').data('mode') ?? 'full';
            if (!nama_cpp || !nama_cpw || !nama_ayah || !nama_ibu || !tanggal_acara ||
                !nomor_telp_customer || !alamat_lengkap_customer) {
                alertToast('danger', "Silahkan lengkapi dahulu informasi data diri anda!")
                return;
            }

            const datas = {
                checked_item: checked_item,
                nama_ayah: nama_ayah,
                nama_ibu: nama_ibu,
                nama_cpp: nama_cpp,
                nama_cpw: nama_cpw,
                tanggal_acara: tanggal_acara,
                nomor_telp_customer: nomor_telp_customer,
                alamat_lengkap_customer: alamat_lengkap_customer,
                dp_persen: mode === 'dp' ? $('#dp-option').val() : null,
                total_dp: mode === 'dp' ? $('#total-dp').text() : null,
                _token: `{{ csrf_token() }}`
            };

            $.ajax({
                url: `{{ route('user.keranjang.booking') }}`,
                method: 'post',
                data: datas,
                success: function(res) {
                    if (res.success) {
                        let url =
                            `{{ route('user.keranjang.pembayaran', [
                                'booking' => ':booking',
                            ]) }}`;

                        url = url.replace(':booking', res.booking);
                        window.location.href = url;
                    }
                },
                error: function(err) {
                    if (err.status === 500) {
                        const errException = err.responseJSON.message;
                        if (errException) {
                            alertToast('danger', err.responseJSON.message)
                            return
                        }
                        alertToast('danger', err.responseJSON.error)
                    }
                    if (err.status === 422) {
                        alertToast('danger', err.responseJSON.message)
                    }
                }
            })
        });

        $('#modal-detail-customer').on('hidden.bs.modal', function() {
            $('.btn-fab-wrapper').removeClass('d-none')
        })
    </script>
@endpush


@push('css')
    <style>
        /* Custom CSS biar makin elegan dan modern */

        .cart-card {
            border-radius: 18px;
            transition: 0.2s ease-in-out;
            border: none;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .cart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.12);
        }

        .cart-item-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
        }

        .empty-cart-box {
            padding: 60px 20px;
            background: #fafafa;
            border-radius: 20px;
            border: 2px dashed #ddd;
        }

        .btn-remove {
            border: none;
            background: transparent;
            color: #dc3545;
            font-size: 1.2rem;
            transition: 0.2s;
        }

        .btn-remove:hover {
            color: #a71d2a;
        }

        .cart-total-box {
            border-radius: 16px;
            padding: 20px;
            background: #f8f9fa;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .checkbox {
            width: 25px;
            height: 25px;
            transform: translateX(10px);
            border: 1px solid red
        }
    </style>
@endpush
