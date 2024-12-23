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
                    <!-- Photo preview container
                    <div id="photoPreview" class="d-flex mb-3" style="gap: 10px;"></div>

                    Add Photo button with icon
                    <label id="addPhotoBox" for="productPhotos" class="d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border: 2px dashed #ccc; border-radius: 5px; cursor: pointer;">
                        <i class="bi bi-plus-circle" style="font-size: 32px; color: #ccc;"></i>
                        <small id="photoCount" class="form-text text-muted">0/9.</small>
                    </label>

                    Add Photo button
                    <input type="file" id="productPhotos" name="productPhotos[]" class="form-control" multiple
                        accept="image/*" required>
                </div>
            </div> -->

                    <!-- Photo preview container -->
                    <div id="photoPreview" class="d-flex mb-3" style="gap: 10px;"></div>

                    <!-- Add Photo button -->
                    <div id="addPhotoBox" class="border border-warning rounded text-center py-3" style="cursor: pointer; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                        <input type="file" id="productPhotos" name="productPhotos[]" class="form-control visually-hidden" multiple accept="image/*">
                        <span id="photoLabel" style="font-size: 24px; color: black; position: absolute; top: 10px;">+</span>
                        <span id="photoLabelText" style="font-size: 14px; color: black; position: absolute; bottom: 10px;">0/9</span>
                    </div>
                </div>
            </div>

            <script>
                document.querySelector('#addPhotoBox').addEventListener('click', function () {
                    document.getElementById('productPhotos').click();
                });

                document.getElementById('productPhotos').addEventListener('change', function () {
                    const photoPreview = document.getElementById('photoPreview');
                    const files = Array.from(this.files);
                    const currentPhotoCount = document.querySelectorAll('#photoPreview img').length;
                    const totalFileCount = currentPhotoCount + files.length;
                    const label = document.getElementById('photoLabelText');

                    if (totalFileCount > 9) {
                        alert('You can only upload up to 9 photos.');
                        return;
                    }

                    label.textContent = `${totalFileCount}/9`;

                    // Hide Add Photo button if reaching the limit
                    if (totalFileCount === 9) {
                        document.getElementById('addPhotoBox').style.display = 'none';
                    }

                    files.forEach((file) => {
                        if (currentPhotoCount + document.querySelectorAll('#photoPreview img').length >= 9) return;

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const photoWrapper = document.createElement('div');
                            photoWrapper.style.position = 'relative';
                            photoWrapper.style.display = 'inline-block';
                            photoWrapper.style.marginRight = '10px';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.width = '100px';
                            img.style.height = '100px';
                            img.style.objectFit = 'cover';
                            img.classList.add('rounded');

                            const trashIcon = document.createElement('i');
                            trashIcon.classList.add('fa', 'fa-trash');
                            trashIcon.style.position = 'absolute';
                            trashIcon.style.top = '5px';
                            trashIcon.style.right = '5px';
                            trashIcon.style.color = 'white';
                            trashIcon.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                            trashIcon.style.borderRadius = '50%';
                            trashIcon.style.cursor = 'pointer';
                            trashIcon.style.padding = '5px';

                            trashIcon.addEventListener('click', function () {
                                photoWrapper.remove();
                                updateFileCount();
                            });

                            photoWrapper.appendChild(img);
                            photoWrapper.appendChild(trashIcon);
                            photoPreview.appendChild(photoWrapper);
                        };
                        reader.readAsDataURL(file);
                    });

                    this.value = ''; // Reset the file input so the user can upload more photos
                });

                function updateFileCount() {
                    const totalPhotos = document.querySelectorAll('#photoPreview img').length;
                    const label = document.getElementById('photoLabelText');
                    label.textContent = `${totalPhotos}/9`;

                    /*if (totalPhotos < 9) {
                        document.getElementById('addPhotoBox').style.display = 'flex';
                    } else {
                        document.getElementById('addPhotoBox').style.display = 'none';
                    }*/
                }
            </script>

            <div class="mb-3">
                <label for="videoLink" class="form-label">Link Video Produk</label>
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
                const videoLinkInput = document.getElementById('videoLink');
                const videoPreviewContainer = document.getElementById('videoPreviewContainer');

                videoLinkInput.addEventListener('input', function () {
                    const videoURL = videoLinkInput.value.trim();

                    // Reset preview jika input kosong
                    if (!videoURL) {
                        videoPreviewContainer.innerHTML = '<small class="text-muted">Pratinjau video akan muncul di sini setelah URL dimasukkan.</small>';
                        return;
                    }
                    // Validasi URL video YouTube dan tampilkan pratinjau
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
