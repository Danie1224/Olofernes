@extends('layouts.app')

@section('title', 'Login - TechStore')

@section('content')
<div class="container mt-4">
    <div class="grid grid-2">
        <div class="card">
            <h2>Login to TechStore</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                @if($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">Login</button>
            </form>
            <p class="text-center mt-3">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </p>
        </div>
        <div class="card">
            <h3>Welcome Back!</h3>
            <p>Sign in to access your account and continue shopping.</p>
            <ul class="mt-3" style="list-style: disc; padding-left: 20px;">
                <li>Track your orders</li>
                <li>View your cart</li>
                <li>Browse products</li>
                <li>Get exclusive vouchers</li>
            </ul>
        </div>
    </div>
</div>
@endsection

