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

	

	//mobile no validation

    jQuery.validator.addMethod('validMobile', function (value, element) {

        if(/^[0-9]+$/i.test(value))

        {

            return true;

        }

    }, 'Please enter valid mobile no.');

	



    $("form[name='add_seller']").validate({

        rules: {

            email: {

                required: true,

                email: true,

                validEmail: true,

            },

            username: {

                required: true,

            }, 

			

			l_name: {

                required: true,

            },

			f_name: {

                required: true,

            },

			mobile:{

				 required: true,

				 validMobile:true,

				 maxlength:10

			},

            password: {

                required: true,

                minlength: 6,

                maxlength: 20

            },

			        },

        // Specify validation error messages

        messages: {

            email:{

			required:"Please enter email address",

			},

            username:{ 

			required:"Please enter username",

			}, 

			mobile:{ 

			required:"Please enter mobile",

			},

			l_name:"Last name is required",

			f_name:"First name is required",

        },

        submitHandler: function(form) {

            form.submit();

        }

    });

});

////load the data......................................................

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

            url : BASE_URL+'/admin/agent/getAgentData', // json datasource

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
	
	////load the data......................................................

    dataTable = $('#active-table').DataTable({

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

            url : BASE_URL+'/admin/agent/getActiveAgentData', // json datasource

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

   ////load the data......................................................

    dataTable = $('#inActive-table').DataTable({

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

            url : BASE_URL+'/admin/agent/getInActiveAgentData', // json datasource

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

// CLEARABLE INPUT

function get_state(value)

{

    $.ajax({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        url: BASE_URL+'/admin/seller/get-state',

        type: 'POST',

        data: {id: value },

        success: function (data) {

            $("#state_list").html(data);

        },

        error: function () {

            console.log('There is some error in user deleting. Please try again.');

        }

    });

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

function check_username(value,column_name)

{

	if(value.length)

	{

	$.ajax({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        url: BASE_URL+'/admin/seller/check-user',

        type: 'POST',

        data: {value: value,column:column_name},

        success: function (data) {

         response=JSON.parse(data);

		 if(!response.status)

		 {

			$("#"+column_name+'_msg').html(response.msg);

		 }

		 else

		 {

			$("#"+column_name+'_msg').text(''); 

		 }

		

        },

        error: function () {

            console.log('There is some error in user deleting. Please try again.');

        }

    });

	}

	else

		 {

			$("#"+column_name+'_msg').text(''); 

		 }

}