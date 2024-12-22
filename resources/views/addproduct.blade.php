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

            <script>
                // let existingPhotos = []; // To keep track of already uploaded photos

                // // Handle the photo input click
                // document.querySelector('#addPhotoBox')?.addEventListener('click', function() {
                //     const photoInput = document.getElementById('productPhotos');
                //     if (photoInput) {
                //         photoInput.click();
                //     } else {
                //         console.error('Photo input element not found.');
                //     }
                // });

                // document.getElementById('productPhotos').addEventListener('change', function() {
                //     const totalFileCount = document.querySelectorAll('#photoPreview img').length + this.files.length;
                //     const label = document.getElementById('photoLabelText');

                //     if (totalFileCount > 9) {
                //         alert('You can only upload up to 9 photos.');
                //         return;
                //     }

                //     label.textContent = `${totalFileCount}/9`;

                //     if (totalFileCount === 9) {
                //         document.getElementById('addPhotoBox').hidden = true; // Hide the add button
                //     }

                //     // Loop through the selected files and create image previews
                //     const photoPreview = document.getElementById('photoPreview');
                //     const files = Array.from(this.files).slice(0, 9 - document.querySelectorAll('#photoPreview img').length);
                //     files.forEach(file => {
                //         const reader = new FileReader();
                //         reader.onload = ({ target }) => {
                //             const photoWrapper = document.createElement('div');
                //             photoWrapper.style.position = 'relative';
                //             photoWrapper.style.display = 'inline-block';
                //             photoWrapper.style.marginRight = '10px';

                //             const img = document.createElement('img');
                //             img.src = target.result;
                //             img.style.width = '100px';
                //             img.style.height = '100px';
                //             img.style.objectFit = 'cover';
                //             img.classList.add('rounded');

                //             // Create trash icon and append to the image
                //             const trashIcon = document.createElement('i');
                //             trashIcon.classList.add('fa', 'fa-trash');
                //             trashIcon.style.position = 'absolute';
                //             trashIcon.style.top = '5px';
                //             trashIcon.style.right = '5px';
                //             trashIcon.style.color = 'white';
                //             trashIcon.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                //             trashIcon.style.borderRadius = '50%';
                //             trashIcon.style.cursor = 'pointer';
                //             trashIcon.style.padding = '5px';

                //             trashIcon.addEventListener('click', function() {
                //                 // Remove the photo from the preview and reset input
                //                 photoWrapper.remove();
                //                 label.textContent = `${document.querySelectorAll('#photoPreview img').length}/9`;
                //             });

                //             // Append image and trash icon to the wrapper
                //             photoWrapper.appendChild(img);
                //             photoWrapper.appendChild(trashIcon);
                //             photoPreview.appendChild(photoWrapper);
                //         };
                //         reader.readAsDataURL(file);
                //     });

                //     // Reset the file input so user can add more photos
                //     this.value = '';
                // });

                // function updateFileCount() {
                //     const totalPhotos = document.querySelectorAll('#photoPreview img').length;
                //     const label = document.getElementById('photoLabelText');
                //     label.textContent = `${totalPhotos}/9`;

                //     // Show the Add Photo button sif there are fewer than 9 photos
                //     if (totalPhotos < 9) {
                //         document.getElementById('addPhotoBox').style.display = 'flex';
                //     } else {
                //         document.getElementById('addPhotoBox').style.display = 'none'; // Hide if 9 photos are reached
                //     }
                // }
            </script>

            <label for="productVideos" class="form-label">
                Video Produk <span class="text-danger">*</span>
            </label>
            <!-- Video Upload Section -->
            <div id="videoSection">
                <div id="addVideoBox" class="border border-warning rounded text-center py-3"
                    style="cursor: pointer; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                    <input type="file" id="productVideo" name="productVideo" class="form-control d-none"
                        accept="video/*">
                    <span id="videoLabel" style="font-size: 24px; color: black; position: absolute; top: 10px;">+</span>
                    <span id="videoLabelText"
                        style="font-size: 14px; color: black; position: absolute; bottom: 10px;">0/1</span>
                </div>
                <div id="videoPreview" class="mt-3"></div>
            </div>

            <script>
                // Video Upload Logic
                document.getElementById('addVideoBox').addEventListener('click', function() {
                    document.getElementById('productVideo').click();
                });

                document.getElementById('productVideo').addEventListener('change', function() {
                    const videoFile = this.files[0];
                    const label = document.getElementById('videoLabelText');

                    // Check if the video already exists
                    if (document.querySelectorAll('#videoPreview video').length > 0) {
                        alert('Only 1 video can be uploaded.');
                        return;
                    }

                    // Check if a file is selected
                    if (videoFile) {
                        label.textContent = '1/1';

                        // Hide the add video box after one video is uploaded
                        document.getElementById('addVideoBox').style.display = 'none';

                        // Create video preview
                        const videoPreview = document.getElementById('videoPreview');
                        const videoWrapper = document.createElement('div');
                        videoWrapper.style.position = 'relative';
                        videoWrapper.style.display = 'inline-block';

                        const videoElement = document.createElement('video');
                        videoElement.controls = true;
                        videoElement.style.width = '200px';
                        videoElement.style.height = '150px';
                        videoElement.style.objectFit = 'cover';

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            videoElement.src = e.target.result;
                        };
                        reader.readAsDataURL(videoFile);

                        // Create trash icon for removing video
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

                        // Event to remove video
                        trashIcon.addEventListener('click', function() {
                            videoWrapper.remove();
                            document.getElementById('addVideoBox').style.display =
                                'flex'; // Show add video button again
                            label.textContent = '0/1';
                            document.getElementById('productVideo').value = ''; // Reset input file
                        });

                        // Append video and trash icon to the wrapper
                        videoWrapper.appendChild(videoElement);
                        videoWrapper.appendChild(trashIcon);
                        videoPreview.appendChild(videoWrapper);
                    }
                });
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