@extends('front.layout.front')
@section('content')
<section class="sign-up mb-5">
  <div class="container">
    <h2 class="text-center title-signup mt-5">Welcome</h2>
    <p class="text-center login-subtext">Please enter your details for login into your accounts</p>
    <div class="register-form login-form">
      <div id="regSuccessMessage"></div>
      <form action="#" id="login-form" name="login-form">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label for="email">Email address:</label>
              <input type="email" class="form-control" name="login_email" id="login_email" placeholder="Email Address/Mobile No. (login with OTP)">
               <span id="login_emailMsg error "></span>
            </div>
          </div>
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label for="pwd">Password:</label>
              <input type="password" class="form-control" placeholder="Enter password" placeholder="Password" id="login_password" name="login_password">
              <span id="login_passwordMsg error "></span>
            </div>
          </div>
          <div class="col-md-12 col-sm-12">
            <button type="button" id="login_user"  class="btn custom-btn w-100 py-2">Login</button>
          </div>
        </div>
      </form>
      <div class="text-center my-3 or"><span>OR</span></div>
      <div class="d-flex justify-content-between flex-wrap my-3 social-login-btn">
        <button class="btn facbook-btn"><i class="fa fa-facebook" aria-hidden="true"></i>Login With Facebook</button>
        <button class="btn google-btn"><i class="fa fa-google-plus" aria-hidden="true"></i>Login With Google</button>
      </div>
      <div class="d-flex justify-content-between my-4 reset-or-create"><a href="#"><span>Reset Password</span></a><a href="{{URL::to('register')}}"><span>create an Account</span></a></div>
    </div>
  </div>
</section>
@section('scripts')
    <script language="javascript" src="{{ URL::asset('public/front/js/validation/jquery.validate.min.js') }}"></script>
    <script language="javascript" src="{{ URL::asset('public/front/js/validation/additional-methods.min.js') }}"></script>
    <script language="javascript" src="{{ URL::asset('public/front/developer/js/user_login.js') }}"></script>
@stop
@endsection