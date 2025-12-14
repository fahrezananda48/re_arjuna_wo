 <nav class="pc-sidebar">
     <div class="navbar-wrapper">
         <div class="m-header">
             <a href="{{ route('admin.beranda.index') }}" class="b-brand text-white h3 uppercase fw-bold">
                 <!-- ========   Change your logo from here   ============ -->
                 {{ config('app.name') }}
             </a>
         </div>
         <div class="navbar-content">
             @if (Auth::user()->role === 'super_admin')
                 <ul class="pc-navbar">
                     <li class="pc-item">
                         <a href="{{ route('admin.beranda.index') }}"
                             class="pc-link {{ isRoutePrefix('admin.beranda.*') }}">
                             <span class="pc-micon">
                                 <i class="ph ph-house-line"></i>
                             </span>
                             <span class="pc-mtext" data-i18n="Dashboard">Dashboard</span>
                         </a>
                     </li>
                     <li class="pc-item pc-caption">
                         <label data-i18n="UI Components">Master Data</label>
                         <i class="ph ph-pencil-ruler"></i>
                     </li>
                     <li class="pc-item {{ Route::is('admin.katalog.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.katalog.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-cube"></i></span>
                             <span class="pc-mtext">Katalog</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.customer.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.customer.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-users"></i></span>
                             <span class="pc-mtext">Customer</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.booking.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.booking.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-bookmarks"></i></span>
                             <span class="pc-mtext">Data Booking</span>
                         </a>
                     </li>
                     {{-- <li class="pc-item {{ Route::is('admin.laporan.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.laporan.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-notepad"></i></span>
                             <span class="pc-mtext">Laporan</span>
                         </a>
                     </li> --}}
                     <li class="pc-item {{ Route::is('admin.portofolio.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.portofolio.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-images"></i></span>
                             <span class="pc-mtext">Portofolio</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.gaun-pengantin.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.gaun-pengantin.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-dress"></i></span>
                             <span class="pc-mtext">Gaun Pengantin</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.dekorasi.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.dekorasi.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-palette"></i></span>
                             <span class="pc-mtext">Dekorasi</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.tenda.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.tenda.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-tent"></i></span>
                             <span class="pc-mtext">Tenda</span>
                         </a>
                     </li>
                 </ul>
             @elseif (Auth::user()->role === 'admin')
                 <ul class="pc-navbar">
                     <li class="pc-item">
                         <a href="{{ route('admin.beranda.index') }}"
                             class="pc-link {{ isRoutePrefix('admin.beranda.*') }}">
                             <span class="pc-micon">
                                 <i class="ph ph-house-line"></i>
                             </span>
                             <span class="pc-mtext" data-i18n="Dashboard">Dashboard</span>
                         </a>
                     </li>
                     <li class="pc-item pc-caption">
                         <label data-i18n="UI Components">Master Data</label>
                         <i class="ph ph-pencil-ruler"></i>
                     </li>
                     <li class="pc-item {{ Route::is('admin.katalog.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.katalog.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-cube"></i></span>
                             <span class="pc-mtext">Katalog</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.booking.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.booking.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-bookmarks"></i></span>
                             <span class="pc-mtext">Data Booking</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.gaun-pengantin.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.gaun-pengantin.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-dress"></i></span>
                             <span class="pc-mtext">Gaun Pengantin</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.dekorasi.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.dekorasi.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-palette"></i></span>
                             <span class="pc-mtext">Dekorasi</span>
                         </a>
                     </li>
                     <li class="pc-item {{ Route::is('admin.tenda.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.tenda.index') }}" class="pc-link">
                             <span class="pc-micon"><i class="ph ph-tent"></i></span>
                             <span class="pc-mtext">Tenda</span>
                         </a>
                     </li>
                 </ul>
             @endif
         </div>
     </div>
 </nav>
