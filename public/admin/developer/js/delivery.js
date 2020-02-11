///delete record...............................
function  get_delivery(type){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/delivery/get-data',
            type: 'POST',
            data: {id:type},
            success: function (data) {
                response= JSON.parse(data);
				$("#radius_charge").val(response.radius_charge);
				$("#radius").val(response.radius);
				$("#out_of_radius_charge").val(response.out_of_radius_charge);
				$("#min_order").val(response.min_order);
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
    }



   