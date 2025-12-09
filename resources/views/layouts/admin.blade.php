<!doctype html>
<html lang="id">
<!-- [Head] start -->

<head>
    <title>{{ $title ?? '' }} | {{ config('app.name') }}</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('admin') }}/images/favicon.svg" type="image/x-icon" />
    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('admin') }}/fonts/phosphor/regular/style.css" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('admin') }}/fonts/tabler-icons.min.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin') }}/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin') }}/css/style-preset.css" />
    @stack('css')

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Sidebar Menu ] start -->
    @include('layouts.admin-sidebar')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('layouts.admin-navbar')
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @session('alert')
                <div class="alert alert-{{ session()->get('alert')['type'] }} alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    {{ session()->get('alert')['message'] }}
                </div>

                @push('js')
                    <script>
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 2000)
                    </script>
                @endpush
            @endsession

            <!-- [ breadcrumb ] start -->
            @yield('page-header')
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            @yield('content')
            <!-- [ Main Content ] end -->
            @stack('utils')
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">{{ config('app.name') }} &#9829; crafted by Team <a
                            href="{{ config('app.url') }}" target="_blank">{{ config('app.name') }}</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('admin') }}/js/plugins/popper.min.js"></script>
    <script src="{{ asset('admin') }}/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('admin') }}/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('admin') }}/js/script.js"></script>
    <script src="{{ asset('admin') }}/js/theme.js"></script>



    <script>
        layout_change('light');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>

    <script>
        layout_theme_sidebar_change('false');
    </script>

    @stack('js')
</body>
<!-- [Body] end -->

</html>
