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

    $("form[name='add_builder']").validate({
        rules: {
            rera_no: {
                required: true,
                   },
            property_name: {
                required: true,
            },
            property_category: {
                required: true,
            },
            project_status: {
                required: true,
            },
            type_of_property: {
                required: true,
            },
            country_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
            state_id: {
                required: true,
            },

        },
        // Specify validation error messages
        messages: {
            rera_no: "Please enter rera no",
            property_name: "Please property name",
            property_category: "Please select property category",
            property_status: "Please select property category",
            type_of_property: "Please enter type of property",
            city_id: "Please select city",
            state_id: "Please select state",
            country_id: "Please select country",
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
////load the data......................................................
dataTable = $('#manifest-table').DataTable({
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
        url : BASE_URL+'/admin/seller/getManifestData', // json datasource
        type: "post",  // method  , by default get
        error: function(){  // error handling
            $(".user-table-error").html("");
            $("#users-table").append('<tbody class="user-table-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
            //$("#user-table_processing").css("display","none");
        }
    },
    "columnDefs": [ { orderable: false, targets: [0,5] }],
    "order": [[ 1, 'desc' ]]
});

///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete this record?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/manifest/delete',
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