@extends('layouts.main', ['title' => 'Katalog'])
@section('content')
    <section class="section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Katalog</h2>
            <p>Daftar layanan yang kami sediakan</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                @foreach ($katalog as $item)
                    <div class="col-12 col-md-4 mx-1 my-3">
                        <div class="card-katalog">
                            <div class="katalog-img" style="background-image: url('{{ $item->link_thumbnail_katalog }}')">
                                @isset($item->diskon_katalog)
                                    <div class="katalog-diskon-badge">
                                        Diskon: {{ $item->diskon_katalog }}%
                                    </div>
                                @endisset
                            </div>
                            <div class="katalog-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('user.katalog.show', $item->id) }}">
                                        <h5 class="card-title fw-bold text-truncate">
                                            {{ $item->nama_katalog }}
                                        </h5>
                                    </a>
                                </div>
                                @isset($item->diskon_katalog)
                                    <span class="harga-asli">{{ $item->formatRupiah() }}</span>
                                    <span class="harga-diskon ms-2">{{ $item->getHargaAfterDiskon() }}</span>
                                @else
                                    <span class="harga-diskon ms-2">{{ $item->formatRupiah() }}</span>
                                @endisset
                                <p class="text-muted small mb-2 description-text">{{ $item->deskripsi_katalog }}</p>

                            </div>
                            <div class="katalog-footer d-grid grid-2">
                                <a href="{{ route('user.katalog.show', $item->id) }}"
                                    class="btn btn-success btn-block">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>
                {{ $katalog->links() }}
            </div>
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
    <script>
        let selectedItems = {},
            detail_item = {},
            detail_keranjang = null;

        $('#modal-detail-item').on('shown.bs.modal', function() {
            $('.btn-fab-wrapper').addClass('d-none')
        })

        function addToCart(data) {
            const {
                data_vendor_array_katalog,
                nama_katalog,
                id
            } = JSON.parse(data);
            $('#modal-detail-item').modal('show');

            $('#modal-title-detail-item').empty()
            const body = $('#body-modal-detail-item');
            body.empty();
            $('#modal-title-detail-item').text(`Detail Item ${nama_katalog}`)


            const accordion = $('<div class="accordion" id="accordionExample"></div>');

            // Loop data vendor (mirip blade)
            Object.keys(data_vendor_array_katalog).forEach(key => {
                const items = data_vendor_array_katalog[key];

                // Atur nama field
                let fieldName = "nama_" + key;
                let fotoName = "link_foto_" + key;
                if (key === "gaun_pengantin") {
                    fieldName = "nama_gaun";
                    fotoName = "link_foto_gaun";
                }

                const titleText = key.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());

                // Accordion item
                const accItem = $(`
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#${key}"
                        aria-expanded="false" aria-controls="${key}">
                        ${titleText}
                    </button>
                </h2>

                <div id="${key}" class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="mb-3">
                            <select class="form-control pilih-item" data-group="${key}">
                            </select>
                        </div>
                        <div id="parent_foto_${key}"></div>
                    </div>
                </div>
            </div>
        `);

                // Isi select option item
                const select = accItem.find("select");

                items.forEach(item => {
                    select.append(`
                <option
                    value="${item.id}"
                    data-id="${key}"
                    data-foto-url="${item[fotoName]}"
                >
                    ${item[fieldName]}
                </option>
            `);
                });

                accordion.append(accItem);
            });

            // Masukkan accordion ke modal body
            body.append(accordion);
            initFotoItem();
            // Tombol footer
            const footer = $('#footer-modal-detail-item');
            footer.empty();

            if (detail_keranjang === null) {
                footer.append(
                    $('<button>').addClass('btn btn-success').text('Masukan Keranjang').click(function() {
                        checkout({
                            ...selectedItems,
                            id_katalog: id
                        })
                    })
                );
            }

        }

        function initFotoItem() {
            $('.pilih-item').each(function() {
                let firstOption = $(this).find('option:first');
                let key = firstOption.data('id');
                let foto = firstOption.data('foto-url');
                let itemId = firstOption.val();
                selectedItems[key] = itemId;
                $(`#parent_foto_${key}`).html(
                    `<img src="${foto}" width="20%" class="rounded-3 mt-2"/>`
                );
            });
        }
        $(document).on('change', '.pilih-item', function() {
            let key = $(this).find(':selected').data('id');
            let foto = $(this).find(':selected').data('foto-url');
            let itemId = $(this).val();
            selectedItems[key] = itemId;
            $(`#parent_foto_${key}`).html(`<img src="${foto}" width="20%" class="rounded-3 mt-2"/>`);
        });

        function ad() {
            $.ajax({
                url: '{{ route('user.katalog.add.to.cart') }}',
                method: "post",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id_katalog: idKatalog
                },
                beforeSend: function() {
                    $('#loaderAddToCart').removeClass('d-none')
                    $('#iconAddToCart').addClass('d-none')
                },
                success: function(res) {
                    if (res.berhasil) {
                        $('.icon-keranjang').text(res.total_item_keranjang)
                        alertToast({
                            type: "success",
                            message: res.message
                        });
                    }
                },
                complete: function() {
                    $('#loaderAddToCart').addClass('d-none')
                    $('#iconAddToCart').removeClass('d-none')
                },
                error: function(err) {
                    if (err.status === 500) {
                        const error = err.responseJSON.error_message;
                        alertToast({
                            type: "danger",
                            message: error
                        })
                    }
                }
            })
        }

        function checkout(data) {
            $.ajax({
                url: `{{ route('user.katalog.add_to_cart') }}`,
                method: 'POST',
                data: {
                    ...data,
                    _token: `{{ csrf_token() }}`
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
        }

        $('#modal-detail-item').on('hidden.bs.modal', function() {
            $('.btn-fab-wrapper').removeClass('d-none')
        })
    </script>
@endpush
