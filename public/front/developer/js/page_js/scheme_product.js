///delete record...............................
function deleteItem(id){
    var result = confirm("Are you sure you want to delete the user ?");
    if (result) {
        $('#ajaxLoader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/admin/property/delete',
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
        url: BASE_URL+'/builder/project/get-state',
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
        url: BASE_URL+'/builder/project/get-city',
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

function get_subcat(value)
{   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/get-subcat',
        type: 'POST',
        data: {id: value },
        success: function (data) {
            $("#sub_cat_id").html(data);
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
    });
}
function getProduct(value)
{   $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: BASE_URL+'/seller/scheme-product/get-product',
    type: 'POST',
    data: {id: value },
    success: function (data) {
        $("#product_id").html(data);
    },
    error: function () {
        console.log('There is some error. Please try again.');
    }
});
}
function getProductItem(value)
{   $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: BASE_URL+'/seller/scheme-product/get-product-item',
    type: 'POST',
    data: {id: value },
    success: function (data) {
        $("#product_item_id").html(data);
    },
    error: function () {
        console.log('There is some error. Please try again.');
    }
});
}

function get_super_subcat(value)
{   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/get-supsubcat',
        type: 'POST',
        data: {id: value },
        success: function (data) {
            $("#super_sub_category_id").html(data);
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
    });
}

function get_types(value)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/builder/project/get-sub-types',
        type: 'POST',
        data: {id: value },
        success: function (data) {
            $("#type_of_property").html(data);
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
    });
}


//...........................upload.......................
jQuery(document).ready(function() {
    var storedFiles = [];
    //$('.cvf_order').hide();
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
        //console.log('test');
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

        for (i = 0; i < files.length; i++) {
            var readImg = new FileReader();
            var file = files[i];

            if (file.type.match('image.*')){
                storedFiles.push(file);
                readImg.onload = (function(file) {
                    return function(e) {
						var msgv=file.name;
						msgv= msgv.split(".");
                        $('.cvf_uploaded_files').append(
                            "<li file = '" + file.name + "'>" +
                            "<img class = 'img-thumb' src = '" + e.target.result + "' />" +
							
                            "<a href = 'javascript:void(0)' class = 'cvf_delete_image' title = 'Cancel'><img class = 'delete-btn' src = '"+delete_img+"' /></a>" +
                            "</li>"
                        );
                    };
                })(file);
                readImg.readAsDataURL(file);
                $('.box2').hide();

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
        $('.box2').show();
        var file = $(this).parent().attr('file');

        for(var i = 0; i < file.length; i++) {
            if(file[i].name == file) {
                file.splice(i, 1);
                break;
            }
        }

        //cvf_reload_order();

    });

    // AJAX Upload
    $('body').on('click', '.cvf_upload_btn', function(e){
        e.preventDefault();
        cvf_reload_order();
        var form = $("#add_offer_product");
        form.validate();
        if(form.valid()) {
            var data = new FormData();
            var items_array = $('.cvf_hidden_field').val();
            var items = items_array.split(',');
            for (var i in items) {
                var item_number = items[i];
                data.append('files' + i, storedFiles[item_number]);
            }
            var cat_id = $("#cat_id").val();
            var sub_cat_id = $("#sub_cat_id").val();
            var product_id = $("#product_id").val();
            var product_item_id = $("#product_item_id").val();
            var offer_name = $("#offer_name").val();
            data.append('cat_id', cat_id);
            data.append('sub_cat_id', sub_cat_id);
            data.append('product_id', product_id);
            data.append('product_item_id',product_item_id);
            data.append('offer_name', offer_name);
			$(".loader_div").show();
			$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					contentType: false,
					url: BASE_URL + '/seller/scheme-product/scheme-store',
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
							 location.replace(BASE_URL+"/seller/scheme-product");
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

            //$(".cvf_uploaded_files").html('<p><img src = "' +loading_img+'" class = "loader" /></p>');
            var data = new FormData();
            var items_array = $('.cvf_hidden_field').val();
            var items = items_array.split(',');
            for (var i in items) {
                var item_number = items[i];
                data.append('files' + i, storedFiles[item_number]);
            }
			
			weightArray= new Array();
			$('input[name^="weight"]').each(function(){
			   weightData= $(this).val();		   
			   weightArray.push(weightData);
			 });
			 
			 priceArray= new Array();
			$(".price").each(function(){
			   priceData= $(this).val();		   
			   priceArray.push(priceData);
			 });
			 
			 spriceArray= new Array();
			$(".sprice").each(function(){
			   spriceData= $(this).val();		   
			   spriceArray.push(spriceData);
			 });

			 qtyArray= new Array();
			$(".qty").each(function(){
			   qtyData= $(this).val();		   
			   qtyArray.push(qtyData);
			 });
			 
            var catalog_id = $("#catalog_id").val();
            var description = $("#description").val();
            var category = $("#category").val();
            var sub_category = $("#sub_category_id").val();
            var super_sub_category = $("#super_sub_category_id").val();
            var name = $("#name").val();
            data.append('description', description);
            data.append('category_id', category);
            data.append('sub_category_id', sub_category);
			data.append('weight', JSON.stringify(weightArray));
            data.append('price', JSON.stringify(priceArray));
            data.append('sprice', JSON.stringify(spriceArray));
            data.append('qty', JSON.stringify(qtyArray));
		    data.append('super_sub_category_id', super_sub_category);
            data.append('name', name);
            data.append('id', catalog_id);
			 
			     $(".loader_div").show();
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: 'POST',
						contentType: false,
						url: BASE_URL + '/seller/catalog-update',
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
									
									 location.replace(BASE_URL+"/seller/catalog");
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

function delete_property_image(value)
{
	 $(".loader_div").show();
   $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/builder/project/delete-property-image',
                data: {id:value},
                cache: false,
                success: function (response, textStatus, jqXHR) {
                    //console.log(response);
                    $(".cvf_uploaded_files").hide();
					  var property_id = $("#property_id").val();
                     $(".loader_div").hide();
				   location.replace(BASE_URL+"/builder/project/add-gallery/"+property_id);
                }
            });
}

function get_product_details(value)
{
	 $(".loader_div").show();
          $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/scheme-product-details',
                data: {id:value},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					$("#dynamic_html").html(response);
					 $(".loader_div").hide();
					$("#myModal_view").modal('show');
                    //console.log(response);
                    $(".cvf_uploaded_files").hide();
			     }
            });
}


function showeEditPopup(value)
{
	 $(".loader_div").show();
	var value= value.split(",");
  $("#price_product_id").val(value[0]);
  $("#starting_price").val(value[1]);
  $("#sell_price").val(value[2]);
   $(".loader_div").hide();
  $("#myModal_edit").modal("show");
}
$("#update_price").click(function()
{
		var form = $("#price_change");
		form.validate();
		if(form.valid()) {
			var product_id= $("#price_product_id").val();
			var price= $("#starting_price").val();
			var sell_price= $("#sell_price").val();
			 $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/update-price',
                data:{price:price,sell_price:sell_price,product_id:product_id},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					response=JSON.parse(response);
							if(response.status)
							{
								  html="<div id='success-alert' class='alert alert-success alert-dismissible'>";
                                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                                  html+=""+response.message+"</div>";
								  $("#msg").html(html);
								  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
									$("#success-alert").slideUp(500);
								   location.replace(BASE_URL+"/seller/catalog");
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

								   });
								
							}
                   
                }
            });  
		}
	});
 function delete_product(value)
 {
	var con= confirm("Are you sure to delete this scheme product?");
	if(con==true)
	{
		location.replace(BASE_URL+"/seller/scheme-product-delete/"+value);
	}
 }

function delete_catalog_image(value)
{
   $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/catalog/delete-catalog-image',
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

function make_sponsor(value)
{
	$("#product_id_value").val(value);
	$("#planModal").modal("show");
}

function activate_plan()
{
	var value=$('input[name=plan]:checked').val();
  	if(value==1)
	{
		var date= $("#date_1").val();
	}
	else if(value==2)
	{
		var date= $("#date_2").val();
	}
	else if(value==3)
	{
		var date= $("#date_3").val();
	}
	
	var product_id= $("#product_id_value").val();
	var sponsor_plan_id= value;
	$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/catalog/activate-sponsor',
                data: {date:date,product_id:product_id,sponsor_plan_id:sponsor_plan_id},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					response= JSON.parse(response);
					if(response.status==1)
					{
						location.reload()
					}
					if(response.status==2)
					{
						  html="<div id='success-alert' class='alert alert-danger alert-dismissible'>";
                                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                                  html+=""+response.message+"</div>";
								  $("#msg_error").html(html);
								  $("#success-alert").fadeTo(2000, 500).slideUp(1000, function(){
									$("#success-alert").slideUp(1000);
								   });
					}
					if(response.status==3)
					{
						         html="<div id='success-alert' class='alert alert-danger alert-dismissible'>";
                                  html+="<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";   
                                  html+=""+response.message+"</div>";
								  $("#msg_error").html(html);
								  $("#success-alert").fadeTo(2000, 500).slideUp(1000, function(){
									$("#success-alert").slideUp(1000);
								   });
					}
					
					if(response.not_login)
					{
						location.replace(BASE_URL);
					}
                }
            });
}
function get_plan(value)
{
	$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/catalog/get-sponsored',
                data: {id:value},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					 $("#dynamic_html_plan").html(response);
					 $("#myModal_plan").modal('show');
                }
            });
}


	
		
	


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