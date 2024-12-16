@extends('layouts.app')

@section('title', 'Teknik Informatika - Login')

@section('content')
    <section class="py-5">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="card shadow-sm" style=" width: 700px;min-height: 500px; border-radius: 15px;">
                <div class="card-body "style= "padding: 40px;">
                    <h2 class="text-center mb-4">Login</h2>
                    <form method="POST"
                        action="{{ route('login') }}"
                             class="needs-validation"novalidate="">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" style="height: 60px;" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" style="height: 60px;" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn custom-btn w-100" style="height: 60px;">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
