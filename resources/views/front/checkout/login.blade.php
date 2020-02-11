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
            <div class="register-form login-form">
			<div> @if(Session::has('success_message'))
				<p class="alert alert-info">{{ Session::get('success_message') }}</p>
				@endif</div>
            <h2>Login</h2>
            
             {{ Form::open(array('url' =>'checkout-login','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="email">Email address:</label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" aria-required="true" aria-invalid="true">
					  <span id="login_emailMsg error "></span>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="pwd">Password:</label>
                      <input type="password" class="form-control valid" placeholder="Password" id="login_password" name="password" aria-required="true" aria-invalid="false">
					  <span id="login_passwordMsg error "></span>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <button type="submit" value="login" id="login" name="login" class="btn custom-btn w-100 py-2">Login</button>
                  </div>
                </div>
              </form>
              <div class="d-flex justify-content-between my-4 reset-or-create"><a href="#"><span>Reset Password</span></a><a data-toggle="modal" data-target="#mySignup" data-dismiss="modal" href="#"><span>create an Account</span></a></div>
            </div>
            </div>
            
            <div class="col-md-6 col-sm-6">
            
            <div class="login-img">
            
            <img src="{{ URL::asset('public/images/login-img.png') }}" class="img-fluid">            
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