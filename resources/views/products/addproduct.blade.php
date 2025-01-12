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
            <div class="d-flex flex-wrap align-items-center" style="gap: 10px; overflow-x: auto;">
                <!-- Add Photo Button -->
                <div id="addPhotoBox" class="border border-warning rounded text-center py-3"
                    style="cursor: pointer; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                    <input type="file" id="productPhotos" name="productPhotos[]" class="form-control" multiple
                        accept="image/*"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    <span id="photoLabel" style="font-size: 24px; color: black; position: absolute; top: 10px;">+</span>
                    <span id="photoLabelText" style="font-size: 14px; color: black; position: absolute; bottom: 10px;">0/9</span>
                </div>

                <!-- Photo preview container -->
                <div id="photoPreview" class="d-flex mb-3" style="gap: 10px;"></div>
            </div>
        </div>

        <script>
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
            </script>

            <div class="mb-3">
            <label for="videoLink" class="form-label">
                Link Video Produk<span class="text-muted"> (Opsional)</span>
            </label>
            <input type="url" class="form-control border-warning" id="videoLink" name="videoLink"
                placeholder="Masukkan URL video YouTube" value="{{ old('videoLink') }}">
            <small id="videoLinkHelp" class="form-text text-muted">
                Masukkan URL video dari YouTube (opsional).
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
                iframe.frameBorder = '0';
                iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;

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
            <div id="seller-container">
                <!-- Render initial seller inputs -->
                @php $oldSellers = old('seller_name', ['']); @endphp
                @foreach ($oldSellers as $index => $sellerName)
                    <div class="mb-3 seller-item">
                        <label class="form-label">Nama Pemilik Produk <span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <input type="text" class="form-control border-warning" 
                                   id="seller_name_{{ $index + 1 }}" 
                                   name="seller_name[]" 
                                   placeholder="Input nama penjual" 
                                   value="{{ $sellerName }}" required>
                            <span class="remove-btn" onclick="removeSeller(this)" title="Hapus Penjual">&times;</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <button type="button" class="btn btn-warning" onclick="addSeller()">Tambah</button>
            </div>
        </div>

        <script>
            let sellerCount = document.querySelectorAll('.seller-item').length || 1;

            // Function to add a new seller input
            function addSeller() {
                sellerCount++;
                const sellerContainer = document.getElementById('seller-container');
                const newSeller = document.createElement('div');
                newSeller.classList.add('mb-3', 'seller-item');
                newSeller.innerHTML = `
                    <label class="form-label">Nama Pemilik Produk <span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <input type="text" class="form-control border-warning" 
                            id="seller_name_${sellerCount}" 
                            name="seller_name[]" 
                            placeholder="Input nama penjual" 
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
                </script>
                
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
                <label for="product_link" class="form-label">
                    Link Produk<span class="text-danger">*</span>
                </label>
                <input type="url" class="form-control border-warning" id="product_link" name="product_link"
                    placeholder="Dapat berupa prototipe atau produk jadi" value="{{ old('product_link') }}" required>
                <small id="product_linkHelp" class="form-text text-muted">
                    Masukkan URL yang valid untuk produk Anda, baik berupa prototipe maupun produk jadi.
                </small>
            </div>

            <button type="submit" class="btn btn-warning px-5">Submit</button>
        </form>
    </section>
@endsection