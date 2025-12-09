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
                            <div class="cart-card p-3 mb-3">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-3">
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
                                <strong>{{ $keranjangs->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Diskon</span>
                                <strong>{{ Cart::getTotalDiskonAmount() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Harga</span>
                                <strong class="text-primary">{{ Cart::getTotalHarga() }}</strong>
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
    <div class="modal fade" id="modal-detail-item" tabindex="-1" role="dialog" aria-labelledby="modal-title-detail-item"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-detail-item"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="body-modal-detail-item"></div>
                <div class="modal-footer" id="footer-modal-detail-item">
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script type="text/javascript"
        src="{{ env('MIDTRANS_IS_PRODUCTION') === 'true' ? env('MIDTRANS_SNAP_JS_PRODUCTION_URL') : env('MIDTRANS_SNAP_JS_SANDBOX_URL') }}"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        let selectedItems = {},
            detail_item = {};
        $('#modal-detail-item').on('shown.bs.modal', function() {
            $('.btn-fab-wrapper').addClass('d-none')
        })



        $('#btn-checkout').click(function() {
            const totalHarga = `{{ Cart::getTotalHargaNumeric() }}`
            $.ajax({
                url: `{{ route('user.keranjang.checkout.proses') }}`
                method: "POST",
                data: {
                    _token: `{{ csrf_token() }}`,
                    total_harga: totalHarga
                },
                success: function(res) {

                }
            })


        })

        $('#modal-detail-item').on('hidden.bs.modal', function() {
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
    </style>
@endpush
