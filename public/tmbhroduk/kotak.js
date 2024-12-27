const productPhotos = document.getElementById('productPhotos');
const photoPreview = document.getElementById('photoPreview');
const photoLabelText = document.getElementById('photoLabelText');

// Array untuk menyimpan file yang diunggah
let uploadedFiles = [];

productPhotos.addEventListener('change', function () {
    const files = Array.from(productPhotos.files);
    const maxFiles = 9;

    // Gabungkan file baru dengan file yang sudah diunggah
    const totalFiles = uploadedFiles.length + files.length;

    // Jika total file melebihi batas, batalkan
    if (totalFiles > maxFiles) {
        alert(`Anda hanya dapat mengunggah hingga ${maxFiles} foto.`);
        return;
    }

    // Tambahkan file baru ke array file yang diunggah
    uploadedFiles = [...uploadedFiles, ...files];

    // Perbarui pratinjau
    photoPreview.innerHTML = ''; // Hapus pratinjau sebelumnya
    uploadedFiles.forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.createElement('div');
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'column';
            previewContainer.style.alignItems = 'center';

            // Tambahkan gambar pratinjau
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '5px';
            img.style.border = '1px solid #ccc';

            // Tambahkan nama file
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            fileName.style.fontSize = '12px';
            fileName.style.color = '#555';
            fileName.style.marginTop = '5px';
            fileName.style.textAlign = 'center';
            fileName.style.width = '100px';
            fileName.style.overflow = 'hidden';
            fileName.style.textOverflow = 'ellipsis';
            fileName.style.whiteSpace = 'nowrap';

            // Gabungkan gambar dan nama file ke container
            previewContainer.appendChild(img);
            previewContainer.appendChild(fileName);
            photoPreview.appendChild(previewContainer);
        };
        reader.readAsDataURL(file);
    });

    // Perbarui label jumlah foto
    photoLabelText.textContent = `${uploadedFiles.length}/${maxFiles}`;
});
