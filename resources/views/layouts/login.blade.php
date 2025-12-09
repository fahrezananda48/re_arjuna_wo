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

    @yield('content')
    <!-- [ Main Content ] end -->
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
