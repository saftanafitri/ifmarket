/**
 * Fungsi INTI: Memeriksa rasio gambar dan menyesuaikan wadahnya.
 * @param {HTMLImageElement} imageElement - Elemen gambar yang akan diperiksa.
 */
function adjustContainerAspectRatio(imageElement) {
    const container = document.querySelector('.main-image-container');
    if (!container || !imageElement) return;

    const isPortrait = imageElement.naturalHeight > imageElement.naturalWidth;
    if (isPortrait) {
        container.classList.add('is-portrait');
    } else {
        container.classList.remove('is-portrait');
    }
}

/**
 * Fungsi yang dipanggil saat thumbnail diklik.
 * @param {HTMLElement} element - Elemen div thumbnail yang diklik.
 * @param {string} imageUrl - URL gambar baru untuk ditampilkan.
 */
function updateMainImage(element, imageUrl) {
    const mainImage = document.getElementById('main-image');
    if (!mainImage) return;

    mainImage.onload = () => adjustContainerAspectRatio(mainImage);
    mainImage.src = imageUrl;

    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
}

/**
 * Fungsi untuk mengambil data produk terbaru dari API.
 */
function fetchUpdatedProduct() {
    // AMBIL DATA DINAMIS DARI ATRIBUT HTML
    const section = document.getElementById('product-details-section');
    if (!section) {
        console.error('Error: Element #product-details-section not found.');
        return; 
    }

    const apiUrl = section.dataset.apiUrl;
    const storageBaseUrl = section.dataset.storageUrl;

    // Gunakan variabel yang sudah berisi URL valid
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Update semua elemen teks (nama, deskripsi, dll)
            document.querySelector(".product-details h2").innerText = data.name;
            document.querySelector(".product-details p").innerText = data.description;
            // ... dan seterusnya untuk elemen lain ...

            const mainImage = document.getElementById("main-image");

            // Update foto produk utama dengan path yang benar
            if (data.photos.length > 0) {
                mainImage.onload = () => adjustContainerAspectRatio(mainImage);
                mainImage.src = storageBaseUrl + data.photos[0].url;
            }

            // Update & bangun ulang thumbnail galeri
            const galleryContainer = document.querySelector(".thumbnails-wrapper");
            galleryContainer.innerHTML = ""; // Kosongkan galeri lama

            data.photos.forEach((photo, index) => {
                const imageUrl = storageBaseUrl + photo.url;
                const thumbnailItem = document.createElement("div");
                
                thumbnailItem.className = "thumbnail-item" + (index === 0 ? " active" : "");
                thumbnailItem.innerHTML = `<img src="${imageUrl}" alt="Thumbnail" class="thumbnail-img">`;
                
                thumbnailItem.onclick = function () {
                    updateMainImage(this, imageUrl);
                };
                
                galleryContainer.appendChild(thumbnailItem);
            });
        })
        .catch(error => console.error("Error fetching updated product:", error));
}

/**
 * Event Listener untuk memeriksa gambar pertama saat halaman baru dimuat.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Jalankan fetch pertama kali saat halaman dimuat
    fetchUpdatedProduct(); 

    const mainImage = document.getElementById('main-image');
    if (mainImage) {
        if (mainImage.complete) {
            adjustContainerAspectRatio(mainImage);
        } else {
            mainImage.onload = () => adjustContainerAspectRatio(mainImage);
        }
    }
});

// Jalankan fetch secara berkala
setInterval(fetchUpdatedProduct, 50000);

// Fungsi scroll tidak perlu diubah
function scrollGallery(direction) {
    const wrapper = document.querySelector(".thumbnails-wrapper");
    const scrollAmount = 150;
    wrapper.scrollBy({
        top: 0,
        left: direction === "left" ? -scrollAmount : scrollAmount,
        behavior: "smooth",
    });
}