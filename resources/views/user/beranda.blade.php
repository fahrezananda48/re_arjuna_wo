@extends('layouts.main', ['title' => 'Beranda'])
@section('content')
    <!-- Hero Section -->
    @isset($beranda['hero_section'])
        <section id="hero" class="hero section">
            <div class="hero-content">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="content">
                                <h1>{{ $beranda['hero_section']['judul'] }}</h1>
                                <p>{{ $beranda['hero_section']['deskripsi'] }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="hero-image">
                                <img src="{{ $beranda['hero_section']['gambar'] }}" alt="gambar-{{ config('app.name') }}"
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="hero-features">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="feature-item">
                                <div class="icon">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h4>Strategic Growth</h4>
                                <p>Accelerate your business expansion with proven methodologies</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="feature-item">
                                <div class="icon">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <h4>Innovation Focus</h4>
                                <p>Transform challenges into opportunities through creative solutions</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                            <div class="feature-item">
                                <div class="icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h4>Expert Team</h4>
                                <p>Industry veterans dedicated to your success and growth</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                            <div class="feature-item">
                                <div class="icon">
                                    <i class="bi bi-award"></i>
                                </div>
                                <h4>Proven Results</h4>
                                <p>Track record of delivering measurable business outcomes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            </div>

        </section>
    @endisset
    <!-- /Hero Section -->

    <!-- About Section -->
    @isset($beranda['tentang_kami'])
        <section class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>{{ $beranda['tentang_kami']['judul'] }}</h2>
            </div>
            <!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                        <div class="content">
                            <h2>{{ $beranda['tentang_kami']['judul_isi'] }}</h2>
                            <p class="lead">{{ $beranda['tentang_kami']['deskripsi_singkat'] }}</p>

                            <div class="description">
                                @if (is_array($beranda['tentang_kami']['deskripsi_lengkap']))
                                    @foreach ($beranda['tentang_kami']['deskripsi_lengkap'] as $p)
                                        <p>{!! $p !!}</p>
                                    @endforeach
                                @endif
                            </div>


                            <div class="cta-section" data-aos="fade-up" data-aos-delay="300">
                                <a href="{{ route('user.tentang.index') }}" class="btn-link">Selengkapnya <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                        <div class="image-container">
                            <img src="{{ asset('assets') }}/img/about/about-square-8.webp" alt="About Us" class="img-fluid">
                        </div>
                    </div>
                </div>

            </div>

        </section>
    @endisset
    <!-- /About Section -->
@endsection
