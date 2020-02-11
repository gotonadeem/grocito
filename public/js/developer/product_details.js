 localStorage.clear(); 
 function add_to_cart()
	{
		var color="";
        var quantity= $("#quantity").val();
        var color= $("#color").val();
        var size= $("input[name='product_radio']:checked").val();
        var product_id= $("#product_id").val();
        if(size==null)
        {
        	
                  html="<div id='success-alert' class='alert alert-danger alert-dismissible'>";
                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                  html+="Please Select size</div>";
				  $("#size_error").html(html);
				  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
					$("#success-alert").slideUp(500);
					
				   });
        	
        }
        else
        {
         var p_status=localStorage.getItem('pincode_status');
		 if(p_status)
		 {
			 $(".loader-div").show();
        $.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					url: BASE_URL + '/cart/store-cart',
					data: {qty:quantity,size:size,product_id:product_id},
				   cache: false,
					success: function (response, textStatus, jqXHR) {
						 $(".loader-div").hide();
						if(response.status==1)
 						{		
                           					
							$("#cart_count").text(response.cart_count);
						}
						if(response.status==2)
 						{		
                            bootbox.confirm({
								message:response.message,
								buttons: {
									confirm: {
										label: 'Clear Now',
										className: 'btn-success'
									},
									cancel: {
										label: 'No',
										className: 'btn-danger'
									}
								},
								callback: function (result) {
									if(result==true)
									{
										$(".loader-div").show();
										var quantity= $("#quantity").val();
										var color= $("#color").val();
										var size= $("input[name='product_radio']:checked").val();
										var product_id= $("#product_id").val();
										
									$.ajax({
												headers: {
													'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
												},
												type: 'POST',
												url: BASE_URL + '/cart/clear-user-cart',
												data: {qty:quantity,size:size,product_id:product_id},
                                                cache: false,
												success: function (response, textStatus, jqXHR) {
													 $(".loader-div").hide();
													if(response.status==1)
													{						
														$("#cart_count").text(response.cart_count);
													}
											    }
									     });
									
								     }
								   }
							});
							
						}
						
						
					},
					error: function(response)
					{
						$(".loader-div").show();
					}
					
				});
		 }
		 else
		 {
			 jQuery("#pmsg").text("Please check Pincode").css('color','red'); 
		 }
       }
	}
	
jQuery(document).ready(function()
{
	jQuery("#check_delivery").click(function()
	{
	   var pincode= jQuery("#delivery_code").val().trim();
	   var product_weight= jQuery("#product_weight").val().trim();
	   var seller_id= jQuery("#seller_id").val().trim();
	   if(pincode=="")
	   {
		  jQuery("#pmsg").text("Enter Pincode").css('color','red'); 
	   }
	   else
	   {
		   $(".loader-div").show();
	    $.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					url: BASE_URL + '/check-delivery',
					data: {pincode:pincode,seller_id:seller_id,product_weight:product_weight},
				    cache: false,
					success: function (response, textStatus, jqXHR) {
						$(".loader-div").hide();
						if(response.status)
						{						
					         jQuery("#pmsg").text(response.message).css('color','green');
							 $("#add_to_cart").removeAttr('disabled');
							 localStorage.setItem('pincode_status',1);
						}
						else
						{
							$("#add_to_cart").attr('disabled','disabled');
							jQuery("#pmsg").text(response.message).css('color','red');
						}
					},
					error: function(response)
					{
						$(".loader_div").show();
					}
					
				});
	   }
	});
	
});	

function add_wishlist(id)
{
	var product_id= id;    
    $.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/wishlist-add',
		data: {product_id:product_id},
		dataType:'json',
		success: function (response, textStatus, jqXHR) {
		    
			if(response.status==1)
		     {
		         bootbox.alert(response.message);
				 $(".wishlist_section").html('<i class="fa fa-heart favrite_data" aria-hidden="true"></i>');
		     }
		     else if(response.status==2)
		     {
		     	$("#myLogin").modal('show');
		     }
			 else
			 {
				bootbox.alert(response.message);
			 }
		},
		error: function(response)
		{
			$(".loader_div").show();
		}
      });
}
	

