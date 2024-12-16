document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.sort-btn');
    const products = document.querySelectorAll('.product-card');

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        const category = button.getAttribute('data-category');

        // Tampilkan/Sembunyikan Produk Berdasarkan Kategori
        products.forEach(product => {
          if (category === 'all' || product.getAttribute('data-category') === category) {
            product.style.display = 'block';
          } else {
            product.style.display = 'none';
          }
        });
      });
    });
  });

  function sortBy(category, button) {
    // Hapus kelas 'active' dari semua tombol
    const buttons = document.querySelectorAll('.sort-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
  
    // Tambahkan kelas 'active' ke tombol yang ditekan
    button.classList.add('active');
  
    // Ambil semua elemen produk
    const products = document.querySelectorAll('.product');
  
    // Jika kategori "All" dipilih, tampilkan semua produk
    if (category === 'All') {
      products.forEach(product => product.style.display = 'block');
    } else {
      // Sembunyikan produk yang tidak sesuai dengan kategori yang dipilih
      products.forEach(product => {
        if (product.getAttribute('data-category') === category) {
          product.style.display = 'block';  // Tampilkan produk yang cocok
        } else {
          product.style.display = 'none';   // Sembunyikan produk yang tidak cocok
        }
      });
    }
  }

  function changeImage(imgElement) {
    // Mengubah gambar utama dengan gambar yang dipilih
    document.getElementById("mainImage").src = imgElement.src;
  }