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
// return pending order......................................................
dataTable = $('#pending-return-order-table').DataTable({
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
    "pageLength": 50,
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/order/getReturnPendingOrderData', // json datasource
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,3] }],
    "order": [[ 2, 'desc' ]]
});
// return approved order......................................................
dataTable = $('#approved-return-order-table').DataTable({
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
    "pageLength": 50,
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/order/getReturnApprovedOrderData', // json datasource
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,3] }],
    "order": [[ 2, 'desc' ]]
});
////load the data......................................................
dataTable = $('#order-table').DataTable({
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
	"pageLength": 50, 
    "ajax":{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : BASE_URL+'/admin/order/getReturnOrderData', // json datasource
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,3] }],
    "order": [[ 2, 'desc' ]]
});
//$('.dataTables_filter input').attr("placeholder", "search with name.");
// $('.dataTables_filter input').attr("class", "clearable");
///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to cancel the Order ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/order/delete',
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
