<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teknik Informatika')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Header -->
    <header class="bg-custom py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/LogoIF.png') }}" alt="Teknik Informatika" class="me-2"
                    style="width: 70px;">
                <h1 class="h4 m-0 text-center">
                    <span class="d-block">TEKNIK</span>
                    <span class="d-block">INFORMATIKA</span>
                </h1>
            </div>
            <div class="input-group w-50">
                @if(Route::currentRouteName() == 'index')
                <input type="text" class="form-control" placeholder="Search for anything...">
                <button class="search-btn">
                    <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="Search Icon">
                </button>
                @endif
            </div>
            <div class="dropdown">
                <div id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </div>
                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    @if(Route::currentRouteName() == 'index')
                    <li><a class="dropdown-item" href="{{ route('addProduct') }}">Add Products</a></li>
                    <li><a class="dropdown-item" href="{{ route('manageProduct') }}">Manage Products</a></li>
                    @elseif (Route::currentRouteName() == 'addProduct')
                    <li><a class="dropdown-item" href="{{ route('index') }}">Home</a></li>
                    <li><a class="dropdown-item" href="{{ route('manageProduct') }}">Manage Products</a></li>
                    @elseif (Route::currentRouteName() == 'manageProduct')
                    <li><a class="dropdown-item" href="{{ route('index') }}">Home</a></li>
                    <li><a class="dropdown-item" href="{{ route('addProduct') }}">Add Products</a></li>
                    @elseif (Route::currentRouteName() == 'detail')
                    <li><a class="dropdown-item" href="{{ route('index') }}">Home</a></li>
                    <li><a class="dropdown-item" href="{{ route('addProduct') }}">Add Products</a></li>
                    <li><a class="dropdown-item" href="{{ route('manageProduct') }}">Manage Products</a></li>
                    @else
                    <li><a class="dropdown-item" href="{{ route('index') }}">Home</a></li>
                    <li>@endif
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
        </div>
    </header>

    <main class="container my-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-custom py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('images/Logo_footer.png') }}" alt="UIN SGD" style="width: 180px;">
                    <h5>Teknik Informatika</h5>
                    <p>Universitas Islam Negeri Sunan Gunung Djati Bandung<br>Jalan A.H. Nasution No. 105, Cipadung,
                        Cibiru, Bandung, Jawa Barat 40614</p>
                </div>
                <div class="col-md-4">
                    <h5>Layanan Akademik</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://salam.uinsgd.ac.id/">Sistem Informasi Layanan Akademik (SALAM)</a></li>
                        <li><a href="https://eknows.uinsgd.ac.id/">Learning Management Sistem (LMS)</a></li>
                        <li><a href="https://lib.uinsgd.ac.id/">E-Library Teknik UIN Sunan Gunung Djati</a></li>
                        <li><a href="#">E-Library Teknik Informatika</a></li>
                        <li><a href="https://join.if.uinsgd.ac.id/">Jurnal Online Informatika</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Akses Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://fst.uinsgd.ac.id/">Fakultas Sains dan Teknologi</a></li>
                        <li><a href="https://uinsgd.ac.id/">UIN Sunan Gunung Djati</a></li>
                        <li><a href="https://sinta.kemdikbud.go.id/">Sinta DIKTI Kemdikbud RI</a></li>
                        <li><a href="https://pddikti.kemdikbud.go.id/">Pangkalan Data DIKTI Kemdikbud RI</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-left mt-3">&copy; 2024 by Saftana Fitri</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
