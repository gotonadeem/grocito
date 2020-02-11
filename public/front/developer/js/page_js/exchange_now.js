function exchange_now(value)
{
	value= value.split(",");
	localStorage.setItem('order_id',value[1]);
	localStorage.setItem('meta_id',value[2]);
	$("#myModalExchange").modal("show");
	$("#order_id").html("<b>"+value[0]+"</b>");
}

$("#submit_exchange").click(function()
{
	$("#submit_exchange").attr("disabled","disabled");
   var reason= $("#exchange_reason").val();
   if(reason=="")
   {
	   $("#exchange_reason_msg").html("select reason");
   }
   else
   {
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'POST',
			url: BASE_URL + '/exchange-now',
			data:{reason:reason,order_id:localStorage.getItem('order_id'),meta_id:localStorage.getItem('meta_id')},
			success: function (response, textStatus, jqXHR) {
			    $(".loader-div").hide();
			   if(response.status_code)
			   {
				  $("#success").show();
				  $(".exchange_btn").hide();
				 
				  var html="<div id='success' class='alert alert-success text-center' style='font-size:16px;'>Your exchange request has been submitted successfully.</div>";
				  $("#ex_message").html(html);
				  setTimeout(function(){ 
				   $("#myModalExchange").modal("hide");
				  $("#success").hide(); },3000);
			   }
			}
		});
   }
});