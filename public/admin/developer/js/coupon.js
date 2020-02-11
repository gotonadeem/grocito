/**
 * Created by wingstud on 10/8/17.
 */
////load the data......................................................
dataTable = $('#coupon-table').DataTable({
        responsive: true,
		serverSide: true,
		"oLanguage": {
        "sProcessing": "<img class='loader' src='"+ASSET_URL+"front/image/index.rotating-balls-spinner.svg'>"
    },
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'pdf', title: 'ExampleFile'},
            {extend: 'print',
                customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
    
    "processing": true,
    "serverSide": true,
	 pageLength: 50,
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/coupon/getCouponData', // json datasource
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#amenity-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,3] }],
    "order": [[ 2, 'desc' ]]
});

///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the coupon?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/coupon/delete/'+id,
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

   