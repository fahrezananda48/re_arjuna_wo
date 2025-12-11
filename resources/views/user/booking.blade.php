@extends('layouts.main', ['title' => 'Booking'])
@section('content')
    <section class="section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Booking</h2>
            <p>Booking layanan kami sekarang</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="card p-4">
                <div class="steps mb-4">
                    <div class="step-item active" data-step="1">
                        <i class="bi bi-people"></i>
                        Data diri anda
                    </div>
                    <div class="step-item" data-step="2">
                        <i class="bi bi-bank2"></i>
                        Pembayaran
                    </div>
                    <div class="step-item" data-step="3">
                        <i class="bi bi-receipt-cutoff"></i>
                        Informasi
                    </div>
                </div>
                <form id="multiStepForm" method="get">

                    <!-- STEP 1 -->
                    <div class="step-content" data-step="1">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap CPP <small class="text-danger">*</small></label>
                            <input type="text" class="form-control"
                                placeholder="Masukan nama lengkap calon pengantin pria" name="nama_cpp" id="nama_cpp">
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
                                placeholder="Masukan nama lengkap ibu calon pengantin wanita" name="nama_ibu"
                                id="nama_ibu">
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
                        <button type="button" class="btn btn-primary" id="btn-submit-step-1">
                            <span class="spinner-border spinner-border-sm d-none" id="loaderStep1"
                                aria-hidden="true"></span>
                            <span role="status" id="textLoaderStep1">Lanjut</span>
                        </button>
                    </div>

                    <!-- STEP 2 -->
                    <div class="step-content d-none" data-step="2">
                        <div class="d-flex justify-content-center align-items-center">
                            <div id="snap-container" class="container-midtrans"></div>
                        </div>
                        <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                    </div>

                    <!-- STEP 3 -->
                    <div class="step-content d-none" data-step="3">
                        <div class="card shadow-sm border-0 rounded-3 mb-3">

                            <div class="card-body">

                                <!-- Order Summary -->
                                <div class="mb-4">
                                    <ul class="list-group small">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>ID Pesanan</span>
                                            <span class="fw-semibold" id="order-id"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Nama Customer</span>
                                            <span class="fw-semibold" id="customer-name"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Nomor Telepon</span>
                                            <span class="fw-semibold" id="customer-phone"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Pembayaran</span>
                                            <span class="fw-bold text-primary" id="total-amount"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Status Pembayaran</span>
                                            <span class="badge text-bg-secondary" id="status-pembayaran"></span>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </div>

                        <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script type="text/javascript"
        src="{{ env('MIDTRANS_IS_PRODUCTION') === 'true' ? env('MIDTRANS_SNAP_JS_PRODUCTION_URL') : env('MIDTRANS_SNAP_JS_SANDBOX_URL') }}"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        $(document).ready(function() {

            function updateUrl(step) {
                const url = new URL(window.location.href);
                url.searchParams.set("step", step);
                window.history.replaceState({}, "", url); // tidak reload halaman
            }

            function goToStep(step) {
                $(".step-content").addClass("d-none");
                $(`.step-content[data-step="${step}"]`).removeClass("d-none");

                $(".step-item").removeClass("active");
                $(`.step-item[data-step="${step}"]`).addClass("active");

                updateUrl(step); // simpan ke query param
            }

            // next
            $(".next-step").click(function() {
                const currentStep = Number($(this).closest(".step-content").data("step"));
                goToStep(currentStep + 1);
            });

            // prev
            $(".prev-step").click(function() {
                const currentStep = Number($(this).closest(".step-content").data("step"));
                if (currentStep === 2) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete("snap_token");
                    window.history.replaceState({}, "", url);
                }
                if (currentStep === 3) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete("snap_token");
                    url.searchParams.delete("id_client");
                    url.searchParams.delete("nama_client");
                    url.searchParams.delete("order_id");
                    url.searchParams.delete("total_pembayaran");
                    url.searchParams.delete("status_transaksi");
                    url.searchParams.delete("metode_pembayaran");
                    url.searchParams.delete("no_client");
                    window.history.replaceState({}, "", url);
                }
                goToStep(currentStep - 1);
            });

            // baca query param ketika halaman dibuka
            const url = new URL(window.location.href);
            let stepFromQuery = Number(url.searchParams.get("step"));

            if (!stepFromQuery || stepFromQuery < 1) stepFromQuery = 1;

            goToStep(stepFromQuery);
            if (stepFromQuery === 2 && url.searchParams.get('snap_token')) {
                const snap_token = url.searchParams.get('snap_token');
                window.snap.embed(snap_token, {
                    embedId: 'snap-container',
                    onSuccess: async function(result) {
                        let results = {
                            ...result,
                            id_client: url.searchParams.get('id_client'),
                            id_katalog: url.searchParams.get('id_katalog'),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                        const {
                            transaction_status,
                            gross_amount,
                            order_id,
                            payment_type
                        } = result;
                        url.searchParams.set('status_transaksi', transaction_status)
                        url.searchParams.set('metode_pembayaran', payment_type)
                        url.searchParams.set('total_pembayaran', gross_amount)
                        url.searchParams.set('order_id', order_id)
                        window.history.replaceState({}, "", url)
                        await webhookhandler(results);
                        updateDetailInformasi()
                        goToStep(3);
                    },
                    onPending: function(result) {
                        alertToast('warning',
                            "Menunggu pembayaran anda!")
                    },
                    onError: function(result) {
                        alertToast('danger', "Pembayaran anda gagal!")
                    },
                    onClose: function() {
                        /* You may add your own implementation here */
                        alertToast('danger',
                            "Anda menutup popup pembayaran, Pembayaran otomatis dibatalkan dalam 24jam!"
                        )
                    }
                });
            }
            if (stepFromQuery === 3) {
                updateDetailInformasi()
            }

            $("#multiStepForm").submit(function(e) {
                e.preventDefault();
                alert("Form berhasil dikirim!");
            });

            $('#btn-submit-step-1').click(() => {
                const nama_cpp = $('#nama_cpp').val();
                const nama_cpw = $('#nama_cpw').val();
                const nama_ayah = $('#nama_ayah').val();
                const nama_ibu = $('#nama_ibu').val();
                const tanggal_acara = $('#tanggal_acara').val();
                const nomor_telp_customer = $('#nomor_telp_customer').val();
                const alamat_lengkap_customer = $('#alamat_lengkap_customer').val();
                const urlSearchParams = new URLSearchParams(window.location.search);
                const idKatalog = urlSearchParams.get('id_katalog');
                const url = new URL(window.location.href);

                if (nama_cpp === "" || nama_cpw === "" || nama_ayah === "" || nama_ibu === "" ||
                    tanggal_acara === "" || nomor_telp_customer === "" || alamat_lengkap_customer === "") {
                    alertToast('danger', "Silahkan lengkapi dahulu informasi data diri anda!")
                    return;
                }
                $.ajax({
                    url: `{{ route('user.katalog.charge') }}`,
                    method: "post",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        nama_cpp: nama_cpp,
                        nama_cpw: nama_cpw,
                        nama_ayah: nama_ayah,
                        nama_ibu: nama_ibu,
                        tanggal_acara: tanggal_acara,
                        nomor_telp_customer: nomor_telp_customer,
                        alamat_lengkap_customer: alamat_lengkap_customer,
                        id_katalog: idKatalog,
                        selected: urlSearchParams.get('selected')
                    },
                    beforeSend: () => {
                        $('#loaderStep1').removeClass('d-none')
                        $('#textLoaderStep1').text('Loading...')
                    },
                    success: (res) => {
                        if (res.success && res.snap_token && res.snap_token.token && res
                            .data_client) {
                            const url = new URL(window.location.href);
                            url.searchParams.set("snap_token", res.snap_token.token);
                            url.searchParams.set('id_client', res.data_client.id)
                            url.searchParams.set('nama_client', res.data_client.nama_customer)
                            url.searchParams.set('no_client', res.data_client
                                .nomor_telp_customer)
                            url.searchParams.set('id_booking', res
                                .id_booking)
                            window.history.replaceState({}, "", url)
                            goToStep(Number(url.searchParams.get('step')) + 1);

                            window.snap.embed(res.snap_token.token, {
                                embedId: 'snap-container',
                                onSuccess: async function(result) {
                                    let results = {
                                        ...result,
                                        id_booking: res.id_booking,
                                        id_client: res.data_client.id,
                                        _token: $('meta[name="csrf-token"]')
                                            .attr('content')
                                    }
                                    const {
                                        transaction_status,
                                        gross_amount,
                                        order_id,
                                        payment_type
                                    } = result;
                                    url.searchParams.set('status_transaksi',
                                        transaction_status)
                                    url.searchParams.set('metode_pembayaran',
                                        payment_type)
                                    url.searchParams.set('total_pembayaran',
                                        gross_amount)
                                    url.searchParams.set('order_id', order_id)
                                    url.searchParams.set('id_booking', res
                                        .id_booking)
                                    window.history.replaceState({}, "", url)
                                    await webhookhandler(results);
                                    updateDetailInformasi()
                                    goToStep(3);
                                },
                                onPending: function(result) {
                                    alertToast('warning',
                                        "Menunggu pembayaran anda!")
                                },
                                onError: function(result) {
                                    alertToast('danger', "Pembayaran anda gagal!")
                                },
                                onClose: function() {
                                    /* You may add your own implementation here */
                                    alertToast('danger',
                                        "Anda menutup popup pembayaran, Pembayaran otomatis dibatalkan dalam 24jam!"
                                    )
                                }
                            });
                        } else if (!res.success && res.error.length > 0) {
                            alertToast('danger', res.error.error_messages[0])
                            return;
                        }
                    },
                    complete: () => {
                        $('#loaderStep1').addClass('d-none')
                        $('#textLoaderStep1').text('Lanjut')
                    },
                    error: (err) => {
                        if (err.status === 500) {

                            const errException = err.responseJSON.message;
                            if (errException) {
                                alertToast('danger', err.responseJSON.message)
                                return
                            } else {
                                if (!err.responseJSON.success) {
                                    alertToast('danger', err.responseJSON.error.error_messages[
                                        0])
                                    return;
                                }
                                alertToast('danger', err.responseJSON.error)
                            }
                        }
                        const errors = err.responseJSON.message
                        if (err.status === 422 && errors) {
                            alertToast('danger', err.responseJSON.message)
                        }
                    }
                });
            });

            async function webhookhandler(data) {
                return await $.ajax({
                    url: `{{ route('user.katalog.store') }}`,
                    method: 'post',
                    data: data,
                    async: true,
                })
            }

            function updateDetailInformasi() {
                const url = new URL(window.location.href)
                const order_id = url.searchParams.get('order_id');
                const status_transaksi = url.searchParams.get('status_transaksi');
                const nama_client = url.searchParams.get('nama_client');
                const total_pembayaran = url.searchParams.get('total_pembayaran');
                const no_client = url.searchParams.get('no_client');

                $('#customer-name').text(nama_client)
                $('#customer-phone').text(no_client)
                $('#order-id').text(order_id)
                $('#total-amount').text(formater(total_pembayaran))
                $('#status-pembayaran').text(status_transaksi)
            }

            function formater(angka) {
                const formates = Intl.NumberFormat('ID', {
                    style: "currency",
                    currency: "IDR",
                    maximumFractionDigits: 0
                })

                return formates.format(angka);
            }
        });
    </script>
@endpush
