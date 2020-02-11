$(function() {
	

    //Please enter valid email
    jQuery.validator.addMethod("validEmail", function(value, element)
    {
        if(value == '')
            return true;
        var temp1;
        temp1 = true;
        var ind = value.indexOf('@');
        var str2=value.substr(ind+1);
        var str3=str2.substr(0,str2.indexOf('.'));
        if(str3.lastIndexOf('-')==(str3.length-1)||(str3.indexOf('-')!=str3.lastIndexOf('-')))
            return false;
        var str1=value.substr(0,ind);
        if((str1.lastIndexOf('_')==(str1.length-1))||(str1.lastIndexOf('.')==(str1.length-1))||(str1.lastIndexOf('-')==(str1.length-1)))
            return false;
        str = /(^[a-zA-Z0-9]+[\._-]{0,1})+([a-zA-Z0-9]+[_]{0,1})*@([a-zA-Z0-9]+[-]{0,1})+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]+)$/;
        temp1 = str.test(value);
        return temp1;
    }, "Please enter valid email.");

	
	
	 $("form[name='contact_form']").validate({
        rules: { 
				   name: {
                      required: true,
				   }, 
				  lname:{
                       required: true,
				   }, 
				   email:{
                       required: true,
					   validEmail:true
				   }, 
				   message:{
                       required: true,
				   },  
				   mobile:{
                       required: true,
				   },
				  
        },
        // Specify validation error messages
        messages: {
            fname: {
				required:"First name price is required",
			},
			lname:{
				required:"Last name price is required",
			},
			email:{
				required:"Email address is required",
			},
			message:{
				required:"Message is required",
			},
            mobile:{
				required:"Mobile no is required",
			},
        }
    });
	
});

 $("#contact_button").click(function()
 {
        var form = $("#contact_form");
        form.validate();
        if(form.valid()) {
            var fname   = $("#fname").val();
            var lname   = $("#lname").val();
            var email   = $("#email").val();
            var mobile  = $("#mobile").val();
            var message = $("#message").val();
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/contact-us',
                data:{fname:fname,lname:lname,email:email,mobile:mobile,message:message},
                success: function (response, textStatus, jqXHR) {
                   response1= JSON.parse(response);
				   if(response1.success)
				   {
					  $("#success").show();
					  var html="<div id='success' class='alert alert-success text-center' style='font-size:16px;'>You query has been submitted successfully</div>";
					  $("#c_message").html(html);
					  setTimeout(function(){ $("#success").hide(); },3000);
				   }
                }
            });
		}		
 });