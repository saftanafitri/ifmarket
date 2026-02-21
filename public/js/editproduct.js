// ==========================================================
// GLOBAL STATE
// ==========================================================

const dropArea = document.getElementById("addPhotoBox");
const fileInput = document.getElementById("productPhotos");
const photoLabelText = document.getElementById("photoLabelText");

let uploadedFiles = [];

// ==========================================================
// FOTO LOGIC
// ==========================================================

function getExistingPhotoCount() {
    return document.querySelectorAll("[id^='photo-']").length;
}

function getTotalPhotoCount() {
    return getExistingPhotoCount() + uploadedFiles.length;
}

function updatePhotoCount() {
    photoLabelText.innerText = `${getTotalPhotoCount()}/9`;
}

function renderNewPhotos() {
    const container = document.querySelector(".d-flex.flex-wrap");
    document.querySelectorAll("[id^='new-preview-']").forEach(el => el.remove());

    uploadedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrapper = document.createElement("div");
            wrapper.classList.add("position-relative");
            wrapper.style.width = "100px";
            wrapper.style.height = "100px";
            wrapper.id = "new-preview-" + index;

            wrapper.innerHTML = `
                <img src="${e.target.result}" 
                     class="img-thumbnail"
                     style="width:100px;height:100px;">
                <button type="button"
                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onclick="removeNewPhoto(${index})">x</button>
            `;

            container.insertBefore(wrapper, dropArea);
        };
        reader.readAsDataURL(file);
    });

    updatePhotoCount();
}

function handleFiles(files) {
    const maxFiles = 9;

    if (getTotalPhotoCount() + files.length > maxFiles) {
        Swal.fire({
            icon: "warning",
            title: "Maksimal 9 Foto",
            text: "Anda hanya bisa mengunggah maksimal 9 foto.",
        });
        return;
    }

    uploadedFiles = [...uploadedFiles, ...files];
    renderNewPhotos();
}

if (fileInput) {
    fileInput.addEventListener("change", function () {
        handleFiles(Array.from(this.files));
    });
}

function removeNewPhoto(index) {
    uploadedFiles.splice(index, 1);
    renderNewPhotos();
}

// ==========================================================
// DELETE FOTO LAMA (SweetAlert Confirm)
// ==========================================================

function deletePhoto(photoId) {
    Swal.fire({
        title: "Hapus Foto?",
        text: "Foto yang dihapus tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, hapus",
    }).then(result => {
        if (!result.isConfirmed) return;

        const url = deletePhotoBaseUrl.replace('PHOTO_ID', photoId);

        // 🔥 Loading SweetAlert
        Swal.fire({
            title: "Menghapus...",
            text: "Mohon tunggu sebentar",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {

                    const photoElement = document.getElementById("photo-" + photoId);

                    if (photoElement) {
                        photoElement.style.transition = "opacity 0.3s ease";
                        photoElement.style.opacity = "0";
                        setTimeout(() => {
                            photoElement.remove();
                            updatePhotoCount();
                        }, 300);
                    }

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "Foto berhasil dihapus.",
                        timer: 1500,
                        showConfirmButton: false,
                    });

                } else {
                    Swal.fire("Gagal", data.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(() => {
                Swal.fire("Error", "Terjadi kesalahan server.", "error");
            });
    });
}
// ==========================================================
// VIDEO PREVIEW
// ==========================================================

const videoInput = document.getElementById("videoLink");
const videoContainer = document.querySelector(".video-preview-container");

if (videoInput) {
    videoInput.addEventListener("input", function () {
        const id = extractYouTubeId(this.value.trim());
        if (!id) {
            videoContainer.innerHTML =
                '<small class="text-muted">Pratinjau video akan muncul di sini.</small>';
            return;
        }
        videoContainer.innerHTML = `
            <iframe src="https://www.youtube.com/embed/${id}"
                frameborder="0"
                allowfullscreen
                style="width:100%;height:100%;border-radius:10px;">
            </iframe>
        `;
    });
}

function extractYouTubeId(url) {
    const regex =
        /(?:youtube\.com\/(?:.*v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// ==========================================================
// SELLER (MINIMAL 1)
// ==========================================================

function addSeller() {
    const container = document.getElementById("seller-container");
    const div = document.createElement("div");
    div.classList.add("mb-3", "seller-item");
    div.innerHTML = `
        <div class="d-flex">
            <input type="text" class="form-control border-warning"
                   name="seller_name[]" required>
            <span class="text-danger"
                  style="cursor:pointer;margin-left:10px;"
                  onclick="removeSeller(this)">×</span>
        </div>
    `;
    container.appendChild(div);
}

function removeSeller(btn) {
    const container = document.getElementById("seller-container");
    if (container.querySelectorAll(".seller-item").length <= 1) {
        Swal.fire("Minimal 1 Pemilik", "Tidak bisa dihapus semua.", "warning");
        return;
    }
    btn.closest(".seller-item").remove();
}

// ==========================================================
// SUBMIT CONFIRM SWEETALERT
// ==========================================================

document.querySelector("form").addEventListener("submit", function (e) {
    e.preventDefault();

    Swal.fire({
        title: "Update Produk?",
        text: "Pastikan data sudah benar.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Update",
    }).then(result => {
        if (!result.isConfirmed) return;

        Swal.fire({
            title: "Menyimpan...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        e.target.submit();
    });
});

document.addEventListener("DOMContentLoaded", updatePhotoCount);

function removeSeller(btn) {
    const container = document.getElementById("seller-container");
    if (container.querySelectorAll(".seller-item").length <= 1) {
        Swal.fire("Minimal 1 Pemilik", "Tidak bisa dihapus semua.", "warning");
        return;
    }
    btn.closest(".seller-item").remove();
}
