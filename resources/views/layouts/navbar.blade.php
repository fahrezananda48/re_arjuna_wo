 <header id="header" class="header sticky-top">

     <div class="topbar d-flex align-items-center dark-background">
         <div class="container d-flex justify-content-center justify-content-md-between">
             @isset($dataConfig['lainya']['footer'])
                 <div class="contact-info d-flex align-items-center">
                     <i class="bi bi-envelope d-flex align-items-center"><a
                             href="mailto:{{ $dataConfig['lainya']['footer']['email'] }}">{{ $dataConfig['lainya']['footer']['email'] }}</a></i>
                     <i class="bi bi-phone d-flex align-items-center ms-4"><span>+62
                             {{ $dataConfig['lainya']['footer']['no_telp'] }}</span></i>
                 </div>
                 <div class="social-links d-none d-md-flex align-items-center">
                     @if (is_array($dataConfig['lainya']['footer']['link_sosmed']))
                         @foreach ($dataConfig['lainya']['footer']['link_sosmed'] as $item)
                             <a href="{{ $item['link'] }}"><i class="bi {{ $item['icon'] }}"></i></a>
                         @endforeach
                     @endif
                 </div>
             @endisset
         </div>
     </div><!-- End Top Bar -->

     <div class="branding d-flex align-items-center">

         <div class="container position-relative d-flex align-items-center justify-content-between">
             <a href="{{ route('user.beranda.index') }}" class="logo d-flex align-items-center">
                 <!-- Uncomment the line below if you also wish to use an image logo -->
                 <!-- <img src="{{ asset('assets') }}/img/logo.webp" alt=""> -->
                 <h1 class="sitename">{{ config('app.name') }}</h1>
             </a>

             <nav id="navmenu" class="navmenu">
                 <ul>
                     <li>
                         <a href="{{ route('user.beranda.index') }}"
                             class="{{ isCurrentUrl('user.beranda.index') }}">Beranda</a>
                     </li>
                     <li>
                         <a href="{{ route('user.katalog.index') }}"
                             class="{{ isCurrentUrl('user.katalog.index') }}">Katalog</a>
                     </li>
                     <li>
                         <a href="{{ route('user.portofolio.index') }}"
                             class="{{ isCurrentUrl('user.portofolio.index') }}">Portfolio
                         </a>
                     </li>
                     @if (!Auth::check())
                         <li><a class="button-link-navbar" href="{{ route('login') }}">Login</a></li>
                     @else
                         <li><a class="button-link-navbar" href="{{ route('admin.logout') }}">Logout</a></li>
                     @endif
                 </ul>
                 <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
             </nav>

         </div>

     </div>

 </header>
