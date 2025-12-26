@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="container" style="max-width: 480px; margin: 3rem auto;">
    <div class="card">
        <h2 class="mb-3">Admin Login</h2>
        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus />
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required />
            </div>
            <div class="d-flex justify-between align-center">
                <button class="btn" type="submit">Sign In</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
