@extends('layouts.app')

@section('title', 'Sakti Product - Edit Produk')

@section('content')
<section class="py-5">
    <h2 class="mb-4">Edit Produk</h2>
    <form action="{{ route('products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Metode PUT untuk update -->

        <div class="mb-3">
            <label for="productPhotos" class="form-label">Foto Produk</label>
            <div class="d-flex flex-wrap align-items-center" style="gap: 10px; overflow-x: auto;">
                <!-- Foto produk yang sudah ada -->
               @foreach ($product->photos as $photo)
                <div id="photo-{{ $photo->id }}" class="position-relative" style="width: 100px; height: 100px;">
                    <img src="{{ route('private.file', ['path' => 'public/' . $photo->url]) }}" alt="Foto Produk"
                        class="img-thumbnail" style="width: 100px; height: 100px;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                            onclick="deletePhoto({{ $photo->id }})">x</button>
                </div>
            @endforeach
                <!-- Input untuk menambah foto baru -->
                <div id="addPhotoBox" class="border border-warning rounded text-center py-3"
                    style="cursor: pointer; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; position: relative;">
                    <input type="file" id="productPhotos" name="productPhotos[]" class="form-control" multiple
                        accept="image/*"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    <span id="photoLabel" style="font-size: 24px; color: black; position: absolute; top: 10px;">+</span>
                    <span id="photoLabelText" style="font-size: 14px; color: black; position: absolute; bottom: 10px;">
                        {{ count($product->photos) }}/9
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="videoLink" class="form-label">Link Video Produk (Optional)</label>
            <input type="url" class="form-control border-warning" id="videoLink" name="video"
                placeholder="Masukkan URL video YouTube" value="{{ old('video', $product->video) }}">
        </div>
        
        <!-- Preview Video -->
        <div class="video-preview-container">
            @if ($product->video)
                <iframe src="https://www.youtube.com/embed/{{ Str::afterLast($product->video, 'v=') }}"
                    frameborder="0" allowfullscreen></iframe>
            @else
                <small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label" for="name">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" class="form-control border-warning" id="name" name="name"
                value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="category_id">Kategori Produk <span class="text-danger">*</span></label>
            <select class="form-select border-warning" id="category_id" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">Deskripsi Produk <span class="text-danger">*</span></label>
            <textarea class="form-control border-warning" id="description" name="description" rows="3"
                required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Pemilik Produk <span class="text-danger">*</span></label>
            <div id="seller-container">
                @foreach ($product->sellers->sortBy('id') as $index => $seller)
                    <div class="mb-3 seller-item">
                        <div class="d-flex">
                            <input type="hidden" name="seller_id[]" value="{{ $seller->id }}">
                            <input type="text" class="form-control border-warning"
                                name="seller_name[]" value="{{ old('seller_name.' . $index, $seller->name) }}" required>
                            <span class="remove-btn text-danger" style="cursor: pointer; margin-left: 10px;" onclick="removeSeller(this)">×</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <button type="button" class="btn btn-warning" onclick="addSeller()">Tambah</button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control border-warning" id="email" name="email"
                value="{{ old('email', $product->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="socialMedia" class="form-label">Media Sosial</label>
            <input type="url" class="form-control border-warning mb-2" name="instagram"
                value="{{ old('instagram', $product->instagram) }}" placeholder="Link Instagram">
            <input type="url" class="form-control border-warning mb-2" name="linkedin"
                value="{{ old('linkedin', $product->linkedin) }}" placeholder="Link LinkedIn">
            <input type="url" class="form-control border-warning" name="github"
                value="{{ old('github', $product->github) }}" placeholder="Link GitHub">
        </div>

        <div class="mb-3">
            <label for="product_link" class="form-label">Link Produk<span class="text-danger">*</span></label>
            <input type="url" class="form-control border-warning" id="product_link" name="product_link"
                value="{{ old('product_link', $product->product_link) }}" required>
        </div>

        <button type="submit" class="btn btn-warning px-5">Update Produk</button>
    </form>
</section>

<!-- Custom CSS untuk Preview Video -->
<style>
    .content-wrapper {
        display: flex;
        flex-wrap: wrap; /* Responsif untuk layar kecil */
    }

    .video-preview-container {
        flex: 1;
        max-width: 560px;
        aspect-ratio: 16 / 9;
    }

    .video-preview-container iframe {
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    .content-details {
        flex: 2;
    }
</style>

<script>
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
    fetch("{{ route('products.photos.delete', ['slug' => $product->slug, 'photoId' => ':photoId']) }}".replace(':photoId', photoId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('photo-' + photoId).remove();
            updatePhotoCount();
        } else {
            alert(data.message || 'Gagal menghapus foto. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus foto.');
    });
}

// Tambah Preview Foto Baru Sebelum Upload
document.getElementById('productPhotos').addEventListener('change', function (event) {
    const photoContainer = document.querySelector('.d-flex.flex-wrap');
    const files = event.target.files;
    let existingPhotos = document.querySelectorAll('.position-relative').length;

    if (files.length + existingPhotos > 9) {
        alert('Maksimal 9 foto diperbolehkan.');
        return;
    }

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const photoId = 'new-' + Math.random().toString(36).substr(2, 9);
            const photoElement = document.createElement('div');
            photoElement.id = photoId;
            photoElement.classList.add('position-relative');
            photoElement.style.width = '100px';
            photoElement.style.height = '100px';

            photoElement.innerHTML = `
                <img src="${e.target.result}" alt="Foto Baru"
                    class="img-thumbnail" style="width: 100px; height: 100px;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onclick="removeNewPhoto('${photoId}')">x</button>
            `;

            photoContainer.insertBefore(photoElement, document.getElementById('addPhotoBox'));
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
    let totalPhotos = document.querySelectorAll('.position-relative').length;
    document.getElementById('photoLabelText').innerText = totalPhotos + "/9";
}

// Pastikan angka diperbarui saat halaman dimuat pertama kali
document.addEventListener("DOMContentLoaded", updatePhotoCount);

// JS video
<!-- JavaScript untuk Preview Video -->
    document.getElementById('videoLink').addEventListener('input', function () {
        const videoURL = this.value.trim();
        const videoPreviewContainer = document.querySelector('.video-preview-container');

        if (!videoURL) {
            videoPreviewContainer.innerHTML = '<small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>';
            return;
        }

        if (isYouTube(videoURL)) {
            const videoId = extractYouTubeId(videoURL);
            if (videoId) {
                showYouTubePreview(videoId);
            } else {
                videoPreviewContainer.innerHTML = '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
            }
        } else {
            videoPreviewContainer.innerHTML = '<small class="text-danger">URL tidak valid atau bukan video YouTube.</small>';
        }
    });

    function isYouTube(url) {
        return url.includes("youtube.com") || url.includes("youtu.be");
    }

    function extractYouTubeId(url) {
        const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S+\/\S+[\?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }

    function showYouTubePreview(videoId) {
        const videoPreviewContainer = document.querySelector('.video-preview-container');
        videoPreviewContainer.innerHTML = `
            <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
        `;
    }
</script>
@endsection