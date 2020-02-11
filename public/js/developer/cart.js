localStorage.clear();
$(document).ready(function() {
	getCartCount();
	get_cart();
});
function getCartCount() {
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/cart-count',
		cache: false,
		success: function (response, textStatus, jqXHR) {
			$("#cart_count").text(response.cart_count);
		},
		error: function(response)
		{
			console.log(response);
		}

	});
}
function listing_product_add_to_cart(product_id)
{
	var color="default";
	var quantity= $("#quantity-"+product_id).val();
	var itemId = $(".get-item-id-"+product_id+" option:selected").val();
	var seller_id = $(".get-seller-id-"+product_id+" option:selected").val();
	var is_return= $("#is-return-"+product_id).val();
	var is_exchange= $("#is-exchange-"+product_id).val();
	var product_color= parseInt($(".product_color_"+product_id).attr('id'));
	var color= $('#color-'+product_id+':checked').val();

	//var size= $("input[name='product_radio']:checked").val().split(",");
	if(product_color==1)
	{
		if(color==undefined)
		{
			/*html="<div id='success-alert-'+product_id class='alert alert-danger alert-dismissible'>";
			html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			html+="Please Select Color</div>";
			$("#color_error_"+product_id).html(html);
			$("#success-alert-"+product_id).fadeTo(2000, 500).slideUp(500, function(){
				$("#success-alert").slideUp(500);

			});*/
			$("#color_error_"+product_id).show();
			return false;
		}

	}
	$("#color_error_"+product_id).hide();
	if(product_id==null)
	{
		console.log('required product id');

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
		$(".loader-div").show();
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'POST',
			url: BASE_URL + '/cart/store-cart',
			data: {qty:quantity,seller_id:seller_id,product_id:product_id,item_id:itemId,is_return:is_return,is_exchange:is_exchange,color:color},
			cache: false,
			success: function (response, textStatus, jqXHR) {
				$(".loader-div").hide();
				if(response.status==1)
				{
					//$("#cart_header").css('display',"block");
					$("#cart_count").text(response.cart_count);
					$('#toast-success').toast('show').html('Successfully added '+response.item_name +' '+response.product_name+ ' to the basket.');
					get_cart();
				}
				if(response.status==2)
				{
					$('#toast-error').toast('show').html('Already In Cart.');
				}
				if(response.status==3)
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
								clear_user_cart();
								//bootbox.alert('kkkkk');
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
}
function add_to_cart()
{
	var color="default";
	var quantity= $("#quantity").val();
	var product_id= $("#product_id").val();
	var is_return= $("#is_return").val();
	var is_exchange= $("#is_exchange").val();
	var itemId = $(".item option:selected").val();
	var seller_id = $("input[name='seller_id']:checked").val();
	var product_color= parseInt($(".product_color").attr('id'));
	var color= $("input[name='color']:checked").val();
	if(product_color==1)
	{
		if(color==null)
		{
			html="<div id='success-alert' class='alert alert-danger alert-dismissible'>";
			html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			html+="Please Select Color</div>";
			$("#color_error").html(html);
			$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
				$("#success-alert").slideUp(500);

			});
			return false;
		}

	}
	if(product_id==null)
	{
		console.log('required product id');

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
			$(".loader-div").show();
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'POST',
				url: BASE_URL + '/cart/store-cart',
				data: {qty:quantity,seller_id:seller_id,product_id:product_id,item_id:itemId,is_return:is_return,is_exchange:is_exchange,color:color},
				cache: false,
				success: function (response, textStatus, jqXHR) {
					$(".loader-div").hide();
					if(response.status==1)
					{
						//$("#cart_header").css('display',"block");
						$("#cart_count").text(response.cart_count);
						$('#toast-success').toast('show').html('Successfully added '+response.item_name +' '+response.product_name+ ' to the basket.');
						get_cart();
					}
					if(response.status==2)
					{
						$('#toast-error').toast('show').html('Already In Cart.');
					}
					if(response.status==3)
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
									clear_user_cart();


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
}
//clear all data
function clear_user_cart() {
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/clear-user-cart',

		cache: false,
		success: function (response, textStatus, jqXHR) {
			$(".loader-div").hide();
			if(response.status==1)
			{
				getCartCount();
				get_cart();
				//$("#cart_count").text(response.cart_count);
			}
		}
	});
}
function get_cart()
{

	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/get-cart',
		cache: false,
		success: function (response, textStatus, jqXHR) {
			$("#cart_header").html(response);
		},
		error: function(response)
		{
			$(".loader-div").show();
		}

	});
}

function cart_qty_increment(id)
{
	$(".loader-div").show();
	var qty= parseInt($(".qty_"+id).val()) + 1;
	var itemId= $('.item_id_'+id).val();
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/cart-plus',
		data: {cart_id:id,qty:qty,itemId:itemId},
		success: function (data) {
			$(".loader-div").hide();
			response=JSON.parse(data);
			if(response.status == 1) {
				get_cart();
			}else if(response.status == 2){
				$('.qty_'+id).val(qty-1);
				$('.cart_stock_error_'+id).html('Only '+(qty-1)+' items available in stock.').show();
			}
		},
		error: function(response)
		{
			$(".loader_div").show();
		}
	});

}

function cart_qty_decrement(id)
{
	$(".loader-div").show();
	var qty= parseInt($(".qty_"+id).val()) - 1;
	if(qty < 1){
		delete_cart(id);
		return false;
	}
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/cart-minus',
		data: {cart_id:id,qty:qty},
		success: function (response, textStatus, jqXHR) {
			$(".loader-div").hide();
			get_cart();
		},
		error: function(response)
		{
			$(".loader_div").show();
		}
	});

}
function delete_cart(id)
{
	$(".loader-div").show();
	var cart_id= id;
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		url: BASE_URL + '/cart/cart-delete',
		data: {cart_id:cart_id},
		dataType:'json',
		success: function (response, textStatus, jqXHR) {
			$(".loader-div").hide();
			if(response.status==1)
			{

				$("#cart_"+cart_id).html("");
				get_cart();
				$("#cart_count").text(response.cart_count);
			}
			else
			{
				var successContent = "<div class='alert alert-danger'>Please Try again</div>";
				$('#cart_message').html(successContent);
			}
			setTimeout(function(){  $('#cart_message').html(""); },2000);
		},
		error: function(response)
		{
			$(".loader_div").show();
		}
	});

}


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





