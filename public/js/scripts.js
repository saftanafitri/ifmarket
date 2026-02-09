document.addEventListener("DOMContentLoaded", () => {
    // --- BAGIAN UNTUK FILTER PRODUK BERDASARKAN KATEGORI (DI HALAMAN UTAMA) ---
    const sortButtons = document.querySelectorAll(".sort-btn");
    const products = document.querySelectorAll(".product-card");

    if (sortButtons.length > 0 && products.length > 0) {
        sortButtons.forEach((button) => {
            button.addEventListener("click", () => {
                sortButtons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");
                const category = button.getAttribute("data-category");
                products.forEach((product) => {
                    if (
                        category === "all" ||
                        product.getAttribute("data-category") === category
                    ) {
                        product.style.display = "block";
                    } else {
                        product.style.display = "none";
                    }
                });
            });
        });
    }

    // --- BAGIAN UNTUK FOTO PROFIL HEADER (BERJALAN DI SEMUA HALAMAN) ---
    function loadProfilePicture() {
        // Cek apakah data API dari Blade sudah ada
        if (
            typeof window.salamApi === "undefined" ||
            !window.salamApi.url ||
            !window.salamApi.token
        ) {
            console.log("Konfigurasi API tidak ditemukan.");
            return;
        }

        const apiUrl = window.salamApi.url;
        const authToken = window.salamApi.token;

        fetch(apiUrl, {
            headers: {
                Authorization: `Bearer ${authToken}`,
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok)
                    throw new Error("Gagal mengambil data profil");
                return response.json();
            })
            .then((apiResponse) => {
                const parsedData = JSON.parse(apiResponse.body);
                const imageUrl = parsedData.data.foto_user;

                if (imageUrl) {
                    const profileContainer = document.getElementById(
                        "profile-icon-container"
                    );
                    if (profileContainer) {
                        const profileImg = document.createElement("img");
                        profileImg.src = imageUrl;
                        profileImg.alt = "Foto Profil";
                        // Tambahkan style langsung via JS
                        profileImg.style.width = "32px";
                        profileImg.style.height = "32px";
                        profileImg.style.borderRadius = "50%";
                        profileImg.style.objectFit = "cover";

                        profileContainer.innerHTML = "";
                        profileContainer.appendChild(profileImg);
                    }
                }
            })
            .catch((error) => {
                console.error("Error saat memuat foto profil:", error);
            });
    }

    // Panggil fungsi untuk memuat foto profil
    loadProfilePicture();
}); // Akhir dari DOMContentLoaded

/**
 * --- BAGIAN UNTUK GALERI GAMBAR (DI HALAMAN DETAIL) ---
 * Diletakkan di luar agar bisa diakses oleh onclick di HTML.
 */
function changeImage(imgElement) {
    const mainImage = document.getElementById("mainImage");
    if (mainImage) {
        mainImage.src = imgElement.src;
    }
}

window.addEventListener("pageshow", function () {
    // Cari input search berdasarkan atribut name='query'
    const searchInput = document.querySelector('input[name="query"]');

    // Jika ditemukan, kosongkan nilainya
    if (searchInput) {
        searchInput.value = "";
    }
});
