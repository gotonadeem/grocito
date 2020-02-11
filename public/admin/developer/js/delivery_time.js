////load the data......................................................
dataTable = $('#time-table').DataTable({
        responsive: true,
		serverSide: true,
		"oLanguage": {
        "sProcessing": "<img class='loader' src='"+ASSET_URL+"front/image/index.rotating-balls-spinner.svg'>"
    },
    "processing": true,
    "serverSide": true,
	 pageLength: 50,
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/getDeliveryTimeData', // json datasource city/getCityData
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#amenity-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,1] }],
    "order": [[ 1, 'desc' ]]
});


///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the city pincode ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/city/delete_city/'+id,
            type: 'POST',
            data: {},
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

function get_city(value)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/admin/seller/get-city',
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



