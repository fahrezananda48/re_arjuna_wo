@php
    $dataConfig = getDataJson();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('assets') }}/img/favicon.png" rel="icon">
    <link href="{{ asset('assets') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets') }}/css/main.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/css/custom.css" rel="stylesheet">
    @stack('css')
</head>

<body class="index-page main-page">
    <div class="alert-container position-fixed top-0 start-50 translate-middle-x mt-3 z-5 d-none"
        style="min-width:300px; max-width:90%;"></div>


    @include('layouts.navbar')

    <main class="main">
        @yield('content')
    </main>

    <footer id="footer" class="footer light-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="{{ route('user.beranda.index') }}" class="d-flex align-items-center">
                        <span class="sitename">{{ config('app.name') }}</span>
                    </a>
                    @isset($dataConfig['lainya']['footer'])
                        <div class="footer-contact pt-3">
                            <p>{{ $dataConfig['lainya']['footer']['alamat'] }}</p>
                            <p class="mt-3"><strong>No Wa:</strong> <span>+62
                                    {{ $dataConfig['lainya']['footer']['no_telp'] }}</span></p>
                            <p><strong>Email:</strong> <span>{{ $dataConfig['lainya']['footer']['email'] }}</span></p>
                        </div>
                    @endisset
                </div>
                @isset($dataConfig['lainya']['footer'])
                    <div class="col-lg-4 col-md-12">
                        <h4>Ikuti Kami</h4>
                        <p>{{ $dataConfig['lainya']['footer']['deskripsi_singkat_link_sosmed'] }}</p>
                        <div class="social-links d-flex">
                            @if (is_array($dataConfig['lainya']['footer']['link_sosmed']))
                                @foreach ($dataConfig['lainya']['footer']['link_sosmed'] as $item)
                                    <a href="{{ $item['link'] }}"><i class="bi {{ $item['icon'] }}"></i></a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endisset
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ config('app.name') }}</strong> <span>All
                    Rights
                    Reserved</span></p>
            <div class="credits">
                Designed by <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    @if (Auth::check())
        @if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')
            <div class="btn-fab-wrapper">
                <a href="{{ route('user.keranjang.index') }}" class="btn-fab-keranjang">
                    <div class="icon-keranjang">
                        {{ Cart::countAllItemInCart() === '0' ? null : Cart::countAllItemInCart() }}
                    </div>
                    <i class="bi bi-bag fs-5"></i>
                </a>
            </div>
        @endif
    @endif
    @stack('utils')
    <!-- Vendor JS Files -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/php-email-form/validate.js"></script>
    <script src="{{ asset('assets') }}/vendor/aos/aos.js"></script>
    <script src="{{ asset('assets') }}/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="{{ asset('assets') }}/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="{{ asset('assets') }}/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets') }}/js/main.js"></script>
    @stack('js')
</body>

</html>
