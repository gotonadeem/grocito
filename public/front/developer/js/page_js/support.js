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
	 $("form[name='support_form']").validate({
        rules: { 

				   subject:{
                       required: true,
				   },
			complaint_message:{
                       required: true,
				   },      
				  
        },

        // Specify validation error messages

        messages: {


			subject:{
				required:"Subject is required",
			},

			complaint_message:{
				required:"Message no is required",
			},
			
        }
    });
});

jQuery("#submit").click(function()
{



	var form = $("#support_form");
           form.validate();
        if(form.valid()) {
			$(".loader_div").show();
		   $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST',
				data: $("#support_form").serialize(),
				url: BASE_URL + '/user-support',
				success: function (response, textStatus, jqXHR) {
					$(".lds-roller").hide();
					if(response.status)
					{
				          $("#addAccount").modal("hide");
						location.reload();
						 // swal("Thanks! ",response.message,"success",3000)
						  //setTimeout(function(){ location.reload(); },1000);
						  
					}
					else
					{
					      $("#addAccount").modal("hide");
						  //swal("Oops! ",response.message,"warning",3000)
						
					}

				}

			});
		}
		else
		{
				$("body").removeClass("loader");
	            $(".lds-roller").css("display","none");
		}
});


