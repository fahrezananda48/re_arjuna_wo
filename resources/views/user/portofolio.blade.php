@extends('layouts.main', ['title' => 'Protofolio'])
@section('content')
    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>{{ $portofolio['judul_utama'] }}</h2>
            <p>{{ $portofolio['deskripsi_singkat'] ?? '' }}</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="isotope-layout" data-default-filter="*" data-layout="fitRows" data-sort="original-order">

                <div class="portfolio-filters-wrapper" data-aos="fade-up" data-aos-delay="100">
                    <ul class="portfolio-filters isotope-filters">
                        <li data-filter=".filter-mua" class="filter-active">MUA</li>
                        <li data-filter=".filter-dekor">DEKOR</li>
                    </ul>
                </div>

                <div class="row gy-4 portfolio-grid isotope-container" data-aos="fade-up" data-aos-delay="200">

                    @foreach ($portofolios as $item)
                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-mua">
                            <div class="portfolio-card">
                                <div class="image-container">
                                    <img src="{{ $item->link_foto_portofolio }}" class="img-fluid"
                                        alt="{{ $item->judul_portofolio }}" loading="lazy">
                                    <div class="overlay">
                                        <div class="overlay-content">
                                            <a href="{{ $item->link_foto_portofolio }}" class="glightbox zoom-link"
                                                title="{{ $item->deskripsi_portofolio }}">
                                                <i class="bi bi-zoom-in"></i>
                                            </a>
                                            <a href="#" class="details-link" title="Selengkapnya">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="content">
                                    <h3>{{ $item->judul_portofolio }}</h3>
                                    <p>{{ $item->deskripsi_portofolio }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <!-- End Portfolio Item -->
                </div><!-- End Portfolio Grid -->

            </div>

        </div>

    </section>
    <!-- /Portfolio Section -->
@endsection
