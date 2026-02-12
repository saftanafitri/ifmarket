<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/Sakti.png') }}">
    <title>Sakti Produk</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('landing/landing.css') }}">
</head>

<body>
    <div class="container-fluid hero d-flex flex-column flex-md-row align-items-center text-center text-md-start">
        <div class="col-md-5 mb-3">
            <img src="images/Sakti.png" alt="logo sakti market" class="img-fluid">
        </div>
        <div class="col-md-5">
            <h1>
                <div class="text-slider" style="color:#f9f8db;">
                    <span>Sakti Produk</span>
                </div>
            </h1>
            <p class="description">
                Platform inovatif yang dirancang untuk memamerkan berbagai produk informatika hasil karya mahasiswa Teknik Informatika UIN Sunan Gunung Djati.
                Sakti Market menjadi wadah bagi mahasiswa untuk menampilkan kreativitas dan keahlian mereka di bidang teknologi, mulai dari aplikasi, perangkat lunak, hingga inovasi berbasis AI dan IoT.
                Sakti Market bertujuan untuk menghubungkan karya mahasiswa dengan masyarakat, dunia industri, dan para pemangku kepentingan, sekaligus mendorong kolaborasi dan pengembangan teknologi yang berdampak positif.
            </p>
            <div class="cta mt-4">
                <a href="{{ route('home.index') }}" class="btn btn-warning me-3">Lihat</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
