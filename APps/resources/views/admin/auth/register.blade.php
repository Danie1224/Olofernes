@extends('layouts.app')

@section('title', 'Admin Register')

@section('content')
<div class="container" style="max-width: 600px; margin: 3rem auto;">
    <div class="card">
        <h2 class="mb-3">Register Admin</h2>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.register.submit') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus />
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required />
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required />
            </div>

            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required />
            </div>

            <div class="d-flex justify-between align-center">
                <button class="btn" type="submit">Register</button>
                <a href="{{ route('admin.login') }}" class="btn btn-secondary">Have an account? Sign in</a>
            </div>
        </form>
    </div>
</div>
@endsection
