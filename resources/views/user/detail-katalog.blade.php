@extends('layouts.main', ['title' => 'Katalog'])
@section('content')

    <section class="section">
        <div class="container my-5" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-4 align-items-start katalog-detail-wrapper">

                <!-- Gambar Kiri (Hilang di HP) -->
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="katalog-image-box">
                        <img src="{{ $katalog->link_thumbnail_katalog }}" alt="Gambar Katalog {{ $katalog->nama_katalog }}"
                            class="img-fluid rounded-4 shadow-sm">
                    </div>
                </div>

                <!-- Detail Katalog -->
                <div class="col-12 col-lg-7">
                    <div class="katalog-detail-card p-4 shadow-sm rounded-4">

                        <h1 class="fw-bold mb-2">{{ $katalog->nama_katalog }}</h1>

                        <div class="harga-wrapper mb-3">
                            @isset($katalog->diskon_katalog)
                                <span class="harga-asli">{{ $katalog->formatRupiah() }}</span>
                                <span class="harga-diskon ms-2">{{ $katalog->getHargaAfterDiskon() }}</span>
                                <span class="badge text-bg-success ms-2">DISKON {{ $katalog->diskon_katalog }}%</span>
                            @else
                                <span class="harga-diskon ms-2">{{ $katalog->formatRupiah() }}</span>
                            @endisset
                        </div>

                        <p class="text-muted mb-4">
                            {{ $katalog->deskripsi_katalog }}
                        </p>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">Include:</h5>
                            <ul>
                                @foreach ($katalog->item_array_katalog as $key => $in)
                                    <li>{{ $in }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">Pilih Item:</h5>
                            <div class="accordion" id="accordionExample">
                                @foreach ($katalog->data_vendor_array_katalog as $key => $v)
                                    @php
                                        // Default
                                        $fieldName = 'nama_' . $key;
                                        $fotoName = 'link_foto_' . $key;

                                        // Semua kategori gaun pakai field dan foto yang sama
                                        $kategoriGaun = [
                                            'gaun_pengantin',
                                            'gaun_pengantin_temu',
                                            'gaun_pengantin_akad',
                                            'gaun_pengantin_resepsi',
                                        ];

                                        if (in_array($key, $kategoriGaun)) {
                                            $fieldName = 'nama_gaun';
                                            $fotoName = 'link_foto_gaun';
                                        }
                                    @endphp

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ $key }}" aria-expanded="false"
                                                aria-controls="{{ $key }}">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}
                                            </button>
                                        </h2>

                                        <div id="{{ $key }}" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <select class="form-control pilih-item">
                                                        @foreach ($v as $item)
                                                            <option data-id="{{ $key }}"
                                                                data-foto-url="{{ $item->$fotoName }}"
                                                                value="{{ $item->id }}">
                                                                {{ $item->$fieldName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div id="parent_foto_{{ $key }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        @if (Auth::check())
                            <div class="row">
                                @if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')
                                    <div class="col-6">
                                        <button id="bookingNow" type="button" class="btn btn-success btn-lg w-100 mt-3">
                                            Booking Sekarang
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button id="addTocart" type="button"
                                            class="btn btn-warning text-white btn-lg w-100 mt-3">
                                            Masukan Keranjang
                                        </button>

                                    </div>
                                @endif
                            </div>
                        @else
                            <a id="btnLogin" href="{{ route('login') }}" class="btn btn-success btn-lg w-100 mt-3">
                                Booking Sekarang
                            </a>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        // Object untuk menyimpan state pilihan user
        let selectedItems = {};

        $('#addTocart').click(function() {
            $.ajax({
                url: `{{ route('user.katalog.add_to_cart') }}`,
                method: 'POST',
                data: {
                    ...selectedItems,
                    _token: `{{ csrf_token() }}`,
                    id_katalog: `{{ $katalog->id }}`
                },
                success: function(res) {
                    if (res.success) {
                        alertToast({
                            type: 'success',
                            message: res.message
                        })
                        window.location.reload();
                    }
                },
                error: function(err) {
                    alertToast({
                        type: 'danger',
                        message: err.responseJSON.message
                    })

                }
            })
        })

        // Loop semua select untuk initial value
        $('.pilih-item').each(function() {
            let firstOption = $(this).find('option:first');
            let key = firstOption.data('id');
            let foto = firstOption.data('foto-url');
            let itemId = firstOption.val();

            selectedItems[key] = itemId;

            $(`#parent_foto_${key}`).html(`<img src="${foto}" width="20%" class="rounded-3 mt-2"/>`);
        });

        // Event ketika dropdown berubah
        $(document).on('change', '.pilih-item', function() {
            let key = $(this).find(':selected').data('id');
            let foto = $(this).find(':selected').data('foto-url');
            let itemId = $(this).val();

            selectedItems[key] = itemId;

            $(`#parent_foto_${key}`).html(`<img src="${foto}" width="20%" class="rounded-3 mt-2"/>`);
        });

        // Klik login: save state dan redirect
        $('#btnLogin').on('click', function(e) {
            e.preventDefault();

            // Simpan pilihan item ke localStorage
            localStorage.setItem('selected_items', JSON.stringify(selectedItems));

            // Simpan URL current page
            localStorage.setItem('redirect_after_login', window.location.href);

            // Redirect ke login
            window.location.href = $(this).attr('href');
        });

        $('#bookingNow').on('click', function() {
            const params = new URLSearchParams({
                selected: JSON.stringify(selectedItems)
            });

            window.location.href =
                "{{ route('user.katalog.booking', ['id_katalog' => $katalog->id]) }}" +
                "&" + params.toString();

        });
        let savedSelections = localStorage.getItem('selected_items');
        if (savedSelections) {
            savedSelections = JSON.parse(savedSelections);

            $('.pilih-item').each(function() {
                let key = $(this).find('option:first').data('id');
                if (savedSelections[key]) {
                    $(this).val(savedSelections[key]).trigger('change');
                }
            });

            localStorage.removeItem('selected_items');
        }
    </script>
@endpush
