@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
	    @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
       <div class="catalogs-view">
		   @foreach($catatlog_list as $vs)
            <div class="single-catalog-view">
                <div class="catalog-card border relative">
                     <div class="catalog-info clearfix">
                        <div class="catalog-info-left border">
                            <div class="catalog-images-1" data-line="+-2">
							@if($vs->product_image[0]->image)
							<img class="catalog-image" src="{{ URL::asset('public/admin/uploads/product') }}/{{$vs->product_image[0]->image}}">
                            @else
							<img class="catalog-image" src="{{ URL::asset('public/uploads/seller/catalog/sample/carlay-dummy.jpg') }}">
                            @endif	
							
							</div>
                        </div>
                        <div class="catalog-info-right">
                            <div class="catalog-data">
                                <div class="catalog-name"><span></span><span>(ID: {{$vs->id}})</span></div>
                                <div class="catalog-content"><strong>Name :</strong>{{$vs->name}}</div>
                                <div class="catalog-content"><strong>Created At :</strong>{{$vs->created_at}}</div>
                                <div class="catalog-content"><strong>Category:</strong>{{$vs->main_category->name}}</div>
								<?PHP if($vs->sub_category_id): ?>
                                <div class="catalog-content"><strong>Sub Category:</strong>{{$vs->sub_category->name}}</div>
                                <?PHP endif; ?>
								
							</div>
                        </div>
                    </div>
                    <div class="catalog-edit-button">
                           <div class="button-2 pull-left"><a class="button-color-2 inventory-button display-block" href="javascript:void(0)" onclick="get_product_details(this.id)" id="{{ $vs->id}}">View</a></div>
                           <div class="button-2 pull-right">
                            <?PHP if($vs->stock_status): ?>
						     <a style="width: 100%;" id="{{$vs->id}}" onclick="manage_product_stock(this.id,'out_stock')" class="catalog-edit catalog-view-button catalog-edit-disable button-color-4" href="javascript:void(0)" >Mark out of stock</a>						 
                             <?PHP else: ?> 
						     <a style="width: 100%;" id="{{$vs->id}}" onclick="get_product_item(this.id)" class="catalog-edit catalog-view-button catalog-edit-disable" href="javascript:void(0)" >Mark in stock</a>
                              <?PHP endif; ?>
                           </div>						
					</div>
                </div>
            </div>
			@endforeach
        </div>
    </div>
</div>


<div class="modal fade custom_popup view" id="myModal_view" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content catalog-upload">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Catalog Details</h4>
            </div>
            <div class="modal-body">
                <div class="catalog-details-popup modal-outer">
				     <div id="dynamic_html"></div>
                     <div class="modal-hidden"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- get product item -->
<div class="modal fade custom_popup view" id="item_view" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content catalog-upload">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Catalog Details</h4>
            </div>
            <div class="modal-body">
                <div class="catalog-details-popup modal-outer">
                    <div id="item_html"></div>
                    <div class="modal-hidden"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade custom_popup edit" id="myModal_edit" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-headers">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Catalog Edit</h4>
            </div>
            <div class="modal-body">
			<div id="msg"></div>
               <form name="price_change" id="price_change">
               <input type="hidden" name="product_id" id="price_product_id">			
			  <div class="form-group">
				    <label>MRP Price</label>
					<input type="text" name="starting_price" placeholder="MRP Price" id="starting_price" class="form-control">
				 </div>
				 <div class="form-group">
				    <label>Sell Price</label>
					<input type="text" name="sell_price" placeholder="Sell Price"  id="sell_price" class="form-control">
				    <span id="sell_price_msg"></span>
				 </div>
				 <div class="form-group">
					<input type="button" name="update_price" value="submit" id="update_price" class="btn btn-primary">
				 </div>
			   </form>
            </div> 
        </div>
    </div>
</div>
<script src="{{ URL::asset('public/front/developer/js/validation_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/developer/js/page_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
<script>
function manage_product_stock(value1,value2)
 {
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/change-product-status',
        type: 'POST',
        data: {id: value1,type:value2},
        success: function (response) {
		  var response= JSON.parse(response);
          if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			
			if(response.status)
			{
	           location.replace(BASE_URL+"/seller/product-by-category/<?=Request::segment(3); ?>");
			}
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       }); 
 }
</script>
@endsection