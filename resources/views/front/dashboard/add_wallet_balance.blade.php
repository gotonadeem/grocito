@extends('front.layout.front')
@section('content')
<section class="myAccount my-5">
		<div class="alert alert-danger col-6 m-auto"  role="alert" style="display: none">
		</div>
	<div class="alert alert-success col-6 m-auto"  role="alert" style="display: none">
	</div>
  <div class="container">
    <h2 class="shoping-cart-text">Add wallet amount</h2>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="account-info">
         
          {{--<div class="title">
            <h3 class="m-0">Address Book <a href="#" class="maddress float-right"><span>Manage Addresses</span></a></h3>
          </div>--}}
		    
          <div class="block-content">
          <div class="row">


          
            <div class="col-8 offset-2">
            <div class="box box-shipping-address">
						  <div class="box-content">
								 <div class="my-profile-content">
				  

							<div class="row">
							  <div class="col-md-6 col-sm-6">
								<div class="form-group row align-items-center">
									<div class="col-4">
										<label>Amount<sub>*</sub></label>
									</div>
									<div class="col-8">
										<input type="number" class="form-control"  placeholder="Enter Amount" id="amount" name="amount" min="1">
										<span class="amount-error error" style="display:none;">This field is required</span>
									</div>
								</div>
							  </div>

							</div>
							<div class="col-md-12 col-sm-12 text-right">
								<button type="type" class="btn custom-btn py-2" onclick="razorpayPayment()">Submit </button>
							</div>


				  </div>
              </div>
            </div>
            </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@section('scripts')
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script>
		function razorpayPayment() {

			var SITEURL = '{{URL::to('')}}';
			var amount  = $('#amount').val();
			if(amount ==''){
				$('.amount-error').show();
				return false;
			}
			$('.amount-error').hide();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			var totalAmount = amount;
			var options = {
				"key": "rzp_live_3Psac1T1qEddGl",
				"amount": (totalAmount * 100), // 2000 paise = INR 20
				"name": "Grocito",
				"description": "Payment",
				"image": '<?php echo asset('public/images/logo2.svg')?>',
				"handler": function (response) {

					$(".loader-div").show();
					$.ajax({
						url: SITEURL + '/wallet-razorpay-success',
						type: 'post',
						dataType: 'json',
						data: {
							razorpay_payment_id: response.razorpay_payment_id,
							totalAmount: totalAmount,
						},
						success: function (data) {
							if(data.success)
							{
								if(data.success_code== 1)
								{
									setTimeout(function(){
										$('.alert-success').html('Amount Added successfully!').show();
										location.replace(SITEURL+'/my-wallet');

									}, 3000);
									}



							}else
							{
								if(data.success_code== 0)
								{
									setTimeout(function(){
										$('.alert-danger').html('Failed! Please try again').show();
										location.replace(SITEURL+'/my-wallet');
									}, 2000);


								}
							}
							$(".loader-div").hide();
							/// window.location.href = SITEURL + '/razorpay-success';
						}
					});

				},
				"modal": {
					"ondismiss": function(){
						setTimeout(function(){
							$('.alert-danger').html('Failed! Please try again').show();
							location.replace(SITEURL+'/my-wallet');
						}, 2000);
						//window.location.replace(SITEURL+'/my-wallet');
					}
				},
				"prefill": {
					"contact": '<?php echo Auth::user()->mobile ?>',
					"email": '<?php echo Auth::user()->email ?>',
				},
				"theme": {
					"color": "#528FF0"
				}
			};
			var rzp1 = new Razorpay(options);
			rzp1.open();
			e.preventDefault();

		}
		/* $("#modal-close").click(function(){
		 $(this).removeClass("close");
		 alert("The paragraph was clicked.");
		 return false;
		 });*/
		/*document.getElementsClass('buy_plan1').onclick = function(e){
		 rzp1.open();
		 e.preventDefault();
		 }*/
	</script>
@stop
@endSection
