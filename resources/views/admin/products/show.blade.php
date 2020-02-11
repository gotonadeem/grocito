@extends('admin.layout.admin')
@section('content')
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Product Details</h5>
                        <div class="text-right">
						<a href="{{ URL::previous() }}" class="btn btn-info">View All</a>
						&nbsp;&nbsp;&nbsp;<div class="catalog-name">
						 </div>  
                        </div>
                         <div class="ibox-content display-table">
                         <div class="row">
                         <div class="striped">
                             <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Product Name </label>
                                     <div class="col-sm-8">
                                         {{$product->name}}
                                     </div>
                                 </div>
                             </div>
                             </div>
                             <div class="striped2">
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Seller Name </label>
                                     <div class="col-sm-8">
                                        {{$product->user_name->username}}
                                     </div>
                                 </div>
                             </div>
                                 <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label for="inputEmail3" class="col-sm-4 form-control-label">City Name </label>
                                         <div class="col-sm-8">
                                             {{$product->city ? $product->city->name:''}}
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label for="inputEmail3" class="col-sm-4 form-control-label">Brand Name </label>
                                         <div class="col-sm-8">
                                             {{$product->brand ? $product->brand->name:''}}
                                         </div>
                                     </div>
                                 </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Product Category </label>
                                     <div class="col-sm-8">
									 {{$product->main_category->name}}
                                     </div>
                                 </div>
                             </div>
                                 <!-- <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label for="inputEmail3" class="col-sm-4 form-control-label">Color</label>
                                         <div class="col-sm-8">
                                             <?PHP $color=explode(",",$product->color);
                                             foreach($color as $vs)
                                             {
                                                 echo "<span style='background-color:".$vs."'>&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;";
                                             }
                                             ?>
                                         </div>
                                     </div>
                                 </div> -->
                             </div>

                             @if(!is_null($product->sub_category))
                             <div class="striped">
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Sub Category </label>
                                     <div class="col-sm-8">
									 {{$product->sub_category->name}}
                                     </div>
                                 </div>
                             </div> 
							 @endif

                             </div>
                             <div class="striped2">
							

                              <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Product Description</label>
                                     <div class="col-sm-8">
                                        {!!$product->description!!}
                                     </div>
                                 </div>
                             </div>
                             </div>
                             
                             <div class="striped">
                              <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Product Status</label>
                                     <div class="col-sm-8">
                                        @if($product->is_admin_approved)
										   <b>Approved</b>
										@else
										   <b>Un-Approved</b>
                                        @endif									
                                     </div>
                                 </div>
                             </div>
							 </div>

                                 <div class="striped-custom">
                                
                             <div class="col-product-div"> 
							   <div class="col-sm-12">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Product Images</label>
                                     <div class="col-sm-12">
									 <div class="row">
                                        @foreach($product->product_image as $vs)
										<div class="col-md-2 col-sm-2 col-xs-6">
                                        <div class="product-box-img">
										<p><img src="{{URL::asset('public/admin/uploads/product/'.$vs->image)}}" style="height:100px;width:100px;">	</p>
										<h4>{{$vs->size}}</h4>
                                        </div>
                                        </div>
										@endforeach
                                       </div> 
                                     </div>
                                 </div>
                             </div>
                            <div class="row">
                            
                            <div class="striped">
                              <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Products Details</label>
                                 </div>
                             </div>
							 </div>
                            
                            
                             
                            <div class="container-fluid"> 
                             <div class="col-sm-6">
                                <table class="table pdtable">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:25px;">S.N.</th>
                                        <th>Weight</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Offer(%)</th>
                                        <th>Special Price</th>
                                        <th>Stock</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        <?php $sn=1; ?>
                                        @foreach($product->product_item as $val)
                                        <tr>
                                        <td>{{$sn}}</td>
                                        <td>{{$val->weight}}</td>
                                        <td>{{$val->qty}}</td>
                                            <td>{{$val->price}}</td>
                                            <td>{{$val->offer}}</td>
                                        <td>{{$val->sprice}}</td>
                                        <td style="color: {{$val->qty>0 ?'green':'red'}}">{{$val->qty>0 ?'In-Stock':'Out Of-Stock'}}</td>
                                        </tr>
                                            <?php $sn++; ?>
                                            @endforeach
                                    </tbody>
                                </table>

                            </div>
                            </div>
                             </div>
                             </div>
							
                             </div>

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

    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
	<script>
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
	var user_id= $("#seller_id").val();
	var sponsor_plan_id= value;
	$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: BASE_URL + '/admin/product/activate-sponsor',
                data: {date:date,product_id:product_id,user_id:user_id,sponsor_plan_id:sponsor_plan_id},
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
                url: BASE_URL + '/admin/product/get-sponsored',
                data: {id:value},
                cache: false,
                success: function (response, textStatus, jqXHR) {
					 $("#dynamic_html_plan").html(response);
					 $("#myModal_plan").modal('show');
                }
            });
}

	</script>
	
@stop
