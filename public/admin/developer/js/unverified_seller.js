

////load the data......................................................
    dataTable = $('#users-table').DataTable({
     pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
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
        "oLanguage": {
            "sProcessing": "<img class='loader' src='"+ASSET_URL+"front/image/index.rotating-balls-spinner.svg'>",
			
        },
        "processing": true,
        "serverSide": true,
		"order": [], //Initial no order.
         "language": {
                "searchPlaceholder": "name,email"
            },
        "ajax":{
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : BASE_URL+'/admin/seller/getUnverifiedSellerData', // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
                $(".user-table-error").html("");
                $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                //$("#user-table_processing").css("display","none");
            }
        },
        "columnDefs": [ { orderable: false, targets: [0,5] }],
        "order": [[ 2, 'desc' ]]
    });
    //$('.dataTables_filter input').attr("placeholder", "search with name.");
   // $('.dataTables_filter input').attr("class", "clearable");
///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the user ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/seller/delete',
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
