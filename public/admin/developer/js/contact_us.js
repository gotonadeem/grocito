/**
 * Created by wingstud on 10/8/17.
 */
$(function() {
    //Please enter valid email
    jQuery.validator.addMethod("validEmail", function(value, element)
    {
        if(value == '')
            return true;
        var temp1;
        temp1 = true;
        var ind = value.indexOf('@');
        var str2=value.substr(ind+1);
        var str3=str2.substr(0,str2.indexOf('.'));
        if(str3.lastIndexOf('-')==(str3.length-1)||(str3.indexOf('-')!=str3.lastIndexOf('-')))
            return false;
        var str1=value.substr(0,ind);
        if((str1.lastIndexOf('_')==(str1.length-1))||(str1.lastIndexOf('.')==(str1.length-1))||(str1.lastIndexOf('-')==(str1.length-1)))
            return false;
        str = /(^[a-zA-Z0-9]+[\._-]{0,1})+([a-zA-Z0-9]+[_]{0,1})*@([a-zA-Z0-9]+[-]{0,1})+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]+)$/;
        temp1 = str.test(value);
        return temp1;
    }, "Please enter valid email.");

    $("form[name='add_user']").validate({
        rules: {
            email: {
                required: true,
                email: true,
                validEmail: true,
            },
            name: {
                required: true,
            },
            password: {
                required: true,
                // alphanumeric: true,
                minlength: 6,
                maxlength: 20
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            }
        },
        // Specify validation error messages
        messages: {
            email: "Please enter a valid email address",
            password: {
                minlength: "Password must be at least 6 characters long",
                maxlength: "Password cannot be longer than 20 characters"
            },
            password_confirmation: {
                equalTo: "Please enter the confirm password as password"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

////load the data......................................................
dataTable = $('#conatct-us-table').DataTable({
    fixedHeader: true,  "dom": 'C<"clear">lfrtip',
    "colVis": {
        "buttonText": "View columns"
    },
    "oLanguage": {
        "sProcessing": "<img src='"+ASSET_URL+"admin/images/loading.gif'>"
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
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/site-setting/getQueryFormData', // json datasource
        type: "post",		
        error: function(){ 
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,3] }],
    "order": [[ 2, 'desc' ]]
});

$('#date_filter').on('click', function(e) {
       dataTable.draw();
   });
//$('.dataTables_filter input').attr("placeholder", "search with name.");
// $('.dataTables_filter input').attr("class", "clearable");
///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the notice ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/notice/delete',
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

// CLEARABLE INPUT
function change_status(value)
{
    alert(value)
}
function depositModel(id)
{
	localStorage.setItem('user_id',id);
	$("#depositModel").modal('show');
}
$("#success_alert_deposite").css('display','none');
$("#danger_alert_deposite").css('display','none');
$("#success_alert_withdraw").css('display','none');
$("#danger_alert_withdraw").css('display','none');

function withdrawModel(id)
{
	localStorage.setItem('user_id',id);
	$("#withdrawModel").modal('show');
}

 // Wait for the DOM to be ready
    $(function() {
        $("form[name='deposite_form']").validate({
            // Specify validation rules
            rules: {
                amount: "required"

            },
            // Specify validation error messages
            messages: {
                amount: "Please enter amount",
            }
        });
    });
	
	// Wait for the DOM to be ready
    $(function() {
        $("form[name='withdraw_form']").validate({
            // Specify validation rules
            rules: {
                amount: "required"

            },
            // Specify validation error messages
            messages: {
                amount: "Please enter amount",
            }
        });
    });
	
	$("#deposite_amount").click(function()
    {
        var form = $( "#deposite_form" );
        form.validate();
        if(form.valid()) {
            var amount = $("#amount").val();
            var user_id = localStorage.getItem('user_id');
            var post_data = {'amount': amount, 'user_id': user_id};
            $.ajax({
				  headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/admin/payment/deposite-payment',
                type: "POST",
                data: post_data,
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.status) {
						localStorage.clear();
                        $("#success_alert_deposite").css('display','block');
                        $("#success_alert_deposite").fadeTo(2000, 500).slideUp(500, function () {
                            $("#success_alert_deposite").slideUp(500);
                        });
                        $('#error_deposit').html(res.message);
                        $('#amount').val("");
                        setTimeout(function () {
                            $("#depositModel").modal('hide');
                            window.location.reload();
                        }, 2000);
                    }
                    else {
                        $("#danger_alert_deposite").css('display','block');
                        $('#danger_alert_deposite').fadeTo(2000, 500).slideUp(500, function () {
                            $("#danger_alert_deposite").slideUp(500);
                        });
                        $('#error_msg_deposit').html(res.message);
                        $('#amount').val("");
                    }
                }
            });

        }

    });
	
	$("#withdraw_amount_btn").click(function()
    {
        var form = $( "#withdraw_form" );
        form.validate();
        if(form.valid()) {
            var amount = $("#withdraw_amount").val();
            var user_id = localStorage.getItem('user_id');
            var post_data = {'amount': amount, 'user_id': user_id};
            $.ajax({
				  headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/admin/payment/withdraw-payment',
                type: "POST",
                data: post_data,
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.status) {
						localStorage.clear();
                        $("#success_alert_withdraw").css('display','block');
                        $("#success_alert_withdraw").fadeTo(2000, 500).slideUp(500, function () {
                            $("#success_alert_withdraw").slideUp(500);
                        });
                        $('#error_withdraw').html(res.message);
                        $('#amount').val("");
                        setTimeout(function () {
                            $("#withdrawModel").modal('hide');
                            window.location.reload();
                        }, 2000);
                    }
                    else {
                        $("#danger_alert_withdraw").css('display','block');
                        $('#danger_alert_withdraw').fadeTo(2000, 500).slideUp(500, function () {
                            $("#danger_alert_withdraw").slideUp(500);
                        });
                        $('#error_msg_withdraw').html(res.message);
                        $('#amount').val("");
                        }
                }
            });

        }

    });
	
function get_account_information(value)
{
	
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/payment/get-account-details',
            type: 'POST',
            data: {id:value},
            success: function (data) {
                $("#content").html(data);
				$("#myModal").modal("show");
            },
            error: function () {
                console.log('There is some error in user deleting. Please try again.');
            }
        });
    
}

	
	