document.addEventListener("DOMContentLoaded", () => {
    const categorySelect = document.getElementById("category-select");

    // Event listener untuk perubahan kategori
    categorySelect.addEventListener("change", function () {
        var selectedCategory = this.value;
        var currentUrl = new URL(window.location.href);

        // Jika kategori "All" dipilih, hapus parameter category
        if (selectedCategory === "All") {
            currentUrl.searchParams.delete("category"); // Hapus query string
        } else {
            currentUrl.searchParams.set("category", selectedCategory); // Set kategori yang dipilih
        }

        // Arahkan ke URL yang telah diperbarui
        window.location.href = currentUrl.toString();
    });

    // Menyamakan tinggi semua card
    function equalizeCardHeights() {
        const cards = document.querySelectorAll(".product-item .card");
        let maxHeight = 0;

        // Resetting height
        cards.forEach((card) => (card.style.height = "auto"));

        // Cari tinggi maksimum
        cards.forEach((card) => {
            const height = card.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        // Terapkan tinggi maksimum ke semua card
        cards.forEach((card) => {
            card.style.height = `${maxHeight}px`;
        });
    }

    // Panggil fungsi saat halaman dimuat
    equalizeCardHeights();

    // Panggil fungsi saat ukuran layar berubah
    window.addEventListener("resize", equalizeCardHeights);
});
