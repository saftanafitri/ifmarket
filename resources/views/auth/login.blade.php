<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/Sakti.png') }}">
    <title>Login - Sakti Product</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <!-- Tombol Home di Pojok Kanan Atas -->
    <a href="{{ route('home.index') }}" class="home-logo">
        <i class="fas fa-home"></i>
    </a>

    <div class="card">
        <div class="logo-container">
            <div class="logo logo-sakti"></div>
        </div>

        <h3 class="typing-text">Selamat datang di platform kami</h3>
        <p class="sub-text">Silahkan masuk untuk melanjutkan</p>
        
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
            
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" name="password" id="passwordInput" placeholder="Masukkan password" required>
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <!-- Menampilkan error di bawah input password -->
            @if ($errors->any())
                <p class="error-message">{{ $errors->first() }}</p>
            @endif

            <button type="submit">Log In</button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("passwordInput");
    
        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    </script>
    
</body>
</html>
