function deleteItem(id){

    var result = confirm("Are you sure you want to delete the color ?");

    if (result) {

        $('#ajaxLoader').show();

        $.ajax({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            url: BASE_URL+'/admin/product/delete-color',

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