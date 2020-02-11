@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
	    @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
        <div class="clearfix">
            <div class="col-xs-6">
            <div class="catalog-upload-heading">
			<a class="btn btn-default catalog-upload-new-button" href="{{URL::to('seller/catalog-add')}}">Add New Product</a>
			@if(isset($_GET['search']))
			<a class="btn btn-default catalog-upload-new-button btn-danger" href="{{URL::to('seller/catalog')}}">Clear Search</a>
		    @endif
		
			</div>
            </div>
            <div class="col-xs-6">
                <div class="catalog-upload-heading text-right">
            
                    {{ Form::open(array('class'=>'form-horizontal','id'=>'add_subadmin', 'method'=>'get', 'name'=>'add_user')) }}       
                    <input type="submit" name="search" class="btn btn-primary" value="Search">
                    <input type="text" name="keyword" placeholder="Enter product name"  class="form-control display_suggestion">
                    </form>
                
                </div>
            </div>
        </div>
        <div class="catalogs-view clearfix">
		   @foreach($catatlog_list as $vs)
            <div class="single-catalog-view">
                <div class="catalog-card border relative">
				 <?PHP if(!$vs->is_admin_approved): ?>
                 <a href="javascript:void(0)" onclick="delete_product({{$vs->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                  <?PHP endif; ?>  
					<div class="catalog-info clearfix">
                        <div class="catalog-info-left border">
                            <div class="catalog-images-1" data-line="+-2">

							<img class="catalog-image" src="{{ URL::asset('public/admin/uploads/product') }}/{{(!is_null($vs->product_image)?$vs->product_image[0]->image:'')}}"></div>

						</div>
                        <div class="catalog-info-right">
                            <div class="catalog-data">
                                <div class="catalog-content">
                                    <strong>Name :</strong>{{$vs->name}}
                                </div>
							<div class="catalog-content">
                                <strong>Created At :</strong>{{$vs->created_at}}
                            </div>
                               
                                <div class="catalog-name"><span></span><span>(ID: {{$vs->id}})</span>  
								 <?PHP if($vs->is_admin_approved): ?>
								<?PHP
								  $data=Helper::check_sponsor($vs->id);
                                  if($data):
                                      ?>
								 <?PHP
							      else:
								 ?>			
								  
								  <?PHP endif; endif; ?>
								  
								</div>
								
                                
                                <div class="catalog-content"><strong>Category:</strong>{{$vs->main_category->name}}</div>
								<?PHP if($vs->sub_category_id): ?>
                                <div class="catalog-content"><strong>Sub Category:</strong>{{$vs->sub_category->name}}</div>
                                <?PHP endif; ?>
								<?PHP if($vs->super_sub_category_id): ?>
                                <div class="catalog-content"><strong>Super Category:</strong>{{$vs->super_sub_category->name}}</div>
                                <?PHP endif; ?>

                                <div class="catalog-content" ><strong>Status:</strong><spnan style="color: {{$vs->is_admin_approved == 0?'red':'green'}} ">{{$vs->is_admin_approved == 0?'Pending':'Approved'}}</spnan></div>

								@if(count($vs->product_note)>0 and $vs->is_admin_approved==0)
								
								<div class="alert alert-danger catalog-content" style="width: 100%;">
								   <a href="{{URL::to('seller/catalog-error-view/'.$vs->id)}}"><strong style="font-weight: 100;width: 100%;font-size: 13px;color: orangered;">Errors in catalog please</strong>
								 <strong style="font-weight: 100;font-size: 13px;color: orangered;width: 100%;">click Here to view errors</strong></a>
								</div>
								@endif
								
							</div>
                        </div>
                    </div>
                    <div class="catalog-edit-button">
                        <button class="catalog-files-view catalog-view-button" onClick="get_product_details(this.id)" id="{{$vs->id}}">View</button>
                        
                         <a class="catalog-edit catalog-view-button catalog-edit-disable" href="{{URL::to('seller/catalog-edit')}}/{{$vs->id}}" >Edit</a>
                        						 
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
<script src="{{ URL::asset('public/js/jquery-ui.min.js') }}"></script>
<script>
$( ".display_suggestion" ).autocomplete({
    source :BASE_URL+'/seller/display-seller-suggestion',
    minLength: 2,
    delayTime:250,
    autoFocus:true,
    select: function(event, ui) {
        var originalEvent 													= event,
            input__event_type												= '',
            input__selection_type											= '';

        while ( originalEvent ) {
            if ( originalEvent.keyCode 										=== 13 ) {
                originalEvent.stopPropagation();
            }
            if ( originalEvent === event.originalEvent ) {
                break;
            }
            originalEvent = event.originalEvent;
        }
        input__event_type = originalEvent.type;
        if ( input__event_type 	=== 'menuselect' ) { // selected by keyboard instead of mouse/touch
            input__selection_type = 'menuselect'
        }
         $("#search_submit").attr("action", ui.item.url);
         $("#search_submit").attr("method", 'POST');
         //$("#search_submit").submit();
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

</script>
@endsection