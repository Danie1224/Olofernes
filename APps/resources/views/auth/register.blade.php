@extends('layouts.app')

@section('title', 'Register - TechStore')

@section('content')
<div class="container mt-4">
    <div class="grid grid-2">
        <div class="card">
            <h2>Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                @if($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">Register</button>
            </form>
            <p class="text-center mt-3">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </p>
        </div>
        <div class="card">
            <h3>Join TechStore</h3>
            <p>Create an account to:</p>
            <ul style="list-style: disc; padding-left: 20px;">
                <li>Save your favorite products</li>
                <li>Track your orders</li>
                <li>Get exclusive deals</li>
                <li>Manage your profile</li>
            </ul>
        </div>
    </div>
</div>
@endsection

