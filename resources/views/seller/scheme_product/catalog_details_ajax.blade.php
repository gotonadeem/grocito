<div class="modal-scroll no-footer-popup">
   <div class="catalog-name">(ID: {{$scheme_details['id']}})</div>

                        <div class="catalog-data border-bottom">
                            <div class="catalog-content"><strong>Scheme Name:</strong> {{$scheme_details['offer_name']}}</div>
                            <div class="catalog-content"><strong>Product Name:</strong> Rs. {{$scheme_details['get_product']['name']}}</div>
                            <div class="catalog-content"><strong>Weight & Sale Price:</strong> {{$scheme_details['get_product_item']['weight']}} - ₹{{$scheme_details['get_product_item']['sprice']}}</div>

                        </div>
                        <div class="margin-top-10">
                            <div class="relative">
							
                                <div class="text-bold">Images</div>
								<div class="stock-message"><span id="message"></span></div>
                                <div class="row-5">
                                    <div class="drop-image-preview">
                                        <div class="relative border">
						                 <img src="{{ URL::asset('public/admin/uploads/scheme_product') }}/{{$scheme_details['image']}}" data-src="{{ URL::asset('public/admin/uploads/scheme_product') }}/{{$scheme_details['image']}}" style="height:60px; width:60px;border:1px solid #ccc;" >
                                         <div class="input-check-btn">
                                         <p>
                                         </p>
                                         </div>                   
									   </div>

                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </div>
<script>					
$('input[id="stock_image"]').click(function(){
	        var id= $(this).val();
			if($(this).prop("checked") == true){
			  var status=0;		  
            }
            else if($(this).prop("checked") == false){
              var status=1;
			}
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/seller/catalog/stock-image',
                data: {id:id,status:status},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					data= JSON.parse(response);
					if(data.status)
					{
						if(status==0)
						{
							$("#message").html("Mark Out Stock Successfully").css('color','red');
						}
						
						if(status==1)
						{
							$("#message").html("Mark In Stock Successfully").css('color','green');
						}
						
					}
                }
            });
        });
</script>