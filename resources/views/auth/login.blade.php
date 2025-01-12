@extends('layouts.app')

@section('title', 'Login - Teknik Informatika')

@section('content')
<section class="py-5">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 700px; min-height: 500px; border-radius: 15px;">
            <div class="card-body" style="padding: 40px;">
                <h2 class="text-center mb-4">Login</h2>

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.submit') }}" novalidate>
                    @csrf

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-control @error('username') is-invalid @enderror" 
                            style="height: 60px;" 
                            placeholder="Masukkan username" 
                            value="{{ old('username') }}" 
                            required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            style="height: 60px;" 
                            placeholder="Masukkan password" 
                            required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Error Message -->
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100" style="height: 60px;">Login</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
