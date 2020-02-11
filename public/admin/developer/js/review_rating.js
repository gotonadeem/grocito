////load the data......................................................
dataTable = $('#rating-table').DataTable({
    fixedHeader: true,  "dom": 'C<"clear">lfrtip',
    "colVis": {
        "buttonText": "View columns"
    },
    "oLanguage": {
        "sProcessing": "<img src='"+ASSET_URL+"front/image/index.rotating-balls-spinner.svg'>"
    },
    "processing": true,
    "serverSide": true,
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/product/getReviewRatingData', // json datasource
        type: "post",  // method  , by default get
        "data": function ( d ) {
           // d.category_id = $("#filter_by_category").val();
        },
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,5] }],
    "order": [[ 2, 'desc' ]]
});