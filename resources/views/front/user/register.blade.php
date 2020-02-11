@extends('front.layout.front')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<section class="sign-up mb-5">
  <div class="container">
    <h2 class="text-center title-signup my-5">Create New Customer Account</h2>
    <div class="register-form">
      <form action="#" id="register-form" name="register-form">
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label>First Name:</label>
              <input type="text" class="form-control" placeholder="Enter First Name" id="fname" name="fname">
              <span class="error-msg error fnameMsg"></span>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label>Last Name:</label>
              <input type="text" class="form-control" placeholder="Enter Last Name" id="lname" name="lname">
                  <div class="error-msg error lnameMsg"></div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label>Date of Birth:</label>
              <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                <input type="text" id="dob" name="dob" class="form-control" placeholder="DOB" readonly />
                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span> 
                  <span class="error-msg  error dobMsg"></span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label>Gender:</label>
              <select name="gender" class="custom-select">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
              <span class="error-msg  error genderMsg"></span>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label for="email">Email address:</label>
              <input type="email" name="user_email" placeholder="Enter Email Address" class="form-control" id="user_email">
              <span class="error-msg  error emailMsg"></span>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label for="pwd">Password:</label>
              <input type="password" class="form-control" placeholder="Enter password" name="password" id="password">
                <span class="error-msg error passMsg"></span>
               
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label for="pwd">Confirm Password:</label>
              <input type="password" class="form-control" placeholder="Enter Confirm password" name="password_confirmation" id="password_confirmation">
              <span class="error-msg  error confMsg"></span>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label>Mobile Number:</label>
                <input type="number" name="mobile" placeholder="Enter mobile" id="mobile" class="form-control" placeholder="Enter Mobile Number">
                 <span class="error-msg error mobileMsg"></span>
                <!--<button type="submit" class="btn custom-btn">SEND OTP</button>-->
              
            </div>
          </div>
          <div class="col-md-12 col-sm-12">
            <button type="button" class="btn custom-btn w-100 py-2" id="register_user">Register</button>
          </div>
        </div>
      </form>
      <h5 class="text-center my-4 login-account"><a href="{{URL::to('login')}}">Login with an existing account</a></h5>
    </div>
  </div>
</section>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Verify Mobile No</h4>
      </div>
      <div class="modal-body">
         <div class="col-sm-6">
             <label>Enter OTP</label>
             <input type="text" class="form-control" name="otp" id="otp" placeholder="OTP">
             <span class="error" id="otp_msg"></span>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         <button type="button" onclick="verify_otp()" class="btn btn-primary">Verify</button>
      </div>
    </div>

  </div>
</div>

@section('scripts')
    <script language="javascript" src="{{ URL::asset('public/front/js/validation/jquery.validate.min.js') }}"></script>
    <script language="javascript" src="{{ URL::asset('public/front/js/validation/additional-methods.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script> 
    <script language="javascript" src="{{ URL::asset('public/front/developer/js/user_signup.js') }}"></script>
@stop



@endsection