@extends('front.layout.front')

@section('content')

  <section class="banner ab-us"><img src="{{URL::asset('public/images/grocerybanner.jpg')}}" class="img-fluid">
    <h2 class="text-center">Contact us</h2>
  </section>
  <section class="contact-us-sec my-sm-5 my-3">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-sm-7">
          <div class="row">
            <div class="img-contact w-100 text-center"> <img src="{{URL::asset('public/images/contact-img.png')}}" class="img-fluid"> </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="adress-div">
                <h5>Grocito Online Private Limited</h5>
                {{--<p>Address:- J979, Raja Colony -dausa,  </br>--}}
                <p>
                  Mobile No:- <a href="tel:9672345662">9672345662</a>
                  </br>
                  Email:-<a href="mailto:support@grocito.com"> support@grocito.com </a>
                </p>
              </div>
            </div>
          {{--  <div class="col-md-6 col-sm-6">
              <div class="adress-div">
                <h5>Address 2:</h5>
                <p>Shop no: 206 - 208, </br>
                  Mansarover Plaza , </br>
                  vijaypath Mansarover , Jaipur</p>
              </div>
            </div>--}}
          </div>
        </div>
        <div class="col-md-5 col-sm-5">
          <div class="quick-connect">
            <h5 class="text-center" style="font-weight:300;">Message us and we’ll </br>get back to you asap</h5>
            <div class="contact-form">
              <div id="c_message"></div>
              <form action="javascript:void(0)" id="contact_form" name="contact_form" novalidate>
                <div class="form-group">
                  <label for="name">First Name</label>
                  <input type="text" class="form-control" placeholder="Enter First name" id="fname" name="fname">
                </div>
				<div class="form-group">
                  <label for="name">Last Name</label>
                  <input type="text" class="form-control" placeholder="Enter last name" id="lname" name="lname">
                </div>
                <div class="form-group">
                  <label for="email">Email address:</label>
                  <input type="email" class="form-control" placeholder="Enter email" id="email" name="email">
                </div>
                <div class="form-group">
                  <label for="mobile">Mobile</label>
                  <input type="number" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile">
                </div>
                <div class="form-group">
                  <label for="comment">Query:</label>
                  <textarea class="form-control" name="comment" rows="5" placeholder="Enter comment" id="comment"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                  <button type="button" id="contact_button" class="btn btn-custom">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="map-sec">
    <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.538153185857!2d75.76453041482023!3d26.8546381831526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db5a42595f835%3A0x8c29f103bc2827bd!2sGrocito+Online+Private+Limited+Plaza%2C+Ward+27%2C+Agarwal+Farm%2C+Barh+Devariya%2C+Mansarovar%2C+Jaipur%2C+Rajasthan+302020!5e0!3m2!1sen!2sin!4v1560929163525!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>-->
    </section>
@section('scripts')
<script language="javascript" src="{{ URL::asset('/public/js/validation/jquery.validate.min.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/js/developer/contact_us.js') }}"></script>
@stop


@endsection