///load the data......................................................

    dataTable = $('#users-table').DataTable({
         // pageLength: 25,
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

		"order": [], //Initial no order.

         "language": {

                "searchPlaceholder": "name,email"

            },

        "ajax":{

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            url : BASE_URL+'/admin/delivery-boy-payment', // json datasource

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
	