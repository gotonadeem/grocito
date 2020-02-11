$("#step2").hide();
$("#step3").hide();
localStorage.clear();
$("#step1Next").click(function()
{
   var form = $("#step_first");
	form.validate();
	if(form.valid()) {
	if($("input[name=seller_image_value]").val()=="")
 	   {
		  $("#seller_image-error").html("Seller image is required");
		   $("#seller_image-error").css("display","block")
	   }
       else
       {
	var imageData = new FormData($("#step_first")[0]);	
    localStorage.setItem('username', $("input[name=username]").val());
	localStorage.setItem('mobile', $("input[name=mobile]").val());
	localStorage.setItem('address_1', $("textarea[name=address_1]").val());
	localStorage.setItem('address_2', $("textarea[name=address_2]").val());
	localStorage.setItem('email', $("input[name=email]").val());
	localStorage.setItem('country_id', $("#country_list").val());
	localStorage.setItem('state_id', $("#state_list").val());
	localStorage.setItem('city_id', $("#city_list").val());
	localStorage.setItem('profile_image', $("input[name=profile_image]").val());	
	localStorage.setItem('seller_image', $("input[name=seller_image]").val());
	localStorage.setItem('pincode', $("#pincode").val());
	localStorage.setItem('food_license_no', $("#food_license_no").val());
	localStorage.setItem('business_name', $("#business_name").val());
	localStorage.setItem('business_reg_no', $("#business_reg_no").val());
	localStorage.setItem('delivery_pincode', $("#delivery_pincode").val());
	localStorage.setItem('latitude', $("#latitude").val());
	localStorage.setItem('longitude', $("#longitude").val());

	imageData.append('username', localStorage.getItem("username"));
	imageData.append('mobile', localStorage.getItem("mobile"));
	imageData.append('address_1', localStorage.getItem("address_1"));
	imageData.append('address_2', localStorage.getItem("address_2"));
	imageData.append('email', localStorage.getItem("email"));
	imageData.append('country_id', localStorage.getItem("country_id"));
	imageData.append('state_id', localStorage.getItem("state_id"));
	imageData.append('city_id', localStorage.getItem("city_id"));
	imageData.append('profile_image', localStorage.getItem("profile_image"));
	imageData.append('seller_image', localStorage.getItem("seller_image"));
	imageData.append('pincode', localStorage.getItem("pincode"));
	imageData.append('food_license_no', localStorage.getItem("food_license_no"));
	imageData.append('business_name', localStorage.getItem("business_name"));
	imageData.append('business_reg_no', localStorage.getItem("business_reg_no"));
	imageData.append('delivery_pincode', localStorage.getItem("delivery_pincode"));
	imageData.append('latitude', localStorage.getItem("latitude"));
	imageData.append('longitude', localStorage.getItem("longitude"));
	$(".loader_div").show();
	$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/seller/complete-user-profile1',
				method: 'post',
				data:  imageData,
				contentType: false,
				cache : false,
				processData: false,
				success: function (data) {
					response=JSON.parse(data);
					if(response.not_login)
					{
						localStorage.clear();
						 bootbox.alert("Please login and try again");
						  setTimeout(function(){ 
						           window.location='/';
					       },3000);
					}
					if(response.success)
					{
						 localStorage.clear();  
					     $("#step1").hide();
						 $("#step2").show();
						 $(".loader_div").hide();
					}
					if(response.errors)
					{
					  $(".loader_div").hide();
					  $("#email_error_msg").text(response.errors.email);
					  $("#mobile_error_msg").text(response.errors.mobile);
					  $("#fname_error_msg").text(response.errors.f_name);
					  $("#lname_error_msg").text(response.errors.l_name);
					}
				},
				error: function () {
					console.log('There is some error in user deleting. Please try again.');
				}
			});	
	   }
     }
});

$('input[name=seller_image]').change(function(e){
  $in=$(this);
  $("input[name=seller_image_value]").val($in.val());
});
$('input[name=cancel_cheque]').change(function(e){
  $in=$(this);
  $("input[name=cancel_cheque_value]").val($in.val());
});
// $('input[name=cin_image]').change(function(e){
  // $in=$(this);
  // $("input[name=cin_image_value]").val($in.val());
// });
$('input[name=pan_image]').change(function(e){
  $in=$(this);
  $("input[name=pan_image_value]").val($in.val());
});

$('input[name=signature]').change(function(e){
  $in=$(this);
  $("input[name=signature_value]").val($in.val());
});


//Jquery Validation code........................
$("#step_first").validate({
	debug:true,
    rules: {
        username: {
            required: true,
           // extension: "png|jpeg|jpg"
        },

		mobile: {
            required: true,
			number:true,
			maxlength:10,
			minlength:10
        },
		country_id: {
            required: true,
        },
		state_id: {
            required: true,
        },
		city_id: {
            required: true,
        },
		pincode: {
            required: true,
        },
		address_1: {
            required: true,
        },
		address_2: {
            required: true,
        },
		business_reg_no: {
			required: true,
		},
		latitude: {
			required: true,
		},
		longitude: {
			required: true,
		},
    },
    messages: {
        username: {
            required: "Name is required",
        },
		address_2:{
			required:'Address is required',
		},
		address_1:{
			required:'Pickup address is required',
		},
		pincode:{
			required:"Pincode is required",
		},
		city_id:{
			required:"City is required",
		},
		state_id:{
			required:"State is required",
		},
		state_id:{
			required:"State is required",
		},
		business_reg_no:{
			required:"Business reg no is required",
		},
		latitude:{
			required:"Latitude is required",
		},
		longitude:{
			required:"Longitude is required",
		},
    }
});
//Jquery Validation code........................
$("#step_second").validate({
	debug:true,
    rules: {
        account_number: {
            required: true,
        },
		bank_name: {
			required: true,
		},
        ifsc_code: {
            required: true,
        },
		pan_number: {
            required: true,
        },
		account_holder_name: {
            required: true,
        },
		gst_number: {
            required: true,
        },
    },
    messages: {
        account_number: {
            required: "Account number is required",
        },
        ifsc_code: {
            required: "IFSC code is required",
        },
		pan_number:{
			required:"Pan number is required",
		},
		account_holder_name:{
			required:"Account holder name is required",
		},
		gst_number:{
			required:"GST number is required",
			
		},
		bank_name:{
			required:"Bank name is required",

		},
		
		
    }
});

//Jquery Validation code........................
$("#form_step3").validate({
	debug:true,
    rules: {
        tc: {
            required: true,
        },	
    },
    messages: {
        tc: {
            required: "Term and Condition is required",
        },
    }
});

$("#step2Next").click(function()
{
	var form = $("#step_second");
	form.validate();
	if(form.valid()) {
   if($("input[name=cancel_cheque_value]").val()=="")
 	{
		$("#cancel_cheque-error").html("Cancel cheque is required");
	}
	// else if($("input[name=cin_image_value]").val()=="")
	// {
		// $("#cin_image-error").html("Cin image is required");
	// }
	else if($("input[name=pan_image_value]").val()=="")
	{
		$("#pan_image-error").html("Pan image is required");
	}
    else
	{
	 var imageData = new FormData($("#step_second")[0]);	
	localStorage.setItem('account_number', $("input[name=account_number]").val());
	localStorage.setItem('bank_name', $("input[name=bank_name]").val());
	localStorage.setItem('cancel_cheque', $("input[name=cancel_cheque]").val());
	localStorage.setItem('pan_number', $('#pan_number').val());
	localStorage.setItem('account_holder_name', $("input[name=account_holder_name]").val());
	localStorage.setItem('ifsc_code', $("#ifsc_code").val());	
	//localStorage.setItem('cin_image', $("#cin_image").val());	
	localStorage.setItem('pan_image', $("#pan_image").val());	
	localStorage.setItem('gst_number', $("#gst_number").val());	
	
	    imageData.append('pan_image', localStorage.getItem("pan_image"));
        imageData.append('pan_number', localStorage.getItem("pan_number"));
        imageData.append('account_holder_name', localStorage.getItem("account_holder_name"));
        imageData.append('ifsc_code', localStorage.getItem("ifsc_code"));
        //imageData.append('cin_image', localStorage.getItem("cin_image"));
        imageData.append('gst_number', localStorage.getItem("gst_number"));
		imageData.append('cancel_cheque', localStorage.getItem("cancel_cheque"));
        imageData.append('account_number', localStorage.getItem("account_number"));
		$(".loader_div").show();
	     $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/seller/complete-user-profile2',
				method: 'post',
				data:  imageData,
				contentType: false,
				cache : false,
				processData: false,
				success: function (data) {
					response=JSON.parse(data);
					if(response.not_login)
					{
						 localStorage.clear();
						 bootbox.alert("Please login and try again");
						 setTimeout(function(){ 
						           window.location='/';
					       },3000);
					}
					if(response.success)
					{
						 localStorage.clear();  
					     $("#step1").hide();
	                     $("#step2").hide();
	                     $("#step3").show();
						 $(".loader_div").hide();
					}
				},
				error: function () {
					console.log('There is some error in user deleting. Please try again.');
				}
			});	
	   }
	}
});


$("#Submitbtn").click(function(){
	var imageData = new FormData($("#form_step3")[0]);	
	var form = $("#form_step3");
	form.validate();
	if(form.valid()) {
		if($("input[name=tc]").val()=="")
 	    {
		$("#signature-error").html("Please check term and condition");
		  $("#signature-error").css("display","block")
	    }
		else
		{
		//localStorage.setItem('signature', $("input[name=signature]").val());
		//localStorage.setItem('tc', $("input[name=tc]").val());
		$(".loader_div").show();
		///////////////////////////////////////////////////////////////////////
        //imageData.append('signature', localStorage.getItem("signature"));
   	    $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/seller/complete-user-profile3',
				method: 'post',
				//data:  imageData,
				contentType: false,
				cache : false,
				processData: false,
				success: function (data) {
					response=JSON.parse(data);
					if(response.not_login)
					{
						localStorage.clear();
						 bootbox.alert("Please login and try again");
						  setTimeout(function(){ 
						           window.location='/';
					       },3000);
					}
					if(response.success)
					{
						  localStorage.clear();  
						  $(".loader_div").hide();
						  bootbox.alert("Profile has been updated successfully");
						  setTimeout(function(){ 
						 
						           window.location='dashboard';
					       },3000);
						  
					}
				},
				error: function () {
					console.log('There is some error in user deleting. Please try again.');
				}
			});
	   }
	}
});

$("#step1Prev").click(function(){
  $("#step1").show();
  $("#step2").hide();
  
});

$("#step2Prev").click(function(){
  $("#step2").show();
  $("#step3").hide();
  
});


function getPincode(city_id) {
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		url: BASE_URL+'/admin/seller/get-pincode',
		type: 'POST',
		data: {id: city_id },
		success: function (data) {
			$("#delivery_pincode").html(data);
		},
		error: function () {
			console.log('There is some error to get pincode. Please try again.');
		}
	});

}
function imgUpload(value)
{
	  var imageData = new FormData($("#upload_form")[0]);
    $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/update-my-profile',
				method: 'post',
				data:  imageData,
				contentType: false,
				cache : false,
				processData: false,
				success: function (data) {
					response=JSON.parse(data);
					if(response.not_login)
					{
						localStorage.clear();
						 bootbox.alert("Please login and try again");
						  setTimeout(function(){ 
						           window.location='/';
					       },3000);
					}
					if(response.success)
					{
						
						$("#user_image").attr("src",response.path);
					}
				},
				error: function () {
					console.log('There is some error in user deleting. Please try again.');
				}
			});
}

function get_state(value)
{
	 $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/get-state',
        type: 'POST',
        data: {id: value },
        success: function (data) {
            $("#state_list").html(data);
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
    });
}

function get_city(value)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/get-city',
        type: 'POST',
        data: {id: value },
        success: function (data) {
            $("#city_list").html(data);
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
    });
}
