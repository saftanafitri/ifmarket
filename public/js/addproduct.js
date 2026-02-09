// ===================================================================
// BAGIAN FOTO PRODUK
// ===================================================================
const productPhotosInput = document.getElementById("productPhotos");
const photoPreviewContainer = document.getElementById("photoPreview");
const photoLabelText = document.getElementById("photoLabelText");

let uploadedFiles = [];

if (productPhotosInput) {
    productPhotosInput.addEventListener("change", function () {
        const files = Array.from(productPhotosInput.files);
        const maxFiles = 9;
        const totalFiles = uploadedFiles.length + files.length;

        if (totalFiles > maxFiles) {
            alert(`Anda hanya dapat mengunggah hingga ${maxFiles} foto.`);
            return;
        }

        uploadedFiles = [...uploadedFiles, ...files];
        updatePhotoPreview();
    });
}

function updatePhotoPreview() {
    if (!photoPreviewContainer) return;
    photoPreviewContainer.innerHTML = ""; // Hapus pratinjau sebelumnya
    uploadedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.createElement("div");
            previewContainer.classList.add("preview-container");

            const img = document.createElement("img");
            img.src = e.target.result;
            img.classList.add("preview-image");

            const fileName = document.createElement("span");
            fileName.textContent = file.name;
            fileName.classList.add("file-name");

            const trashIcon = document.createElementNS(
                "http://www.w3.org/2000/svg",
                "svg"
            );
            trashIcon.setAttribute("viewBox", "0 0 24 24");
            trashIcon.setAttribute("class", "trash-icon");
            trashIcon.innerHTML = `<path d="M3 6h18v2H3V6zm2 4h14v12H5V10zm4 2h2v8H9v-8zm4 0h2v8h-2v-8z" />`;

            trashIcon.addEventListener("click", () => {
                uploadedFiles.splice(index, 1);
                updatePhotoPreview();
                if (photoLabelText) {
                    photoLabelText.textContent = `${uploadedFiles.length}/9`;
                }
            });

            previewContainer.appendChild(img);
            previewContainer.appendChild(fileName);
            previewContainer.appendChild(trashIcon);
            photoPreviewContainer.appendChild(previewContainer);
        };
        reader.readAsDataURL(file);
    });

    if (photoLabelText) {
        photoLabelText.textContent = `${uploadedFiles.length}/9`;
    }
}

// ===================================================================
// BAGIAN VIDEO PRODUK
// ===================================================================
const videoLinkInput = document.getElementById("videoLink");
const videoPreviewDiv = document.getElementById("videoPreviewContainer");

if (videoLinkInput) {
    videoLinkInput.addEventListener("input", function () {
        const videoURL = videoLinkInput.value.trim();
        if (!videoURL) {
            videoPreviewDiv.innerHTML =
                '<small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>';
            return;
        }
        const videoId = extractYouTubeId(videoURL);
        if (videoId) {
            showYouTubePreview(videoId);
        } else {
            videoPreviewDiv.innerHTML =
                '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
        }
    });
}

function extractYouTubeId(url) {
    const regex =
        /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function showYouTubePreview(videoId) {
    const iframe = document.createElement("iframe");
    iframe.src = `https://www.youtube.com/embed/${videoId}`;
    iframe.width = "560";
    iframe.height = "315";
    iframe.frameBorder = "0";
    iframe.allow =
        "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture";
    iframe.allowFullscreen = true;
    videoPreviewDiv.innerHTML = "";
    videoPreviewDiv.appendChild(iframe);
}

// ===================================================================
// BAGIAN TAMBAH/HAPUS PEMILIK PRODUK (SELLER)
// ===================================================================
let sellerCount = document.querySelectorAll(".seller-item").length || 1;

function addSeller() {
    sellerCount++;
    const sellerContainer = document.getElementById("seller-container");
    const newSeller = document.createElement("div");
    newSeller.classList.add("mb-3", "seller-item");
    newSeller.innerHTML = `
        <div class="d-flex">
            <input type="text" class="form-control border-warning" 
                id="seller_name_${sellerCount}" 
                name="seller_name[]" 
                placeholder="Input nama pemilik produk" 
                required>
            <span class="remove-btn" onclick="removeSeller(this)" title="Hapus Pemilik Produk">×</span>
        </div>
    `;
    sellerContainer.appendChild(newSeller);
}

function removeSeller(button) {
    const sellerContainer = document.getElementById("seller-container");
    if (sellerContainer.children.length > 1) {
        button.parentElement.parentElement.remove();
    } else {
        alert("Minimal harus ada satu pemilik produk.");
    }
}

// ===================================================================
// BAGIAN UTAMA: LOGIKA SUBMIT FORM (INI YANG PALING PENTING)
// ===================================================================
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addProductForm");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Selalu hentikan submit default

            const nameInput = document.getElementById("name");
            const productName = nameInput.value.trim();
            const checkUrl = nameInput.dataset.checkUrl;
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            // Tampilkan loading spinner
            Swal.fire({
                title: "Memeriksa...",
                text: "Mohon tunggu sebentar.",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // 1. Lakukan pengecekan nama produk dulu
            fetch(checkUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({ name: productName }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        // JIKA NAMA SUDAH ADA, tampilkan error dan batalkan
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Submit",
                            text:
                                'Nama produk "' +
                                productName +
                                '" sudah digunakan. Silakan gunakan nama lain.',
                        });
                    } else {
                        // 2. JIKA NAMA UNIK, lanjutkan dengan mengirim seluruh data form via AJAX
                        Swal.update({ title: "Menyimpan Produk..." }); // Update loading text

                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                Accept: "application/json",
                            },
                            body: formData,
                        })
                            .then((response) => response.json())
                            .then((result) => {
                                if (result.success) {
                                    // JIKA BERHASIL DISIMPAN, tampilkan pop-up sukses
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil!",
                                        text: result.message,
                                    }).then(() => {
                                        // Arahkan ke halaman utama setelah user menekan OK
                                        window.location.href = "/home";
                                    });
                                } else {
                                    // Jika ada error validasi dari backend
                                    let errorText =
                                        result.message ||
                                        "Terjadi kesalahan saat menyimpan.";
                                    if (result.errors && result.errors.name) {
                                        errorText = result.errors.name[0]; // Tampilkan error spesifik
                                    }
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal Menyimpan",
                                        text: errorText,
                                    });
                                }
                            })
                            .catch((error) => {
                                console.error("Submit Error:", error);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Terjadi kesalahan teknis saat mengirim data.",
                                });
                            });
                    }
                })
                .catch((error) => {
                    console.error("Check Name Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Terjadi kesalahan teknis saat memeriksa nama.",
                    });
                });
        });
    }
});
