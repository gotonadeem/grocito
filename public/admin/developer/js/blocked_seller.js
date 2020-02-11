////load the data......................................................
    dataTable = $('#users-table').DataTable({
       pageLength: 25,
        responsive: true,
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
            url : BASE_URL+'/admin/seller/getBlockedSellerData', // json datasource
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

$(document).ready(function()
{
	//Jquery Validation code........................
$("#mail_form").validate({
	debug:true,
    rules: {
		email: {
            required: true,
        },
		subject: {
            required: true,
        },
		message: {
            required: true,
        },	
    },
    messages: {
        email: {
            required: "Email address is required",
        },
        subject: {
            required: "Subject is required",
        },
		message:{
			required:'Message is required',
		},
    }
});

});

	function send_email(email)
		{
			$("#email").val(email);
			$("#myModal").modal("show");
		}
		function send_email_to_seller()
		{
			
				var form = $("#mail_form");
	                form.validate();
	             if(form.valid()) {
					 var email= $("#email").val();
			         var subject= $("#subject").val();
			         var message= $("#message").val();
				 $.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: BASE_URL+'/admin/seller/send-email',
						type: 'POST',
						data: {email:email,subject:subject,message:message},
						success: function (data) {
							response=JSON.parse(data);
							if(response.status)
							{
								  html="<div id='success-alert' class='alert alert-success alert-dismissible'>";
                                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                                  html+=""+response.message+"</div>";
								  $("#msg").html(html);
								  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
									$("#success-alert").slideUp(500);
									$("#myModal").modal("hide");
								   });
								   
							}
							else
							{
								  html="<div id='danger-alert' class='alert alert-danger alert-dismissible'>";
                                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                                  html+=""+response.message+"</div>";
								  $("#msg").html(html);
								  $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
									$("#danger-alert").slideUp(500);
									$("#myModal").modal("hide");
								   });
								
							}
							
							
						},
						error: function () {
							console.log('There is some error in user deleting. Please try again.');
						}
					});
			}
		}
function subadmin(value1,value2)
{

	var result = confirm("Are you sure you want assign subadmin?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/seller/subadmin-permission',
            type: 'POST',
            data: {subadmin_id:value1,seller_id:value2},
            success: function (data) {
				
				if(data.status_code)
				{
					  html="<div id='success-alert' class='alert alert-success alert-dismissible'>";
					  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
					  html+=""+data.message+"</div>";
					  $("#success_alert").show();
					  $("#success_alert").html(html);
					  $("#success_alert").fadeTo(2000, 500).slideUp(500, function(){
						$("#success_alert").slideUp(500);
					   });
				}
				//alert(data);
               // location.reload();
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
        return false;
    }
}	