@extends('layouts.app')

@section('title', 'Teknik Informatika - Tambah Produk')

@section('content')
    <section class="py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Product details</li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="row g-4">
            <!-- Product Image -->
            <div class="col-md-4">
                <img src="https://via.placeholder.com/500x300" alt="Product" class="img-fluid">
                <!-- Galeri Gambar -->
                <div class="image-gallery mt-4">
                    <div class="row">
                        <div class="col-3">
                            <img src="https://via.placeholder.com/100" class="img-thumbnail gallery-item"
                                alt="Additional Image 1" onclick="changeImage(this)">
                        </div>
                        <div class="col-3">
                            <img src="https://via.placeholder.com/100" class="img-thumbnail gallery-item"
                                alt="Additional Image 2" onclick="changeImage(this)">
                        </div>
                        <div class="col-3">
                            <img src="https://via.placeholder.com/100" class="img-thumbnail gallery-item"
                                alt="Additional Image 3" onclick="changeImage(this)">
                        </div>
                        <div class="col-3">
                            <img src="https://via.placeholder.com/100" class="img-thumbnail gallery-item"
                                alt="Additional Image 4" onclick="changeImage(this)">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Description -->
            <div class="col-md-6">
                <h2>Curabitur pulvinar aliquam lectus, non blandit erat mattis vitae.</h2>

                <!-- Profile section -->
                <div class="d-flex justify-content-between align-items-center">

                    <!-- Profile name and icon on the left -->
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <span>Saftana Fitri</span>
                    </div>

                    <!-- Social media icons on the right -->
                    <div class="d-flex">
                        <a href="#" class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-envelope text-dark" viewBox="0 0 16 16">
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                            </svg>
                        </a>
                        <a href="#" class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-linkedin text-dark" viewBox="0 0 16 16">
                                <path
                                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                            </svg>
                        </a>
                        <a href="#" class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-instagram text-dark" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 0 0-.962.961c0 .53.431.961.962.961.53 0 .961-.431.961-.961a.96.96 0 0 0-.961-.961zM8 3.892A4.108 4.108 0 0 0 3.892 8 4.108 4.108 0 0 0 8 12.108 4.108 4.108 0 0 0 12.108 8 4.108 4.108 0 0 0 8 3.892zm0 6.769A2.662 2.662 0 0 1 5.338 8 2.662 2.662 0 0 1 8 5.338 2.662 2.662 0 0 1 10.662 8 2.662 2.662 0 0 1 8 10.662z" />
                            </svg>
                        </a>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-github text-dark" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C3.58 0 0 3.58 0 8a8 8 0 0 0 5.47 7.59c.4.07.55-.17.55-.38v-1.35C4.5 14.91 4 13.9 4 13.9c-.36-.92-.88-1.16-.88-1.16-.72-.5.06-.49.06-.49.8.06 1.22.82 1.22.82.71 1.21 1.87.86 2.33.66.07-.52.28-.86.51-1.06-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.58.82-2.14-.08-.2-.36-1.02.08-2.13 0 0 .67-.21 2.2.82a7.7 7.7 0 0 1 2.01-.27c.68 0 1.36.09 2.01.27 1.53-1.03 2.2-.82 2.2-.82.44 1.11.16 1.94.08 2.13.51.56.82 1.27.82 2.14 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48v2.2c0 .21.15.45.55.38A8 8 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eget mauris vulputate, sollicitudin orci
                    at, elementum lorem. Etiam nec neque a tortor malesuada consequat eu in nunc. Vivamus sapien sem,
                    sollicitudin vel dolor at, rutrum elementum ipsum. Vestibulum vel nisi tincidunt, bibendum dolor sed,
                    mattis turpis. Integer vehicula semper sapien sed pellentesque. Morbi ac tempor eros. Vestibulum
                    accumsan, erat volutpat interdum accumsan, orci libero ornare mi, quis mattis sem ex id leo. Donec
                    sollicitudin elementum dolor et bibendum.</p>
                <a href="https://example.com/produk" class="btn btn-custom">Lihat Produk</a>
            </div>

        </div>

        <!-- Related Products -->
        <div class="container py-5">
            <h2>Related Product</h2>
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="d-flex">
                            <img src="https://via.placeholder.com/150" class="card-img-left" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                                <p class="card-text">Non blandit erat mattis vitae.</p>
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                    <span>Saftana Fitri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="d-flex">
                            <img src="https://via.placeholder.com/150" class="card-img-left" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                                <p class="card-text">Non blandit erat mattis vitae.</p>
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-person-circle me-2 text-dark"
                                        viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                    <span>Saftana Fitri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="d-flex">
                            <img src="https://via.placeholder.com/150" class="card-img-left" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">Curabitur pulvinar aliquam lectus</h5>
                                <p class="card-text">Non blandit erat mattis vitae.</p>
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-person-circle me-2 text-dark"
                                        viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                    <span>Saftana Fitri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
