@extends('layouts.app')

@section('title', 'Teknik Informatika')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-left">Products</h2>
                <div class="sorting-buttons">
                    <button class="sort-btn" onclick="sortBy('All', this)">All</button>
                    <button class="sort-btn" onclick="sortBy('KP', this)">Kerja Praktik (KP)</button>
                    <button class="sort-btn" onclick="sortBy('TA', this)">Tugas Akhir (TA)</button>
                    <button class="sort-btn" onclick="sortBy('Penelitian', this)">Penelitian</button>
                    <button class="sort-btn" onclick="sortBy('Pengabdian', this)">Pengabdian pada Masyarakat</button>
                    <button class="sort-btn" onclick="sortBy('TugasKuliah', this)">Tugas Kuliah</button>
                </div>
            </div>
            <div class="row g-4">
                <!-- Repeat Product Card -->
                <div class="col-md-4 col-lg-2" data-category="TA">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="{{ route('detail') }}" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2" data-category="Penelitian">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2" data-category="KP">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2" data-category="Pengabdian">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="#" class="btn bg-custom1 btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2" data-category="TugasKuliah">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="#" class="btn bg-custom1 btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2" data-category="KP">
                    <div class="card">
                        <img src="product.jpg" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                            <p class="card-text">Non blandit erat mattis vitae.</p>
                            <p class="text-muted small mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg> Saftana Fitri</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <!-- Copy Product Card as needed -->
            </div>
        </div>
    </section>
    <!-- Latest News Section -->

    <section>
        <div class="container mb-5">
            <h2 class="text-center mb-4">Latest Products</h2>
            <div class="row g-4">
                <!-- Repeat News Card -->
                <div class="col-md-4">
                    <div class="card">
                        <img src="news.jpg" class="card-img-top" alt="News">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur massa orci</h5>
                            <p class="card-text">Consectetur et blandit ac, auctor et tellus.</p>
                            <p class="text-muted small">25 November 2024</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="news.jpg" class="card-img-top" alt="News">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur massa orci</h5>
                            <p class="card-text">Consectetur et blandit ac, auctor et tellus.</p>
                            <p class="text-muted small">25 November 2024</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="news.jpg" class="card-img-top" alt="News">
                        <div class="card-body">
                            <h5 class="card-title">Curabitur massa orci</h5>
                            <p class="card-text">Consectetur et blandit ac, auctor et tellus.</p>
                            <p class="text-muted small">25 November 2024</p>
                            <a href="#" class="btn bg-custom1  btn-sm">View More</a>
                        </div>
                    </div>
                </div>
                <!-- Copy News Card as needed -->
            </div>
        </div>
    </section>
@endsection
