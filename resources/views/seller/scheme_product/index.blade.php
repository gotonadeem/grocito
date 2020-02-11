@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
	    @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
        <div class="catalog-upload-heading"><a class="btn btn-default catalog-upload-new-button" href="{{URL::to('seller/scheme-product-add')}}">Add Scheme Product</a></div>
        <div class="catalogs-view clearfix">
		   @foreach($catatlog_list as $vs)
            <div class="single-catalog-view">
                <div class="catalog-card border relative">

                 <a href="javascript:void(0)" onclick="delete_product({{$vs->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>

					<div class="catalog-info clearfix">
                        <div class="catalog-info-left border">
                            <div class="catalog-images-1" data-line="+-2">
							<img class="catalog-image" src="{{ URL::asset('public/admin/uploads/scheme_product') }}/{{(!is_null($vs->image)?$vs->image:'')}}"></div>
                            						
						</div>
                        <div class="catalog-info-right">
                            <div class="catalog-data">
                                <div class="catalog-name"><span></span><span>(ID: {{$vs->id}})</span>  
								</div>
                                <div class="catalog-content"><strong>Scheme Name</strong> {{$vs->offer_name}}</div>
                                <div class="catalog-content"><strong>Product Name:</strong>{{$vs->get_product->name}}</div>
                                <div class="catalog-content"><strong>Weight & Sale Price:</strong>{{$vs->get_product_item?$vs->get_product_item->weight:''}} - ₹{{$vs->get_product_item ? $vs->get_product_item->sprice:''}}</div>
							</div>
                        </div>
                    </div>
                    <div class="catalog-edit-button">
                        <button class="catalog-files-view catalog-view-button" onClick="get_product_details(this.id)" id="{{$vs->id}}">View</button>

					</div>
                </div>
            </div>
			@endforeach
			<div class="payment-pagination text-center">

                            <div class="pagination-testing text-center">


                               

                            </div>

                        </div>
        </div>
    </div>
</div>

<div class="modal fade custom_popup view" id="myModal_view" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content catalog-upload">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Product Details</h4>
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

<div class="modal fade custom_popup view" id="myModal_plan" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content catalog-upload">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Plan Details Details</h4>
            </div>
            <div class="modal-body">
                <div class="catalog-details-popup modal-outer">
				     <div id="dynamic_html_plan"></div>
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
<script src="{{ URL::asset('public/front/developer/js/page_js/scheme_product.js') }}"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
@endsection