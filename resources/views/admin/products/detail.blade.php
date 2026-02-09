
@extends('layouts.admin')
@section('title', 'Admin Detail Produk ')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admindetail.css') }}">
@endpush

{{-- Ini adalah bagian konten utama dari halaman --}}
@section('content')
<div id="detail-product-page">
    <h1>Detail dari Produk {{ $product->name }}</h1>

    <table>
        <tr><th>NIM</th><td>{{ $product->user->username ?? '-' }}</td></tr>
        <tr><th>Nama Produk</th><td>{{ $product->name }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $product->description }}</td></tr>
        <tr><th>Email</th><td>{{ $product->email }}</td></tr>
        <tr><th>Instagram</th><td><a href="{{ $product->instagram }}" target="_blank">{{ $product->instagram }}</a></td></tr>
        <tr><th>LinkedIn</th><td><a href="{{ $product->linkedin }}" target="_blank">{{ $product->linkedin }}</a></td></tr>
        <tr><th>GitHub</th><td><a href="{{ $product->github }}" target="_blank">{{ $product->github }}</a></td></tr>
        <tr><th>Link Produk</th><td><a href="{{ $product->product_link }}" target="_blank">{{ $product->product_link }}</a></td></tr>
        <tr><th>Kategori</th><td>{{ $product->category->name ?? '-' }}</td></tr>
        <tr><th>Nama Pengirim</th><td>{{ $product->seller_name }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($product->status) }}</td></tr>

        <tr>
            <th>Foto</th>
            <td>
                <div class="media-grid">
                    @foreach ($product->photos as $photo)
                    <img src="{{ asset('storage/' . $photo->url) }}" alt="Foto Produk" class="preview-image">
                    @endforeach
                </div>
            </td>
        </tr>
        <tr>
            <th>Video</th>
            <td>
                @if ($product->video)
                    @php
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $product->video, $matches);
                        $youtubeId = $matches[1] ?? null;
                    @endphp

                    @if ($youtubeId)
                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" allowfullscreen></iframe>
                    @else
                        <p>Link video tidak valid</p>
                    @endif
                @else
                    <p>Tidak ada video</p>
                @endif
            </td>
        </tr>
    </table>

    <div id="imageModal" class="modal">
        <span class="close-modal">×</span>
        <img class="modal-content" id="modalImage">
    </div>

    <div class="action-buttons">
        <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger reject">Reject</button>
        </form>
    </div>
</div>
@endsection

{{-- Menambahkan JavaScript khusus hanya untuk halaman ini --}}
@push('scripts')
    <script src="{{ asset('js/admindetail.js') }}"></script>
@endpush