function get_rider(id,order_id,sellerId)
{
    $('#d_seller_id').val(sellerId);
	$('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/get-rider',
            type: 'POST',
            data: {id: id },
            success: function (data) {
                $("#data").html(data);
				$("#myModal").modal('show'); 
				$("#order_id").val(order_id); 
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
      
}

$("#assign_rider").submit(function(event){
    event.preventDefault(); //prevent default action 
	var id=$("#rider_id").val(); 
	var order_id=$("#order_id").val(); 
	var seller_id=$("#d_seller_id").val();
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/assign-rider',
            type: 'POST',
            data: {id: id,order_id:order_id,seller_id:seller_id},
            success: function (data) {
				//alert(data)
                location.reload();
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
});