@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
    {{-- Kita akan membuat file CSS baru khusus untuk dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')

 <div>
    <h2 style="
display: flex;
  justify-content: center;
  align-items: center;">Selamat datang, Admin Sakti Produk</h2>
    <p style="
  display: flex;
  justify-content: center;
  align-items: center;">Berikut adalah daftar produk yang sedang menunggu persetujuan Anda.</p>
  </div>

    <table id="productTable">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>NIM</th>
                <th>Nama Pengirim</th>
                <th>Kategori</th>
                <th>Lihat Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->user->username ?? '-' }}</td>
                <td>{{ $product->seller_name ?? '-' }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.products.show', $product->slug) }}" class="action-link">
                        Lihat Produk
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-4">
    {{ $products->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection
