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



	$.validator.addMethod("lessThan",

    function (value, element, param) {

          var $otherElement = $(param);

          return parseInt(value, 10) < parseInt($otherElement.val(), 10);

    });

	

	$.validator.addMethod("greaterThan",

    function (value, element, param) {

          var $otherElement = $(param);

          return parseInt(value, 10) > parseInt($otherElement.val(), 10);

    });



    $("form[name='add_product']").validate({

        rules: {
            category_id: {
                required: true
            },
            name: {
                required: true
            },
            description:{
                required:true
            },
			sub_category_id:{
                required:true
            },
			user_id:{
                required:true
            },
            'weight[]': {
                required: true,
                minlength: 1
            },
            'price[]': {
                required: true,
                minlength: 1
            },
            'qty[]': {
                required: true,
                minlength: 1
            },
            'offer[]': {
                required: true,
                minlength: 1
            },
           upload:{
                required:true
            },
            p_gst:{
                required:true
            }

        },

        // Specify validation error messages

        messages: {

            category_id: "Select category",
            name: "Enter product name",
            starting_price: "Enter MRP price",
            sell_price: "Enter Sell price",
            lessThan: "Sell Price Must Be Less Than MRP",
            greaterThan: "MRP Price Must Be Greater Than Sell Price",
            description:"Enter Description",
            sub_category_id:"Select Sub category",
            'weight[]': 'Please select at least one weight.',
            'price[]': 'Please select at least one price.',
            'qty[]': 'Please select at least one qty.',
            'offer[]': 'Please select at least one special price.',
              upload: 'Please Upload at least one Profile.', 
        },
        submitHandler: function(form) {
            form.submit();
        }

    });
});

jQuery(document).ready(function() {
    var storedFiles = [];
    // Apply sort function
    function cvf_reload_order() {

        var order = $('.cvf_uploaded_files').sortable('toArray', {attribute: 'item'});

        $('.cvf_hidden_field').val(order);

    }



    function cvf_add_order() {

        $('.cvf_uploaded_files li').each(function(n) {

            $(this).attr('item', n);

        });

		$('.cvf_uploaded_files select').each(function(n) {

            $(this).attr('id', n);

        });

		$('.cvf_uploaded_files div').each(function(n) {

            $(this).attr('id', "msg_"+n);

        });

    }



    $(function() {

        $('.cvf_uploaded_files').sortable({

            cursor: 'move',

            placeholder: 'highlight',

            start: function (event, ui) {

                ui.item.toggleClass('highlight');

            },

            stop: function (event, ui) {

                ui.item.toggleClass('highlight');

            },

            update: function () {

                //cvf_reload_order();

            },

            create:function(){

                var list = this;

                resize = function(){

                    $(list).css('height','auto');

                    $(list).height($(list).height());

                };

                $(list).height($(list).height());

                $(list).find('img').load(resize).error(resize);

            }

        });

        $('.cvf_uploaded_files').disableSelection();

    });



    $('body').on('change', '.user_picked_files', function() {

         var files = this.files;

        var i = 0;

         //delete files[0];

        for (i = 0; i < files.length; i++) {

            var readImg = new FileReader();

            var file = files[i];



            if (file.type.match('image.*')){

                storedFiles.push(file);

                readImg.onload = (function(file) {

                    return function(e) {

                        $('.cvf_uploaded_files').append(

                            "<li file = '" + file.name + "'>" +
                            "<img class = 'img-thumb' src = '" + e.target.result + "' />" +
                            "<a href = 'javascript:void(0)' class = 'cvf_delete_image' title = 'Cancel'><img class = 'delete-btn' src = '"+$("#delete_id").val()+"' /></a>" +

                            "</li>"

                        );

                    };

                })(file);

                readImg.readAsDataURL(file);



            } else {

                alert('the file '+ file.name + ' is not an image<br/>');

            }



            if(files.length === (i+1)){

                setTimeout(function(){

                    cvf_add_order();

                }, 1000);

            }

        }

    });



    // Delete Image from Queue

    $('body').on('click','a.cvf_delete_image',function(e){

        e.preventDefault();

        $(this).parent().remove('');



        var file = $(this).parent().attr('file');



        for(var i = 0; i < file.length; i++) {

            if(file[i].name == file) {

                file.splice(i, 1);

                break;
            }
        }
    });



    // AJAX Upload

    $('body').on('click', '.cvf_upload_btn', function(e){
        e.preventDefault();
        cvf_reload_order();
        var form = $("#add_product");
        form.validate();
        if(form.valid()) {
            //$(".cvf_uploaded_files").html('<p><img src = "' +$("#loading_id").val()+'" class = "loader" /></p>');
            var data = new FormData();
             //console.log(data);
            var items_array = $('.cvf_hidden_field').val();
            var items = items_array.split(',');
            for (var i in items) {
                var item_number = items[i];
                data.append('files' + i, storedFiles[item_number]);
            }
            var name = $("#name").val();            
			var category_id = $("#category_id").val();            
			var sub_category_id = $("#sub_category_id").val();
			var city_id = $("#city_id").val();
			var brand_id = $("#brand_id").val();
			var p_gst = $("#p_gst").val();

            var weight = $("input[name='weight[]']").map(function(){return $(this).val();}).get();
            var price = $("input[name='price[]']").map(function(){return $(this).val();}).get();
            var qty = $("input[name='qty[]']").map(function(){return $(this).val();}).get();
            var offer = $("input[name='offer[]']").map(function(){return $(this).val();}).get();
			defaultArray= new Array();
			blankArray= new Array();
			check_default= new Array();

            var related_product= $("#related_product").val();
            var is_admin_approved= $("#is_admin_approved").val();
            var color = $("#color").val();
            //var description = $("#description").val();
            var description = CKEDITOR.instances.description.getData();
            data.append('category_id', category_id);
            data.append('sub_category_id', sub_category_id);
            data.append('city_id', city_id);
            data.append('weight', weight);
            data.append('price', price);
            data.append('qty', qty);
            data.append('offer', offer);
            data.append('brand_id', brand_id);
            data.append('color', color);
            data.append('p_gst', p_gst);
			var is_return = $("#is_return:checked").val();
			var is_exchange = $("#is_exchange:checked").val();

            var is_cod = $("#is_cod:checked").val();
           // var is_featured = $("#is_featured:checked").val();
            var user_id = $("#user_id").val();
           // var color = $("#color").val();
            //var is_shipping_free = $("#is_shipping_free:checked").val();
			data.append('is_return', is_return);
			data.append('is_exchange', is_exchange);
            data.append('is_cod', is_cod);
            //data.append('size', size);
           // data.append('color', color);
            //data.append('is_default', JSON.stringify(defaultArray));
            //data.append('starting_price', price);            
            //data.append('sell_price', special_price);            
            data.append('user_id', user_id);            
			data.append('name', name);
           // data.append('tips', $("#tips").val());
			//data.append('weight', weight);
			//data.append('is_featured', is_featured);
            data.append('description', description);
            data.append('is_admin_approved', is_admin_approved);
           // data.append('is_shipping_free', is_shipping_free);
            data.append('related_product',related_product);
				 $(".loader_div").show();
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					contentType: false,
					url: BASE_URL + '/admin/product/store-product',
					data: data,
					processData: false,
					cache: false,
					success: function (response, textStatus, jqXHR) {
						data= JSON.parse(response);
						if(data.status)
						{						
							$(".cvf_uploaded_files").hide();
							$(".loader_div").hide();
							bootbox.alert(data.message);
						   setTimeout(function(){
							  window.location=BASE_URL+"/admin/product/unverified-product-list";
						   },2000);
						}
						else if(!data.status)
						{
							$(".loader_div").hide();
							bootbox.alert(data.message).find('.modal-content').css({'background-color': '#f99', 'font-weight' : 'bold', color: '#F00', 'font-size': '1em', 'font-weight' : 'bold'} );;
						}
					},
					error: function(response)
					{
						$(".loader_div").show();
					}

				});
        }
    });
	   // AJAX Upload

    $('body').on('click', '.cvf_upload_btn_update', function(e){
        e.preventDefault();
        cvf_reload_order();
        var form = $("#edit_catalog");
        form.validate();
        if(form.valid()) {
            var data = new FormData();
            var items_array = $('.cvf_hidden_field').val();
            var items = items_array.split(',');
            for (var i in items) {
                var item_number = items[i];
                data.append('files' + i, storedFiles[item_number]);
            }
            var name = $("#name").val();
            var city_id = $("#city_id").val();
            var brand_id = $("#brand_id").val();
			var category_id = $("#category_id").val();
            var sub_category_id = $("#sub_category_id").val();
            var is_admin_approved = $("#is_admin_approved").val();
            var weight = $("input[name='weight[]']").map(function(){return $(this).val();}).get();
            var price = $("input[name='price[]']").map(function(){return $(this).val();}).get();
            var qty = $("input[name='qty[]']").map(function(){return $(this).val();}).get();
            var offer = $("input[name='offer[]']").map(function(){return $(this).val();}).get();
            var item_id = $("input[name='item_id[]']").map(function(){return $(this).val();}).get();
            var is_return = $("#is_return:checked").val();
            var is_exchange = $("#is_exchange:checked").val();
            var is_cod = $("#is_cod:checked").val();
            var p_gst = $("#p_gst").val();
			defaultArray= new Array();
            blankArray= new Array();
            var product_id = $("#product_id").val();
            var user_id = $("#user_id").val();
            //var description = $("#description").val();
            var description = CKEDITOR.instances.description.getData();
            var color = $("#color").val();
            var related_product= $("#related_product").val();

            data.append('city_id', city_id);
            data.append('is_return', is_return);
            data.append('is_exchange', is_exchange);
            data.append('is_cod', is_cod);
            data.append('brand_id', brand_id);
            data.append('category_id', category_id);
            data.append('sub_category_id', sub_category_id);
            data.append('is_admin_approved', is_admin_approved);
            data.append('user_id', user_id);
            data.append('name', name);
            data.append('description', description);
            data.append('product_id', product_id);
            data.append('weight', weight);
            data.append('price', price);
            data.append('qty', qty);
            data.append('offer', offer);
            data.append('item_id', item_id);
            data.append('color', color);
            data.append('p_gst', p_gst);
            data.append('related_product',related_product);
			$(".loader_div").show();
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					contentType: false,
					url: BASE_URL + '/admin/product/update-product',
					data: data,
					processData: false,
					cache: false,
					success: function (response, textStatus, jqXHR) {
							data= JSON.parse(response);
							if(data.status)
							{
								$(".cvf_uploaded_files").hide();
								$(".loader_div").hide();
								bootbox.alert(data.message);
							   setTimeout(function(){
								  window.location=back_url;
							   },2000);
							}
							else if(!data.status)
							{
								$(".loader_div").hide();
								bootbox.alert(data.message).find('.modal-content').css({'background-color': '#f99', 'font-weight' : 'bold', color: '#F00', 'font-size': '1em', 'font-weight' : 'bold'} );;
							}
						},
						error: function(response)
						{
							$(".loader_div").show();
						}
				});
        }
    });
});

///get seller...............................

function getSeller(e) {
    return $("#ajaxLoader").show(),
        $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: BASE_URL + "/admin/product/get-seller",
        type: "POST",
        data: {
            id: e
        },
        success: function(e) {
            $("#user_id").html(e)
        },
        error: function() {
            console.log("There is some error to get seller. Please try again.")
        }
    })

}

///get subcategory...............................

function getSubcategory(e) {

    return $("#ajaxLoader").show(), $.ajax({

        headers: {

            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")

        },

        url: BASE_URL + "/admin/product/get-sub-category",

        type: "POST",

        data: {

            id: e

        },

        success: function(e) {

            $("#sub_category_id").html(e)

        },

        error: function() {

            console.log("There is some error in user deleting. Please try again.")

        }

    }), !1

}



function getSuperSubcategory(e) {

    return $("#ajaxLoader").show(), $.ajax({

        headers: {

            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")

        },

        url: BASE_URL + "/admin/product/get-super-category",

        type: "POST",

        data: {

            id: e

        },

        success: function(e) {

            $("#super_sub_category_id").html(e)

        },

        error: function() {

            console.log("There is some error in user deleting. Please try again.")

        }

    }), !1

}

$("#sell_price").keyup(function() {

    var e = $("#starting_price").val();

    parseFloat($(this).val()) >= parseFloat(e) ? ($("#sell_price_msg").html("Sell Price can not be greater than or equal to  MRP").css("color", "red"), $(this).val("")) : $("#sell_price_msg").html("").css("color", "red")

});



window.onmousedown = function (e) {

    var el = e.target;

    if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {

        e.preventDefault();



        // toggle selection

        if (el.hasAttribute('selected')) el.removeAttribute('selected');

        else el.setAttribute('selected', '');



        // hack to correct buggy behavior

        var select = el.parentNode.cloneNode(true);

        el.parentNode.parentNode.replaceChild(select, el.parentNode);

    }

}

function delete_catalog_image(value)
{
    var con= confirm("Are you sure delete it?");
    if(con)
    {
   $.ajax({
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                type: 'POST',

                url: BASE_URL + '/admin/catalog/delete-catalog-image',

                data: {id:value},

                cache: false,

                success: function (response, textStatus, jqXHR) {

					response= JSON.parse(response);

					if(response.status)

					{

						$("#remove_"+value).html("").css("border","none");

					}

                }

            });
  }
}



function calculate_shipping()

{

		var is_shipping_free = $("#is_shipping_free:checked").val();

	var weight = $("#weight").val();

	var shipping;

	var shipping_free_amount;

	if(is_shipping_free==1 && weight!="")

	{

		if(weight<=500)

		{

			shipping=50;

		}

		else

		{

		div= parseInt(weight%500);

			if(div == 0)

			{

				shipping= parseInt((weight/500))*50;

			}

			else

			{

				shipping= parseInt((weight/500))*50+50;

			}

			

		}

		shipping_free_amount=shipping; 

	}

   else

	{

		shipping_free_amount=0;

	}

	

   $("#shipping_amount").val(shipping_free_amount);	

    var total_shipping_amount= eval(parseInt($("#sell_price").val())+parseInt($("#shipping_amount").val()));

	if(!isNaN(total_shipping_amount))

	{

	$("#total_shipping_amount").val(total_shipping_amount); 

	}

}



$("input[name='is_shipping_free']").click(function()

{

  var is_shipping_free = $("input[name='is_shipping_free']:checked").val();

  if(is_shipping_free==1)

  {

	  calculate_shipping();

  }

  else

  {

	 $("#shipping_amount").val(0);

	var total_shipping_amount= eval(parseInt($("#sell_price").val())+parseInt($("#shipping_amount").val()));

	$("#total_shipping_amount").val(total_shipping_amount); 

  }

	

});



$("#weight").blur(function()

{

   calculate_shipping();

})

$("#sell_price").blur(function()

{

	var total_shipping_amount= eval(parseInt($("#sell_price").val())+parseInt($("#shipping_amount").val()));

	$("#total_shipping_amount").val(total_shipping_amount);	

});