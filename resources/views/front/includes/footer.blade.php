<footer class="dark-bg pt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-2 col-sm-6 col-6">
        <div class="column1">
          <h4>Grocito</h4>
          <ul class="nav flex-column">
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('about-us')}}">About Us</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('discount-information')}}">Discount Information</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('privacy-policy')}}">Privacy Policy</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('term-condition')}}">Terms and Conditions</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('return-policy')}}">Cancellations and Returns</a> </li>
          </ul>
        </div>
      </div>
      <div class="col-md-2 col-sm-6 col-6">
        <div class="column1">
          <h4>Help</h4>
          <ul class="nav flex-column">
            <li class="nav-item"> <a class="nav-link" href="{{URL::asset('faq')}}">FAQs</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('contact-us')}}">Contact US</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('shipping-delivery')}}">Shipping & Delivery</a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{URL::to('payment-policy')}}">Payment Policy</a> </li>
           <!-- <li class="nav-item"> <a class="nav-link" href="{{--{{URL::to('sitemap')}}--}}">Sitemap</a> </li> -->
          </ul>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="column1 social-link">
          <h4>Get Social With Us</h4>
          <ul class="nav">
            <li class="nav-item"> <a class="nav-link" href="https://www.facebook.com/grocito"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>
            <li class="nav-item"> <a class="nav-link" href="https://www.instagram.com/grocito_online"><i class="fa fa-instagram" aria-hidden="true"></i></a> </li>
          </ul>
          <h4>Download Our App</h4>
         <a href="https://play.google.com/store/apps/details?id=com.grocito.grocito" target="_blank"> <img src="{{URL::asset('public/images/Google-App-store-icon.png')}}" class="img-fluid"> </div></a>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="column1">
          <h4>SUBSCRIBE FOR UPDATES</h4>
          <h6>Get instant updates about our new products and special promos!</h6>

          <div class="subcribe-div">
		    <div id="emsg"></div>
            <input type="text" class="form-control" name="semail" id="semail" placeholder="Your Email Address">
            <button class="btn subscribe-btn" onclick="subscribe()">Subscribe Now</button>
			<div id="ssmsg"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">Copyright © 2019 Grocito Online Pvt. Ltd.</div>
</footer>


<!-- The signup -->
<div class="modal" id="mySignup">
    <div class="modal-md">
        <div class="modal-contents">

            <!-- Modal Header -->
            <div class="modal-header-custom">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="register-form">

                    <div class="page-content">
                        <div class="form-v8-content">
                            <div class="form-left">
                                <img src="{{URL::asset('public/images/registration-form-img.jpg')}}" alt="form" class="img-fluid">
                            </div>
                            <div class="form-right">
                                <div class="auth_msg"></div>
                                <div class="tab">
                                    <div class="tab-inner">
                                        <button class="tablinks" onclick="openCity(event, 'sign-up')" id="defaultOpen">Sign Up</button>
                                    </div>
                                    <div class="tab-inner">
                                        <button class="tablinks" onclick="openCity(event, 'sign-in')">Sign In</button>
                                    </div>
                                </div>
                                <form class="form-detail" id="register-form" action="#" method="post">
                                    <div class="tabcontent" id="sign-up">
                                        <div id="register_section">
                                            <div class="form-row">
                                                <label class="form-row-inner">
                                                    <input type="text" name="fname" id="fname" class="input-text" required>
                                                    <span class="label">First Name <sub>*</sub></span>
                                                    <span class="border"></span>
                                                </label>
                                            </div>

                                            <div class="form-row">
                                                <label class="form-row-inner">
                                                    <input type="text" name="lname" id="lname" class="input-text" required>
                                                    <span class="label">Last Name <sub>*</sub></span>
                                                    <span class="border"></span>
                                                </label>
                                            </div>

                                            <div class="form-row">
                                                <label class="form-row-inner">
                                                    <input type="email" name="user_email" id="user_email" class="input-text" required>
                                                    <span class="label">E-Mail <sub>*</sub></span>
                                                    <span class="border"></span>
                                                    <span class="emailMsg error"></span>
                                                </label>
                                            </div>


                                            <div class="form-row">
                                                <label class="form-row-inner">
                                                    <input type="number" name="mobile" id="mobile" class="input-text" required>
                                                    <span class="label">Mobile Number <sub>*</sub></span>
                                                    <span class="border"></span>
                                                    <span class="mobileMsg error"></span>
                                                </label>
                                            </div>
                                            <div class="form-row">
                                                <label class="form-row-inner">
                                                    <input type="text" name="reff_code" id="reff_code" class="input-text" >
                                                    <span class="label">Referral Code</span>
                                                    <span class="border"></span>
                                                    <span class="refErrorMsg error"></span>

                                                </label>
                                            </div>
                                           {{-- <div class="form-row">
                                                <div id="datepicker" class="form-row-inner input-group date" data-date-format="mm-dd-yyyy">
                                                    <span class="label">DOB <sub>*</sub></span>
                                                    <input class="form-control" type="text" required readonly />
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>--}}


                                            <div class="form-row-last">
                                                <input type="button" name="register" id="register_user" class="register" value="Register">
                                            </div>
                                        </div>
                                        <div id="otp_section">
                                            <div class="form-row otp-text">

                                                <h6>An OTP has been sent to your registered mobile number</h6>
                                                <label class="form-row-inner">
                                                    <input type="number" name="otp" id="otp" class="input-text otp-number" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4" min="1" max="9999" required autofocus>
                                                    <span class="label">Enter OTP</span>
                                                    <span class="border"></span>
                                                </label>

                                                <button type="button" onclick="resend_otp()" class="resendotp">Resend OTP</button>
                                            </div>
                                            <div class="form-row-last">
                                                <input type="button" onclick="verify_otp()" name="register" class="register" value="Verify">
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <form class="form-detail" action="#" id="login-form" method="post">
                                    <div class="tabcontent" id="sign-in">

                                        <div class="form-row" id="login_section">
                                            <label class="form-row-inner">
                                                <input type="number" name="login_mobile" id="login_mobile" class="input-text" required>
                                                <span class="label">Mobile Number</span>
                                                <span class="border"></span>
                                            </label>

                                            <div class="form-row-last">
                                                <input type="button" name="register" id="login_user" class="register" value="Login using OTP">
                                            </div>
                                        </div>



                                        <div id="login_otp_section">
                                            <div class="form-row otp-text">
                                                <h6>An OTP has been sent to your registered mobile number</h6>

                                                <label class="form-row-inner">
                                                    <input type="number" name="login_otp" id="login_otp" class="input-text otp-number" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4" min="1" max="9999" required autofocus>
                                                    <span class="label">Enter OTP</span>
                                                    <span class="border"></span>
                                                </label>

                                                <button type="button" onclick="resend_otp()" class="resendotp">Resend OTP</button>
                                            </div>

                                            <div class="form-row-last">
                                                <input type="button" name="register" onclick="verify_login_otp()" class="register" value="Login using OTP">
                                            </div>
                                        </div>




                                       {{-- <div class="or-line my-4"><span>Or</span></div>

                                        <div class="d-flex justify-content-center social-sign">
                                            <a href="{{URL::to('facebook-login-test')}}"><img src="{{URL::asset('public/images/facebook.png')}}" class="img-fluid"></a>
                                            <a href="{{URL::to('google-login-now')}}"><img src="{{URL::asset('public/images/gsearch.png')}}" class="img-fluid"></a>
                                        </div>--}}


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModalOtp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Verify Mobile No</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-row otp-text">
                        <h6>An OTP has been sent to your registered mobile number</h6>
                        <label class="form-row-inner">
                            <input type="number" id="otp" name="otp" class="input-text otp-number" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4" min="1" max="9999" required autofocus>
                            <span class="label">Enter OTP</span>
                            <span class="border"></span>
                        </label>
                        <button class="resendotp">Resend OTP</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" onclick="verify_otp()" class="btn btn-primary custom-btn">Verify</button>
                </div>
            </div>

        </div>

    </div>
</div>

<div id="myModalResetPassword" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="rsuccessMsg"></div>
            <div class="modal-header-custom">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center title-signup">Reset Password</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <label>Email Address</label>
                    <input type="text" class="form-control" name="remail" id="remail" placeholder="Enter Registered Email address">
                    <span class="error" id="remail_msg"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="reset_password()" class="btn btn-primary custom-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="toast" id="toast-success" data-delay="3000">
      Toast Header
  </div>
<div class="toast" id="toast-error" data-delay="2000">
    Toast Header
</div>



<div class="loader-div" style="display:none;">
<div class="loader-style">
    <svg viewBox="0 0 120 120" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

      <symbol id="s--circle">
        <circle r="10" cx="20" cy="20"></circle>
      </symbol>

      <g class="g-circles g-circles--v1">
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
        <g class="g--circle">
          <use xlink:href="#s--circle" class="u--circle"></use>
        </g>
      </g>
  </svg>




</div>

</div>
<script>
BASE_URL="{{URL::to('/')}}";
</script>

<script src="{{URL::asset('public/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="{{ URL::asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('public/js/price_range_script.js') }}"></script>

<script src="{{ URL::asset('public/js/owl.carousel.js') }}"></script>
<script src="{{ URL::asset('public/js/zoom-image.js') }}"></script>
<script src="{{ URL::asset('public/js/main.js') }}"></script>
<script src="{{ URL::asset('public/js/custom.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/validation/jquery.validate.min.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/validation/additional-methods.min.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/developer/user_signup.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/developer/search.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/developer/user_login.js') }}"></script>
<script language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.js"></script>
<script type="text/javascript" src="{{ URL::asset('public/js/jqueryElevateZoom.js') }}"></script>
<script src="{{ URL::asset('public/js/ubislider.min.js?v=1.0.0.1') }}"></script>

<script src="{{ URL::asset('public/js/developer/cart.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap-datepicker.js') }}"></script>
<!-- Compiled and minified JavaScript -->

<script>

</script>

<script type="text/javascript">
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
function subscribe()
{

    var semail= $("#semail").val();
    if(semail=="" || isEmail(semail)==false)
    {
        $("#emsg").text("Enter valid email address").css('color','red');
    }
    else
    {
		 $("#emsg").html("");
		$(".loader-div").show();
       $("#smsg").text("");
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: BASE_URL + '/subscribe',
            data: {email:semail},
            success: function (response, textStatus, jqXHR) {
				$(".loader-div").hide();
                var data= JSON.parse(response);
                if(data.status)
                {
                  $("#ssmsg").show();
                  $("#ssmsg").text(data.message).css('color','green');
                  setTimeout(function(){
                     $("#ssmsg").hide();
                  },2000);
                }
                else
                 {
                   $("#smsg").text(data.message);
                 }
            },
            error: function(response)
            {
              $(".loader_div").show();
            }
        });
    }
}
</script>

 <script>
function openNav() {
  document.getElementById("collapsibleNavbars").style.width = "100%";
}

function closeNav() {
  document.getElementById("collapsibleNavbars").style.width = "0%";
}

</script>

<script type="text/javascript">
		function openCity(evt, cityName) {
		    var i, tabcontent, tablinks;
		    tabcontent = document.getElementsByClassName("tabcontent");
		    for (i = 0; i < tabcontent.length; i++) {
		        tabcontent[i].style.display = "none";
		    }
		    tablinks = document.getElementsByClassName("tablinks");
		    for (i = 0; i < tablinks.length; i++) {
		        tablinks[i].className = tablinks[i].className.replace(" active", "");
		    }
		    document.getElementById(cityName).style.display = "block";
		    evt.currentTarget.className += " active";
		}

		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	</script>

<script>
  function maxLengthCheck(object) {
    if (object.value.length > object.maxLength)
      object.value = object.value.slice(0, object.maxLength)
  }
  function isNumeric (evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode (key);
    var regex = /[0-9]|\./;
    if ( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>

<script>
  $( function() {
    $( "#datepicker-dob" ).datepicker();
  } );

  $( ".show-toast" ).click(function() {
      $('#toast-error').toast('show').html('Please login first.');
  });
  </script>

<script>

$(function () {
  $("#datepicker").datepicker({
        autoclose: true,
        todayHighlight: true
  });
});
</script>

  <script>
    $('.location-carousel').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:4
            }
        }
    })
  </script>
  <script>
  $("document").ready(function() {
    $('.dropdown-menu').on('click', function(e) {
      if($(this).hasClass('notifaction-list')) {
        e.stopPropagation();
      }
    });
  });
      function updateNotifyViewStatus() {
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type: 'POST',
              url: BASE_URL + '/update-notify-view-status',
              //data: {email:semail},
              success: function (response, textStatus, jqXHR) {

                  $('#bell_count').html(0);
              },
              error: function(response)
              {
                  $(".loader_div").show();
              }
          });
      }
</script>