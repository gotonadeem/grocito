///delete record...............................
function deleteItem(id1){

    var result = confirm("Are you sure you want to delete the Note ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/user-note/delete',
            type: 'POST',
            data: {id: id1 },
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
//delete record...............................
function deleteItemProduct(id1){

    var result = confirm("Are you sure you want to delete the Note ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/product-note/delete',
            type: 'POST',
            data: {id: id1 },
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
//delete record...............................
function deleteItemOrder(id1){

    var result = confirm("Are you sure you want to delete the Note ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/order-note/delete',
            type: 'POST',
            data: {id: id1 },
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