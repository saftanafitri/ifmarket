// Tambah Seller
function addSeller() {
    let sellerContainer = document.getElementById("seller-container");
    let newSeller = document.createElement("div");
    newSeller.classList.add("mb-3", "seller-item");
    newSeller.innerHTML = `
        <div class="d-flex">
            <input type="text" class="form-control border-warning" name="seller_name[]" required>
            <span class="remove-btn text-danger" style="cursor: pointer; margin-left: 10px;" onclick="removeSeller(this)">×</span>
        </div>
    `;
    sellerContainer.appendChild(newSeller);
}

// Hapus Seller
function removeSeller(element) {
    element.parentElement.parentElement.remove();
}

// Hapus Foto Lama dari Server
function deletePhoto(photoId) {
    fetch(
        "{{ route('products.photos.delete', ['slug' => $product->slug, 'photoId' => ':photoId']) }}".replace(
            ":photoId",
            photoId
        ),
        {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
            },
        }
    )
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("photo-" + photoId).remove();
                updatePhotoCount();
            } else {
                alert(
                    data.message || "Gagal menghapus foto. Silakan coba lagi."
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menghapus foto.");
        });
}

// Tambah Preview Foto Baru Sebelum Upload
document
    .getElementById("productPhotos")
    .addEventListener("change", function (event) {
        const photoContainer = document.querySelector(".d-flex.flex-wrap");
        const files = event.target.files;
        let existingPhotos =
            document.querySelectorAll(".position-relative").length;

        if (files.length + existingPhotos > 9) {
            alert("Maksimal 9 foto diperbolehkan.");
            return;
        }

        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const photoId =
                    "new-" + Math.random().toString(36).substr(2, 9);
                const photoElement = document.createElement("div");
                photoElement.id = photoId;
                photoElement.classList.add("position-relative");
                photoElement.style.width = "100px";
                photoElement.style.height = "100px";

                photoElement.innerHTML = `
                <img src="${e.target.result}" alt="Foto Baru"
                    class="img-thumbnail" style="width: 100px; height: 100px;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onclick="removeNewPhoto('${photoId}')">x</button>
            `;

                photoContainer.insertBefore(
                    photoElement,
                    document.getElementById("addPhotoBox")
                );
            };
            reader.readAsDataURL(file);
        });

        setTimeout(updatePhotoCount, 100); // Beri jeda agar foto baru dihitung
    });

// Hapus Foto Baru Sebelum Submit
function removeNewPhoto(photoId) {
    document.getElementById(photoId).remove();
    updatePhotoCount();
}

// Update Jumlah Foto yang Ditampilkan
function updatePhotoCount() {
    let totalPhotos = document.querySelectorAll(".position-relative").length;
    document.getElementById("photoLabelText").innerText = totalPhotos + "/9";
}

// Pastikan angka diperbarui saat halaman dimuat pertama kali
document.addEventListener("DOMContentLoaded", updatePhotoCount);

// JS video
// JavaScript untuk Preview Video
document.getElementById("videoLink").addEventListener("input", function () {
    const videoURL = this.value.trim();
    const videoPreviewContainer = document.querySelector(
        ".video-preview-container"
    );

    if (!videoURL) {
        videoPreviewContainer.innerHTML =
            '<small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>';
        return;
    }

    if (isYouTube(videoURL)) {
        const videoId = extractYouTubeId(videoURL);
        if (videoId) {
            showYouTubePreview(videoId);
        } else {
            videoPreviewContainer.innerHTML =
                '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
        }
    } else {
        videoPreviewContainer.innerHTML =
            '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
    }
});

function isYouTube(url) {
    return url.includes("youtube.com") || url.includes("youtu.be");
}

function extractYouTubeId(url) {
    const regex =
        /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S+\/\S+[\?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function showYouTubePreview(videoId) {
    const videoPreviewContainer = document.querySelector(
        ".video-preview-container"
    );
    videoPreviewContainer.innerHTML = `
            <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
        `;
}
