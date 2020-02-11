$(document).ready(function(){
	$(".pay_now").click(function()
	{
		var value=$(this).attr('id');
		localStorage.setItem('details',value);
		$("#myModal").modal('show');
	});
	
	$("#send_payment").click(function()
	{
	   var value= localStorage.getItem('details');	
	   var transaction_id= $('#transaction_id').val();	
	   var bank_name= $('#bank_name').val();	
	   var data= value.split(',');

		if(transaction_id ==''){
			$('.transaction_error').show();
			return false;
		}
		if(bank_name ==''){
			$('.bank_error').show();
			$('.transaction_error').hide();
			return false;
		}
		$('.bank_error').hide();
		$('.transaction_error').hide();
		var result = confirm("Are you sure you want to pay now?");
       if (result) {
			 $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: BASE_URL+'/admin/payment/pay-now',
				type: 'POST',
				data: {id: data[2],shippedDate:data[0],total_payable_amount:data[1],totalAdminCommission:data[3],tcs_amount:data[4],transaction_id:transaction_id,bank_name:bank_name},
				success: function (data) {
					response= JSON.parse(data);
					if(response.status)
					{
					   location.reload();
					}
				},
				error: function () {
					console.log('There is some error in to pay. Please try again.');
				}
			});
			return false;
          } 	
    });
});
///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the Product ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/product/delete',
            type: 'POST',
            data: {id: id },
            success: function (data) {
                location.reload();
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
    }
}

///delete record...............................
function get_payment_details(value){
   
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/payment/payment-details',
            type: 'POST',
            data: {id: value },
            success: function (data) {
               $("#data_details").html(data);
			   $("#myModal2").modal('show');
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
}

         function submit_payment()
		   {
			   
			    var result = confirm("Are you sure you want to pay now?");
               if (result) {
				   var user_id= $("#user_id").val();
				   var start_date= $("#start_date").val();
				   var end_date= $("#end_date").val();
				   var amount= $("#s_amount").val();
				   var bank_name= $("#s_bank_name").val();
				   var transaction_id= $("#s_transaction_id").val();
				   var week_number= $("#week_number").val();
					   $.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: BASE_URL+'/admin/payment/settle-now',
						type: 'POST',
						data: {week_number:week_number,transaction_id:transaction_id,bank_name:bank_name,amount:amount,start_date:start_date,user_id:user_id,end_date:end_date},
						success: function (data) {
							response= JSON.parse(data);
							if(response.status)
							{
							   location.reload();
							}
						},
						error: function () {
							console.log('There is some error in user deleting. Please try again.');
						}
					});
					return false;
				
			   }
			
		   }

