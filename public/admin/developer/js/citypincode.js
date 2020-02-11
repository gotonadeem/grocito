////load the data......................................................
dataTable = $('#pincode-table').DataTable({
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
        url : BASE_URL+'/admin/city/getPincodeData', // json datasource
        type: "post",  // method  , by default get
         "data": function ( d ) {
                d.city_id = city_id;
            },
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
    
    var result = confirm("Are you sure you want to delete this pincode ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/city/delete/'+id,
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



