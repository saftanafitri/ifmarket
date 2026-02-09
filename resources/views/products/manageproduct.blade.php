@extends('layouts.app')

@section('title', 'Sakti Product - Manage Produk')

@section('content')
    <section class="py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Product</li>
            </ol>
        </nav>

        <h2>Produk Anda</h2>

        <!-- Styling untuk gambar agar ukurannya seragam -->
        <style>
            .product-img {
                width: 120px;
                height: 80px;
                object-fit: cover;
                border-radius: 5px;
            }
        </style>

        <table class="table table-custom mb-5">
            <thead>
                <tr>
                    <th>PRODUCTS</th>
                    <th>DATE ADDED</th>
                    <th>STATUS</th> 
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($product->photos->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->photos->first()->url) }}"
                                     alt="{{ $product->name }}"
                                     class="product-img me-3">
                            @else
                                <img src="{{ asset('default.jpg') }}"
                                     alt="Default Image"
                                     class="product-img me-3">
                            @endif
                            <p class="m-0">{{ $product->name }}</p>
                        </div>
                    </td>
                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $statusClass = match($product->status) {
                                'pending' => 'badge bg-warning',
                                'approved' => 'badge bg-success',
                                'rejected' => 'badge bg-danger',
                                default => 'badge bg-secondary',
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ ucfirst($product->status) }}</span>
                    </td>
                    <td>
                    {{-- Hanya tampilkan tombol jika status BUKAN 'pending' --}}
                    @if ($product->status != 'pending')

                    {{-- Di dalam blok ini, hanya tampilkan tombol Edit jika status 'approved' --}}
                    @if ($product->status == 'approved')
                        <a href="{{ route('products.edit', ['slug' => $product->slug]) }}" class="btn btn-sm btn-secondary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Tombol Hapus akan selalu tampil jika status bukan 'pending' --}}
                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $product->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                        </svg>
                    </button>

                    @endif
                        <!-- Formulir Hapus -->
                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', ['id' => $product->id]) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    let productId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Hapus Produk?',
                        text: "Produk yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + productId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection