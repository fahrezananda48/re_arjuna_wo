@extends('layouts.main', ['title' => 'Pembayaran'])
@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div id="snap-container" class="container-midtrans"></div>
    </div>
@endsection


@push('js')
    <script type="text/javascript"
        src="{{ env('MIDTRANS_IS_PRODUCTION') === 'true' ? env('MIDTRANS_SNAP_JS_PRODUCTION_URL') : env('MIDTRANS_SNAP_JS_SANDBOX_URL') }}"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        $(document).ready(function() {
            window.snap.embed(`{{ $snap_token }}`, {
                embedId: 'snap-container',
                onSuccess: async function(result) {
                    let results = {
                        ...result,
                        id_booking: `{{ $booking->id }}`,
                        _token: $('meta[name="csrf-token"]')
                            .attr('content')
                    }
                    const {
                        transaction_status,
                        gross_amount,
                        order_id,
                        payment_type,
                        finish_redirect_url
                    } = result;
                    window.location.href = finish_redirect_url;

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
        })
    </script>
@endpush
