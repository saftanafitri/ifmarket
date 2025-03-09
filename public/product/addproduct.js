// JS Foto
const productPhotos = document.getElementById('productPhotos');
const photoPreview = document.getElementById('photoPreview');
const photoLabelText = document.getElementById('photoLabelText');


let uploadedFiles = [];

productPhotos.addEventListener('change', function () {
    const files = Array.from(productPhotos.files);
    const maxFiles = 9;
    const totalFiles = uploadedFiles.length + files.length;

    if (totalFiles > maxFiles) {
        alert(`Anda hanya dapat mengunggah hingga ${maxFiles} foto.`);
        return;
    }

    uploadedFiles = [...uploadedFiles, ...files];

    // Perbarui pratinjau
    updatePhotoPreview();
});

function updatePhotoPreview() {
    photoPreview.innerHTML = ''; // Hapus pratinjau sebelumnya
    uploadedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.createElement('div');
            previewContainer.classList.add('preview-container');

            // Tambahkan gambar pratinjau
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image');

            // Tambahkan nama file
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            fileName.classList.add('file-name');

            // Ikon Tempat Sampah Berwarna Merah (menggunakan SVG)
            const trashIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            trashIcon.setAttribute('viewBox', '0 0 24 24');
            trashIcon.setAttribute('class', 'trash-icon');
            trashIcon.innerHTML = `
                <path d="M3 6h18v2H3V6zm2 4h14v12H5V10zm4 2h2v8H9v-8zm4 0h2v8h-2v-8z" />
            `;

            // Fungsi untuk menghapus gambar
            trashIcon.addEventListener('click', () => {
                uploadedFiles.splice(index, 1); // Hapus file dari array
                updatePhotoPreview(); // Perbarui pratinjau
                photoLabelText.textContent = `${uploadedFiles.length}/9`; // Perbarui label jumlah foto
            });

            // Gabungkan elemen
            previewContainer.appendChild(img);
            previewContainer.appendChild(fileName);
            previewContainer.appendChild(trashIcon);
            photoPreview.appendChild(previewContainer);
        };
        reader.readAsDataURL(file);
    });

    // Perbarui label jumlah foto
    photoLabelText.textContent = `${uploadedFiles.length}/9`;
}


// JS Video

// Video Upload Logic
const videoLinkInput = document.getElementById('videoLink');
const videoPreviewContainer = document.getElementById('videoPreviewContainer');

videoLinkInput.addEventListener('input', function () {
    const videoURL = videoLinkInput.value.trim();

    // Check if the video already exists
    if (!videoURL) {
        videoPreviewContainer.innerHTML = '<small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>';
        return;
    }

    if (isYouTube(videoURL)) {
        const videoId = extractYouTubeId(videoURL);
        showYouTubePreview(videoId);
    } else {
        videoPreviewContainer.innerHTML = '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
    }
});

// Fungsi untuk memeriksa URL YouTube
function isYouTube(url) {
    return url.includes("youtube.com") || url.includes("youtu.be");
}

// Fungsi untuk mengambil ID video YouTube
function extractYouTubeId(url) {
    const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S+\/\S+[\?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// Fungsi untuk menampilkan pratinjau YouTube
function showYouTubePreview(videoId) {
    const iframe = document.createElement('iframe');
    iframe.src = `https://www.youtube.com/embed/${videoId}`;
    iframe.width = '560';
    iframe.height = '315';
    iframe.frameBorder = '0';
    iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;

    videoPreviewContainer.innerHTML = ''; // Hapus konten lama
    videoPreviewContainer.appendChild(iframe);
}

// Tambah Seller
let sellerCount = document.querySelectorAll('.seller-item').length || 1;

// Function to add a new seller input
function addSeller() {
    sellerCount++;
    const sellerContainer = document.getElementById('seller-container');
    const newSeller = document.createElement('div');
    newSeller.classList.add('mb-3', 'seller-item');
    newSeller.innerHTML = `
        <div class="d-flex">
            <input type="text" class="form-control border-warning" 
                id="seller_name_${sellerCount}" 
                name="seller_name[]" 
                placeholder="Input nama pemilik produk" 
                required>
            <span class="remove-btn" onclick="removeSeller(this)" title="Hapus Penjual">&times;</span>
        </div>
    `;
    sellerContainer.appendChild(newSeller);
}

// Function to remove a seller input
function removeSeller(button) {
    const sellerContainer = document.getElementById('seller-container');
    if (sellerContainer.children.length > 1) {
        button.parentElement.parentElement.remove();
    } else {
        alert('Minimal harus ada satu penjual.');
    }
}
