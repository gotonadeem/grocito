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

    $("form[name='add_subadmin']").validate({
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
    dataTable = $('#datatable-fixed-header').DataTable({
        fixedHeader: true,  "dom": 'C<"clear">lfrtip',
        "colVis": {
            "buttonText": "View columns"
        },
        "oLanguage": {
            "sProcessing": "<img src='"+ASSET_URL+"admin/images/loading.gif'>"
        },
        "processing": true,
        "serverSide": true,
        "ajax":{
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : BASE_URL+'/admin/subadmin/getSubadminData', // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
                $(".user-table-error").html("");
                $("#datatable-fixed-header").append('<tbody class="user-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                //$("#user-table_processing").css("display","none");
            }
        },
        "columnDefs": [ { orderable: false, targets: [0,4] }],
        "order": [[ 2, 'desc' ]]
    });
    $('.dataTables_filter input').attr("placeholder", "username.");
    $('.dataTables_filter input').attr("class", "clearable");

///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the user ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/subadmin/delete',
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


$(document).ready(function()
{
	//Jquery Validation code........................
$("#password_form").validate({
	debug:true,
    rules: {
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
    messages: {
        password: {
                minlength: "Password must be at least 6 characters long",
                maxlength: "Password cannot be longer than 20 characters"
            },
            password_confirmation: {
                equalTo: "Please enter the confirm password as password"
            }
    }
});

});


function change_password(id)
 {
	$("#user_id").val(id);
	$("#myModal").modal("show");
 }
 function change_password_now()
		{
			
				var form = $("#password_form");
	                form.validate();
	             if(form.valid()) {
					 var new_password= $("#password").val();
					 var user_id= $("#user_id").val();
			    $.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: BASE_URL+'/admin/subadmin/change-subadmin-password',
						type: 'POST',
						data: {new_password:new_password,user_id:user_id},
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
									$("#password").val("");
									$("#password_confirmation").val("");
									$("#password_"+user_id).text(new_password);
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
		
			
