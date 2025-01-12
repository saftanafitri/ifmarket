document.addEventListener('DOMContentLoaded', () => {
    const productList = document.getElementById('product-list');

    // Menyamakan tinggi semua card
    function equalizeCardHeights() {
        const cards = document.querySelectorAll('.product-item .card');
        let maxHeight = 0;

        // Resetting height
        cards.forEach(card => (card.style.height = 'auto'));

        // Cari tinggi maksimum
        cards.forEach(card => {
            const height = card.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        // Terapkan tinggi maksimum ke semua card
        cards.forEach(card => {
            card.style.height = `${maxHeight}px`;
        });
    }

    // Panggil fungsi saat halaman dimuat
    equalizeCardHeights();

    // Panggil fungsi saat ukuran layar berubah
    window.addEventListener('resize', equalizeCardHeights);

    function sortBy(category, button) {
        // Reset active state on buttons
        document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Show/hide products
        const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'All' || itemCategory === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
});
