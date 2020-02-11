$("#step2").hide();
$("#step3").hide();
//localStorage.clear();
$("#step1Next").click(function()
{
    localStorage.setItem('f_name', $("input[name=f_name]").val());
	localStorage.setItem('l_name', $("input[name=l_name]").val());
	localStorage.setItem('relation_title', $('input[name=relation_title]:checked').val());
	localStorage.setItem('relation_name', $('input[name=relation_name]').val());
	localStorage.setItem('gender', $("input[name=gender]").val());
	localStorage.setItem('dob', $("input[name=dob]").val());
	localStorage.setItem('doa', $("input[name=doa]").val());
	localStorage.setItem('mobile', $("input[name=mobile]").val());
	localStorage.setItem('whatsapp_no', $("input[name=whatsapp_no]").val());
	localStorage.setItem('address_1', $("textarea[name=address_1]").val());
	localStorage.setItem('address_2', $("textarea[name=address_2]").val());
	localStorage.setItem('address_3', $("textarea[name=address_3]").val());
	localStorage.setItem('email', $("input[name=email]").val());
	localStorage.setItem('country_id', $("#country_list").val());
	localStorage.setItem('state_id', $("#state_list").val());
	localStorage.setItem('city_id', $("#city_list").val());
    $("#step1").hide();
    $("#step2").show();
});


//Jquery Validation code........................
// $("#step1").validate({
    // rules: {
        // f_name: {
            // required: true,
            // extension: "png|jpeg|jpg"
        // },
        // l_name: {
            // required: true,
        // }
    // },
    // messages: {
        // f_name: {
            // required: "First name is required",
        // },
        // l_name: {
            // required: "Last name is required",
        // }
    // }
// });


$("#step2Next").click(function()
{
	localStorage.setItem('occupation', $("input[name=occupation]").val());
	localStorage.setItem('company_name', $("input[name=company_name]").val());
	localStorage.setItem('designation', $('#designation').val());
	localStorage.setItem('department', $("input[name=department]").val());
	localStorage.setItem('experience', $("#experience").val());	
 $("#step1").hide();
 $("#step2").hide();
 $("#step3").show();
});

$("#Submitbtn").click(function(){
	    var imageData = new FormData($("#form_step3")[0]);	
		localStorage.setItem('profile_image', $("input[name=profile_image]").val());
		localStorage.setItem('pan_image', $("input[name=pan_image]").val());
		localStorage.setItem('aadhar_image', $("input[name=aadhar_image]").val());
		localStorage.setItem('aadhar_number', $("input[name=aadhar_number]").val());
		localStorage.setItem('pan_number', $("input[name=pan_number]").val());
		///////////////////////////////////////////////////////////////////////
        imageData.append('f_name', localStorage.getItem("f_name"));
        imageData.append('l_name', localStorage.getItem("l_name"));
        imageData.append('relation_title', localStorage.getItem("relation_title"));
        imageData.append('gender', localStorage.getItem("gender"));
        imageData.append('dob', localStorage.getItem("dob"));
        imageData.append('doa', localStorage.getItem("doa"));
        imageData.append('mobile', localStorage.getItem("mobile"));
        imageData.append('whatsapp_no', localStorage.getItem("whatsapp_no"));
        imageData.append('address_1', localStorage.getItem("address_1"));
        imageData.append('address_2', localStorage.getItem("address_2"));
        imageData.append('address_3', localStorage.getItem("address_3"));
        imageData.append('email', localStorage.getItem("email"));
        imageData.append('country_id', localStorage.getItem("country_id"));
        imageData.append('state_id', localStorage.getItem("state_id"));
        imageData.append('city_id', localStorage.getItem("city_id"));
        imageData.append('occupation', localStorage.getItem("occupation"));
        imageData.append('company_name', localStorage.getItem("company_name"));
        imageData.append('designation', localStorage.getItem("designation"));
        imageData.append('department', localStorage.getItem("department"));
        imageData.append('experience', localStorage.getItem("experience"));
        imageData.append('profile_image', localStorage.getItem("profile_image"));
        imageData.append('pan_image', localStorage.getItem("pan_image"));
        imageData.append('aadhar_image', localStorage.getItem("aadhar_image"));
        imageData.append('aadhar_number', localStorage.getItem("aadhar_number"));
        imageData.append('pan_number', localStorage.getItem("pan_number"));
        imageData.append('relation_name', localStorage.getItem("relation_name"));
		
		$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/update-user-profile',
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
						  bootbox.alert("Profile has been updated successfully");
						  setTimeout(function(){ 
						           window.location='user-dashboard';
					       },3000);
					}
				},
				error: function () {
					console.log('There is some error in user deleting. Please try again.');
				}
			});
		
});

$("#step1Prev").click(function(){
  $("#step1").show();
  $("#step2").hide();
  
});

$("#step2Prev").click(function(){
  $("#step2").show();
  $("#step3").hide();
  
});



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

