@extends('master.layoutRegisterSignup')
@section('title','Login')
@section('content')

<div class="wrapper">
    <div class="image">
      <img src="assets/img/registration.png" id="regis" alt="welcome image">
    </div>
    <div class="form">
      <form action="">
        <img src="assets/img/iium-logo.png" alt="IIUM Logo" class="center">
        <h1>Create Account</h1><br>
        <h6>Sign up now for more excitement!</h6>

        <div class="input-box">
          <input type="text" placeholder="First Name" required>
          <i class='bx bxs-envelope'></i>
        </div>

        <div class="input-box">
            <input type="text" placeholder="Last Name" required>
            <i class='bx bxs-envelope'></i>
          </div>

          <div class="input-box">
            <input type="text" placeholder="Email" required>
            <i class='bx bxs-envelope'></i>
          </div>

        <div class="input-box">
          <input type="password" placeholder="Password" required>
          <i class='bx bxs-lock-alt'></i>
        </div>

        <div class="remember-forgot">
          <label><input type="checkbox"> Keep me logged in</label>
          <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="btn">Sign Up</button>

        <div class="register-link">
          <p>Already registered? <a href="#">Register</a></p>
        </div>
      </form>
    </div>
  </div>




@endsection
