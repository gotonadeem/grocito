function return_now(value)
{
	value= value.split(",");
	localStorage.setItem('order_id',value[1]);
	localStorage.setItem('meta_id',value[2]);
	$("#myModalReturn").modal("show");
	$("#order_id").html("<b>"+value[0]+"</b>");
}

$("#submit_return").click(function()
{
	$("#submit_return").attr("disabled","disabled");
   var reason= $("#reason").val();
   if(reason=="")
   {
	   $("#reason_msg").html("select Reason");
   }
   else
   {
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'POST',
			url: BASE_URL + '/return-now',
			data:{reason:reason,order_id:localStorage.getItem('order_id'),meta_id:localStorage.getItem('meta_id')},
			success: function (response, textStatus, jqXHR) {
			    $(".loader-div").hide();
			   if(response.status_code)
			   {
				  $("#success").show();
				  $(".return_btn").hide();
				 
				  var html="<div id='success' class='alert alert-success text-center' style='font-size:16px;'>Your return request has been submitted successfully.</div>";
				  $("#c_message").html(html);
				  setTimeout(function(){ 
				   $("#myModalReturn").modal("hide");
				  $("#success").hide(); },3000);
			   }
			}
		});
   }
});