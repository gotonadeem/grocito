@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
	    @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
        <div class="catalog-upload-heading">
           <!-- <a class="btn btn-default catalog-upload-new-button" href="{{URL::to('seller/catalog-add')}}">Add New Product</a>-->
        </div>
        <div class="catalogs-view clearfix">
		   @foreach($catatlog_list as $vs)
            <div class="single-catalog-view">
                <div class="catalog-card border relative">
                 <a href="javascript:void(0)" onclick="delete_duplicate_product({{$vs->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
					<div class="catalog-info clearfix">
                        <div class="catalog-info-left border">
                            <div class="catalog-images-1" data-line="+-2">
							<img class="catalog-image" src="{{ URL::asset('public/admin/uploads/product') }}/{{(!is_null($vs->product->product_image)?$vs->product->product_image[0]->image:'')}}"></div>
						</div>
                        <div class="catalog-info-right">
                            <div class="catalog-data">
                                <div class="catalog-content"><strong>Name:</strong>{{$vs->product->name}}</div>

                                <div class="catalog-content"><strong>Category:</strong>{{$vs->product->main_category->name}}</div>

                                <div class="catalog-content"><strong>Sub Category:</strong>{{$vs->product->sub_category->name}}</div>

							</div>
                        </div>
                    </div>
                    <div class="catalog-edit-button">
                        <button class="catalog-files-view catalog-view-button" onClick="get_duplicate_product_details(this.id)" id="{{$vs->product->id}}">View</button>
                        <a class="catalog-edit catalog-view-button catalog-edit-disable" href="{{URL::to('seller/catalog-edit')}}/{{$vs->product->id}}?type=1" >Edit</a>
					</div>
                </div>
            </div>
			@endforeach
			<div class="payment-pagination text-center">

                            <div class="pagination-testing text-center">

							{{$catatlog_list->links()}}
                               

                            </div>

                        </div>
        </div>
    </div>
</div>
@include('seller.catalog.plan')
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
<script src="{{ URL::asset('public/front/developer/js/page_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
@endsection