@extends('master.layoutLoginSignup')
@section('title','Login')
@section('content')

<div class="wrapper">
    <div class="image">
      <img src="assets/img/login.png" alt="welcome image">
    </div>
    <div class="form">
        <div class="mt-5">
            @if ($errors->any())
                <div class="col-12">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

        </div>
      <form action="{{route('login.post')}}" method="POST">
        @csrf
        <img src="assets/img/iium-logo.png" alt="IIUM Logo" class="center">
        <h1>Welcome Back!</h1>
        <h6>Unlock the door and let the magic begin</h6>

        <div class="input-box">
          <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-box">
          <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="remember-forgot">
          <label><input type="checkbox"> Remember me</label>
          <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="btn">Login</button>

        <div class="register-link">
          <p>Don't have an account? <a href="registration">Register</a></p>
        </div>
      </form>
    </div>
  </div>




@endsection