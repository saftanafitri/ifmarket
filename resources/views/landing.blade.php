<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakti Market</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #111, #111, #f8d219);
            color: #fff;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px; /* Removed padding from top and bottom */
        }

        .hero img {
            width: 100%;
            max-width: 500px;
            height: auto;
            object-fit: contain; /* Ensure the image is fully visible */
            border-radius: 5px;
            margin-right: 5px; /* Added a small margin to the right */
        }
        .text-slider {
            display: inline-block;
            overflow: hidden;
            vertical-align: top;
            height: 1.2em;
        }

        .text-slider span {
            display: inline-block;
            padding-left: 10px;
            animation: slide 6s infinite;
        }

        @keyframes slide {
            0%,
            20% {
                transform: translateY(0);
            }

            25%,
            45% {
                transform: translateY(-1.2em);
            }

            50%,
            70% {
                transform: translateY(-2.4em);
            }

            75%,
            100% {
                transform: translateY(0);
            }
        }

        .cta .btn-warning {
            background-color: #fff; /* Warna putih default */
            color: #000; /* Warna teks hitam default */
            border: none; /* Hilangkan border */
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            padding: 1rem 2rem;
            transition: background-color 0.3s, color 0.3s; /* Tambahkan transisi */
        }

        .cta .btn-warning:hover {
            background-color: #f8d219; /* Warna kuning saat hover */
            color: #000; /* Tetap warna teks hitam */
        }

        .description {
            margin-top: 20px;
            font-size: 1rem;
            color: #f9f8db;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero h1 {
                font-size: 3rem;
            }

            .hero img {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid hero d-flex flex-column flex-md-row align-items-center text-center text-md-start">
        <div class="col-md-5 mb-3">
            <img src="images/Sakti.png" alt="logo sakti market" class="img-fluid">
        </div>
        <div class="col-md-5">
            <h1>
                <div class="text-slider" style="color:#f9f8db;">
                    <span>SAKTI MARKET</span>
                </div>
            </h1>
            <p class="description">
                Platform inovatif yang dirancang untuk memamerkan berbagai produk informatika hasil karya mahasiswa Teknik Informatika UIN Sunan Gunung Djati.
                Sakti Market menjadi wadah bagi mahasiswa untuk menampilkan kreativitas dan keahlian mereka di bidang teknologi, mulai dari aplikasi, perangkat lunak, hingga inovasi berbasis AI dan IoT.
                Sakti Market bertujuan untuk menghubungkan karya mahasiswa dengan masyarakat, dunia industri, dan para pemangku kepentingan, sekaligus mendorong kolaborasi dan pengembangan teknologi yang berdampak positif.
            </p>
            <div class="cta mt-4">
                <a href="{{ route('home.index') }}" class="btn btn-warning me-3">Get in Touch</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
