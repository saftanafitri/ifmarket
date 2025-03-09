@extends('layouts.app')

@section('title', 'Teknik Informatika - Tambah Produk')

@section('content')
<section class="py-5">
    <h2 class="mb-4">Tambah Produk</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="productPhotos" class="form-label">Foto Produk <span class="text-danger">*</span></label>
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
                                   placeholder="Input nama pemilik produk" 
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
        <script src="{{ asset('product/addproduct.js') }}"></script>
    </section>
@endsection