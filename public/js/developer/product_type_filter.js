function filter_now()
{
    var min_price = $('#min_price').val();
    var max_price = $('#max_price').val();
    var product_type = $('#product_type').val();
    var brand="";

    var val = [];
    $('.brand_value:checked').each(function(i){
        val[i] = $(this).val();
    });
    var offer = [];
    $('.offer_value:checked').each(function(i){
        offer[i] = $(this).val();
    });

    $(".loader-div").show();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: BASE_URL + '/product/get-filter-product-type',
        cache: false,
        data:{product_type:product_type,min_price:min_price,max_price:max_price,brand:val,cat_url:cat_url,s_cat_url:s_cat_url,offer:offer},
        success: function (response, textStatus, jqXHR) {
            $("#product_listing").html(response);
            $(".loader-div").hide();
        },
        error: function(response)
        {
            $(".loader-div").show();
        }

    });
}