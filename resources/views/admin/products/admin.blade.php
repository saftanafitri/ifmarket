@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard - Produk Pending</h2>

    <!-- Tampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel produk yang pending -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nama Produk</th>
                <th scope="col">Kategori</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        <!-- Form untuk menyetujui produk -->
                        <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <!-- Form untuk menolak produk -->
                        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
