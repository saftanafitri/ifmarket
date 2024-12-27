@extends('layouts.app')

@section('title', 'Teknik Informatika - Tambah Produk')

@section('content')
    <section class="py-5">
        <h2 class="mb-4">Tambah Produk</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="productPhotos" class="form-label">
                    Foto Produk <span class="text-danger">*</span>
                </label>
                <div class="d-flex flex-wrap align-items-center" style="gap: 10px; flex-wrap: nowrap; overflow-x: auto;">
                    <!-- Photo preview container -->
                    <div id="photoPreview" class="d-flex mb-3" style="gap: 10px;"></div>

                    <!-- Add Photo button -->
                    <input type="file" id="productPhotos" name="productPhotos[]" class="form-control" multiple
                        accept="image/*" required>
                        <!-- <div id="addPhotoBox" class="border border-warning rounded text-center py-3"
                            style="cursor: pointer; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                            <input type="file" id="productPhotos" name="productPhotos[]" class="form-control" multiple
                                accept="image/*" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0;">
                            <span id="photoLabel" style="font-size: 24px; color: black; position: absolute; top: 10px;">+</span>
                            <span id="photoLabelText" style="font-size: 14px; color: black; position: absolute; bottom: 10px;">0/9</span>
                        </div> -->
                </div>
            </div>

            <div class="mb-3">
                <label for="videoLink" class="form-label">
                    Link Video Produk<span class="text-danger">*</span>
                </label>
                <input type="url" class="form-control border-warning" id="videoLink" name="videoLink"
                    placeholder="Masukkan URL video YouTube" value="{{ old('videoLink') }}" required>
                <small id="videoLinkHelp" class="form-text text-muted">
                    Masukkan URL video dari YouTube.
                </small>
                <!-- Tempat untuk menampilkan pratinjau video -->
                <div id="videoPreviewContainer" class="mt-3">
                    <small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>
                </div>
            </div>

            <script>
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
                        iframe.frameborder = '0';
                        iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
                        iframe.allowfullscreen = true;

                        videoPreviewContainer.innerHTML = ''; // Hapus konten lama
                        videoPreviewContainer.appendChild(iframe);
                    }
                </script>

            <div class="mb-3">
                <label class="form-label" for="name">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-warning" id="name" name="name" placeholder="Input"
                    value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="category_id">Kategori Produk <span class="text-danger">*</span></label>
                <select class="form-select border-warning" id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $items)
                        <option value="{{ $items->id }}" {{ isset($items->id) || old('id') ? 'selected' : '' }}>
                            {{ $items->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Deskripsi Produk <span class="text-danger">*</span></label>
                <textarea class="form-control border-warning" id="description" name="description" rows="3" placeholder="Input"
                    required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="seller_name">Nama Penjual <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-warning" id="seller_name" name="seller_name"
                    placeholder="Input" value="{{ old('seller_name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control border-warning" id="email" name="email"
                    placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="socialMedia" class="form-label">Media Sosial</label>
                <input type="url" class="form-control border-warning mb-2" id="instagram" name="instagram"
                    placeholder="Link Instagram" value="{{ old('instagram') }}">
                <input type="url" class="form-control border-warning mb-2" id="linkedin" name="linkedin"
                    placeholder="Link LinkedIn" value="{{ old('linkedin') }}">
                <input type="url" class="form-control border-warning" id="github" name="github"
                    placeholder="Link GitHub" value="{{ old('github') }}">
            </div>

            <div class="mb-3">
                <label for="productLink" class="form-label">
                    Link Produk<span class="text-danger">*</span>
                </label>
                <input type="url" class="form-control border-warning" id="productLink" name="productLink"
                    placeholder="Dapat berupa prototipe atau produk jadi" value="{{ old('productLink') }}" required>
                <small id="productLinkHelp" class="form-text text-muted">
                    Masukkan URL yang valid untuk produk Anda, baik berupa prototipe maupun produk jadi.
                </small>
            </div>

            <button type="submit" class="btn btn-warning px-5">Submit</button>
        </form>
    </section>
@endsection