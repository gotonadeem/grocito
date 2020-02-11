@extends('front.layout.front')
@section('content')
<style>
 .hide_tr{ display:none;}
 .show_tr{ display:block;}
</style> 
<section class="login-page my-sm-5 my-3">
          <div class="container">
          <div class="row">
          <div class="col-md-6 col-sm-6">
            <div class="register-form">
		  <h2>Sign Up</h2>
          {{ Form::open(array('url' =>'checkout-register','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label>First Name:</label>
                  <input type="text" class="form-control" placeholder="Enter First Name" id="fname" name="fname">
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label>Last Name:</label>
                  <input type="text" class="form-control" placeholder="Enter Last Name" id="lname" name="lname">
				  
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="email">Email address:</label>
                  <input type="email" class="form-control" name="email" id="email">
				  <span class="emailMsg error"></span>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label>Mobile Number:</label>
                  <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile Number">
				  <span class="mobileMsg error"></span>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="pwd">Password:</label>
                  <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="pwd">Confirm Password:</label>
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter Confirm password">
                </div>
              </div>
              <div class="col-md-12 col-sm-12">
                <button type="submit" name="signup" value="signup" class="btn custom-btn w-100 py-2">Signup</button>
              </div>
            </div>
          </form>
          <h5 class="text-center my-4 login-account"><a data-toggle="modal" data-target="#myLogin" data-dismiss="modal" href="#">Login with an existing account</a></h5>
        </div>
            </div>
            
            <div class="col-md-6 col-sm-6">
            
            <div class="login-img">
            
            <img src="{{ URL::asset('public/images/sign-up.png') }}" class="img-fluid">            
            </div>
            
            </div>
            </div>
          </div>
        </section>
@section('scripts')
<script language="javascript" src="{{ URL::asset('public/front/js/validation/jquery.validate.min.js') }}"></script>
<script language="javascript" src="{{ URL::asset('public/front/js/validation/additional-methods.min.js') }}"></script>
<script src="{{ URL::asset('public/front/js/developer/checkout.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
   $('input[name="wallet-check"]').click(function(){
	    if($(this).prop("checked") == true){
                $(".hide_tr").css("display","block");
                $(".hide_tr").css("display","table-row");
				var item_total=$("#item_total").val();
				var s_wallet= $("#wallet_amount").val();
				var value= parseFloat(item_total)-parseFloat(s_wallet);
				$("#order_total").text(value);
				$("#s_wallet").text(s_wallet);
            }
            else if($(this).prop("checked") == false){
                $(".hide_tr").css("display","none");
				var item_total=$("#item_total").val();
				$("#order_total").text(item_total);
            }
  });
});

 function qty_increment(id)
     {
        var qty= parseInt($(".increment_"+id).val()) + 1;
         $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: BASE_URL + '/checkout/checkout-total',
        data: {cart_id:id,qty:qty},
        success: function (response, textStatus, jqXHR) {
            $("#checkout-total").html(response);
        },
        error: function(response)
        {
            $(".loader_div").show();
        }
      });

    }
	
	function qty_decrement(id)
     {
        var qty= parseInt($(".increment_"+id).val()) -1;
         $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: BASE_URL + '/checkout/checkout-total-minus',
        data: {cart_id:id,qty:qty},
        success: function (response, textStatus, jqXHR) {
            $("#checkout-total").html(response);
        },
        error: function(response)
        {
            $(".loader_div").show();
        }
      });

    }
	
$(function () {
	
	
        $("#shipCart").click(function () {
            if ($(this).is(":checked")) {
                $("#shippBlock").hide();
               
            } else {
                $("#shippBlock").show();
              
            }
        });
    });
</script>

@stop
@endSection