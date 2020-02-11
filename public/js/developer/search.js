$( ".display_suggestion" ).autocomplete({
    source :BASE_URL+'/display-suggestion',
    minLength: 1,
    delayTime:250,
    autoFocus:true,
    select: function(event, ui) {
         $("#main_search").attr("action", ui.item.url);
         $("#main_search").attr("method", 'POST');
         $("#main_search").submit();
    }
}).data("ui-autocomplete")._renderItem = function rdrItem(ul, item) {
    var gym_for=$("#property_for").val();
    var url=item.url;
    value='';
    value+='<div class="search_ful_list">';
    value+='<div class="col-md70 col-sm70">';
    value+='<div class="search_img">';
    value+='<div class="product_search_list">';
    value+='<p class="product_search_name">'+item.value+'</p>';
    value+='</div>';
    value+='</div>';
    value+='<div class="prod">';
    value+='<p></p>';
    value+='</div>';
    value+='</div>';


    return $('<li></li>').data("item.ui-autocomplete", item).append("<div class='rows' id='"+url+"' ><div class='col-md-contnt'><a href='javascript:void(0)' onClick='redirect(this.id)' id='"+url+"' >"+value+"</a></div></div>").appendTo(ul);
}



function redirect(value)
{
	window.location.href=value;
}

$( ".display_suggestion_mobile" ).autocomplete({
    source :BASE_URL+'display_suggestion',
    minLength: 2,
    delayTime:250,
    autoFocus:true,
    select: function(event, ui) {
        $(".search_error_list").html("");
        $(".search_type").val(ui.item.search_type);
        /*    var url=BASE_URL+"filter/gym-in-"+ui.item.value+"-"+ui.item.search_type+"-"+ui.item.id+"-"+ui.item.city_value;
         $("#home-search").attr("action", url);
         $("#home-search").attr("method", 'POST');
         $("#search_id").val(ui.item.id);
         $("#home-search").submit();
         */
    }
}).data("ui-autocomplete")._renderItem = function rdrItem(ul, item) {
    var gym_for=$("#property_for").val();
    var url=item.url;
    var weight=item.weight;
    var split_data=weight.split(",");
    weight_html='';
    for(i=0;i<split_data.length;i++)
    {
        weight_html+='<span>'+split_data[i]+' </span>';
    }
    value='';
    var price=item.price;
    var split_data=price.split(",");
    price_html='';
    for(i=0;i<split_data.length;i++)
    {
        price_html+='<span>'+split_data[i]+' </span>';
    }

    value+='<div class="search_ful_list">';
    value+='<div class="col-md70 col-sm70">';
    value+='<div class="search_img">';
    value+='<img src='+item.image+' class="img-responsive " alt=""/></div>';
    value+='<div class="product_search_list">';
    value+='<p class="product_search_name">'+item.value+'</p>';
    value+='<p class="product_search_qu">'+weight_html+'</p>';
    value+='<p class="product_search_price"><i class="fa fa-inr" aria-hidden="true"></i>'+price_html+'</p>';
    value+='</div>';
    value+='</div>';
    value+='<div class="prod">';
    value+='<p></p>';
    value+='</div>';
    value+='</div>';


    return $('<li></li>').data("item.ui-autocomplete", item).append("<div class='rows' id='"+url+"' ><div class='col-md-contnt'><a href='javascript:void(0)' onClick='redirect(this.id)' id='"+url+"' >"+value+"</a></div><div  class='col-md-store' >"+ item.type +"</div></div>").appendTo(ul);
}
