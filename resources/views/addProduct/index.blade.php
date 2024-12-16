@extends('layouts.app')

@section('title', 'Teknik Informatika - Tambah Produk')

@section('content')
    <section class="py-5">
        <h2 class="mb-4">Add Products</h2>
        <form>
            <div class="col-md-6">
                <label for="productPhotos" class="form-label">
                    Foto Produk <span class="text-danger">*</span>
                </label>
                <div class="border border-warning rounded text-center py-3" style="cursor: pointer;">
                    <input
                        type="file"
                        id="productPhotos"
                        name="productPhotos[]"
                        class="form-control d-none"
                        multiple
                        accept="image/*"
                        required>
                    <span id="photoLabel">Tambahkan Foto (0/9)</span>
                </div>
            </div>
            <script>
                document.querySelector('.border').addEventListener('click', function() {
                    document.getElementById('productPhotos').click();
                });

                document.getElementById('productPhotos').addEventListener('change', function() {
                    const fileCount = this.files.length;
                    const label = document.getElementById('photoLabel');
                    label.textContent = `Tambahkan Foto (${fileCount}/9)`;
                });
            </script>
                <div class="col-md-6">
                    <label class="form-label" for="productVideo">Video Produk</label>
                    <div class="border border-warning rounded text-center py-3" style="cursor: pointer;">
                        <input
                            type="file"
                            id="productVideo"
                            name="productVideo"
                            class="form-control d-none"
                            accept="video/*">
                        <span id="videoLabel">Tambahkan Video</span>
                        @if (optional($product)->video)
                        <video width="320" height="240" controls>
                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                    </div>
                </div>
                <script>
                    document.querySelector('.border').addEventListener('click', function() {
                        document.getElementById('productVideo').click();
                    });

                    document.getElementById('productVideo').addEventListener('change', function() {
                        const fileName = this.files[0] ? this.files[0].name : "Tambahkan Video";
                        document.getElementById('videoLabel').textContent = fileName;
                    });
                </script>
            <div class="mb-3">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-warning" placeholder="Input">
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori Produk <span class="text-danger">*</span></label>
                <select class="form-select border-warning">
                    <option>Pilih kategori</option>
                    <option>Kerja Praktik (KP)</option>
                    <option>Tugas Akhir (TA)</option>
                    <option>Penelitian</option>
                    <option>Pengabdian pada Masyarakat</option>
                    <option>Tugas Kuliah</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                <textarea class="form-control border-warning" rows="3" placeholder="Input"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Penjual <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-warning" placeholder="Input">
            </div>
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-warning" placeholder="Email">
            </div>
            <div class="mb-3">
                <label for="socialMedia" class="form-label">
                    Media Sosial
                </label>
                <input
                    type="url"
                    class="form-control border-warning mb-2"
                    id="instagram"
                    name="instagram"
                    placeholder="Link Instagram"
                    aria-describedby="socialMediaHelp">
                <input
                    type="url"
                    class="form-control border-warning mb-2"
                    id="linkedin"
                    name="linkedin"
                    placeholder="Link LinkedIn"
                    aria-describedby="socialMediaHelp">
                <input
                    type="url"
                    class="form-control border-warning"
                    id="github"
                    name="github"
                    placeholder="Link GitHub"
                    aria-describedby="socialMediaHelp">
            </div>

            <div class="mb-3">
                <label for="productLink" class="form-label">
                    Link Produk<span class="text-danger">*</span>
                </label>
                <input
                    type="url"
                    class="form-control border-warning"
                    id="productLink"
                    name="productLink"
                    placeholder="Dapat berupa prototipe atau produk jadi"
                    required
                    aria-describedby="productLinkHelp">
                <small id="productLinkHelp" class="form-text text-muted">
                    Masukkan URL yang valid untuk produk Anda, baik berupa prototipe maupun produk jadi.
                </small>
            </div>

            <button type="submit" class="btn btn-warning px-5">Submit</button>
        </form>

    </section>
@endsection
