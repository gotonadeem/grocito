		function get_state(value)
{
	 $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/get-state-data',
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
        url: BASE_URL+'/get-city-data',
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

	   jQuery.validator.addMethod("phoneno", function(phone_number, element) {
    	    phone_number = phone_number.replace(/\s+/g, "");
    	    return this.optional(element) || phone_number.length > 9 && 
    	    phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    	}, "<br />Please specify a valid phone number");
	
	
	 $("form[name='join_us_form']").validate({
        rules: { 
				   name: {
                      required: true,
				   },
            business_name: {
                required: true,
            },
            mobile: {
                      required: true,
                      phoneno: true,
                      maxlength: 10,
				   },
				   
				   country_id:{
                       required: true,
				   },  				   
				   
				   state_id:{
                       required: true,
				   }, 
				   city_id:{
                       required: true,
				   },
				   address_2:{
                       required: true,
				   },
				   pincode:{
                       required: true,
				   },
				  
        },
        // Specify validation error messages
        messages: {  
			name:{
				required:"Business name is required",
			},
            business_name:{
                required:"Name is required",
            },
			country_id:{
				required:"Country is required",
			},

			state_id:{
				required:"State is required",
			},
			city_id:{
				required:"City is required",
			},
            mobile:{
				required:"Mobile no is required",
			},
			address_2:{
				required:"Address is required",
			},
			pincode:{
				required:"Pincode is required",
			},
        },
		 submitHandler: function(form) {
            form.submit();
        }
    });
	
});
