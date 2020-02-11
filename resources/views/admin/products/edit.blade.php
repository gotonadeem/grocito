<style>
ul.cvf_uploaded_files1 {list-style-type: none; margin: 20px 0 0 0; padding: 0;}
ul.cvf_uploaded_files1 li {background-color: #fff; border: 1px solid #ccc; border-radius: 5px; float: left; margin: 20px 20px 0 0; padding: 2px; width: 50px; height: 50px; line-height: 50px; position: relative;}
ul.cvf_uploaded_files1 li img.img-thumb {width: 50px; height: 50px;}
ul.cvf_uploaded_files1 .ui-selected {background: red;}
ul.cvf_uploaded_files1 .highlight {border: 1px dashed #000; width: 50px; background-color: #ccc; border-radius: 5px;}
ul.cvf_uploaded_files1 .delete-btn1 {width: 24px; border: 0; position: absolute; top: -12px; right: -14px;}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins ibox-title">
                <div class=" clearfix">
                    <h3>Edit Product</h3>
                    <div class="text-right"><a href="{{ URL::to('admin/product/product-list') }}" class="btn btn-info">Back</a>
                        <div class="ibox-tools"></div>
                    </div>
                    {{ Form::open(array('url' => 'admin/product/update-product','class'=>'form-horizontal','id'=>'edit_catalog','name'=>'edit_catalog',"enctype"=>"multipart/form-data")) }}
                    <input type="hidden" name="id" id="product_id" value="<?=$product_details->id?>">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">City<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="city_id" id="city_id" onchange="getSeller(this.value)">
                                    <option value="">Select City</option>
                                    <?PHP foreach($cityList as $vs): ?>
                                    <?PHP if($product_details->city_id==$vs->id): ?>
                                    <option selected value="<?=$vs->id?>"><?=$vs->name?></option>
                                    <?PHP else: ?>
                                    <option value="<?=$vs->id?>"><?=$vs->name?></option>
                                    <?PHP endif; ?>
                                    <?PHp endforeach; ?>
                                </select>
                                <div class="error-message">{{ $errors->first('city_id') }}</div>
                            </div>
                        </div>
                    </div>
				    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select Seller<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="">Select Seller</option>
                            <?PHP foreach($sellerList as $vs): ?>
                             <?PHP if($product_details->user_id==$vs['user']['id']): ?>
							<option selected value="<?=$vs['user']['id']?>"><?=$vs['user']['username']?>(<?=$vs['user']['email']?>)</option>
							  <?PHP else: ?>
							<option value="<?=$vs['user']['id']?>"><?=$vs['user']['username']?>(<?=$vs['user']['email']?>)</option>
                               <?PHP endif; ?>
							<?PHp endforeach; ?>
                        </select>
                        <div class="error-message">{{ $errors->first('user_id') }}</div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Parent Category<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                        <select class="form-control" name="category_id" onchange="getSubcategory(this.value)" id="category_id">
                            <option value="">Select Category</option>
                            <?PHP foreach($category_list as $vs): ?>
							 <?PHP if($product_details->category_id==$vs->id): ?> 
                            <option selected  value="<?=$vs->id?>"><?=$vs->name?></option>
                              <?PHP  else: ?>
							<option value="<?=$vs->id?>"><?=$vs->name?></option>
							  <?PHP endif; ?>
                            <?PHp endforeach; ?>
                        </select>
                        <div class="error-message">{{ $errors->first('category_id') }}</div>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Sub Category<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                        <select class="form-control" name="sub_category_id" onchange="getSuperSubcategory(this.value)" id="sub_category_id">
                            <option value="">Select Sub Category</option>
                            <?PHP foreach($sub_category_list as $vs): ?>
							 <?PHP if($product_details->sub_category_id==$vs->id): ?>
							   <option selected value="<?=$vs->id?>"><?=$vs->name?></option>
                             <?PHP else: ?>							 
                            <option value="<?=$vs->id?>"><?=$vs->name?></option>
                             <?PHP endif; ?>
							<?PHp endforeach; ?>
                        </select>
                        <div class="error-message">{{ $errors->first('subcategory_id') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Brand</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="brand_id"  id="brand_id">
                                    <option value="">Select Brand</option>
                                    <?PHP foreach($brandList as $vs): ?>
                                    <?PHP if($product_details->brand_id==$vs->id): ?>
                                    <option selected value="<?=$vs->id?>"><?=$vs->name?></option>
                                    <?PHP else: ?>
                                    <option value="<?=$vs->id?>"><?=$vs->name?></option>
                                    <?PHP endif; ?>
                                    <?PHp endforeach; ?>
                                </select>
                                <div class="error-message">{{ $errors->first('brand_id') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Product Name<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Name" id="name" value="{{$product_details->name}}" class="form-control" name="name">
                                <div class="error-message">{{ $errors->first('name') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">

                            <label for="inputEmail3"  class="col-sm-4 form-control-label">Price and Unit<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-box-unit row">
                                    <div class="input-unit col-md-3">
                                        <small>Ex:(Weight) 1KG,100Ml </small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <small>Ex:Product MRP </small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <small>Ex:Discount Amount(₹) </small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <small>Ex:Product Qty(Stock) </small>
                                    </div>
                                    <div class="add_field_button" ></div>
                                </div>
                            </div>
                           <?php $plusSign =1;
                           $productItem = Helper::getProductItemBySellerId($product_details->id,$product_details->user_id);?>
                            @foreach($productItem as $ks=>$vs)
                                <input type="hidden" name="item_id[]" value="<?=$vs->id?>" >
                                <div class="col-sm-4"></div>
                                <div id="pTable" class="col-sm-8">
                                    <div class="input_fields_wrap" >
                                        <div class="input-box-unit row">
                                            <div class="input-unit col-md-3">
                                                <input type="text" name="weight[]" placeholder="weight" value="<?=$vs->weight?>" class="form-control weight">
                                            </div>
                                            <div class="error-message">{{ $errors->first('weight') }}</div>
                                            <div class="input-unit col-md-3">
                                                <input type="text" placeholder="price" name="price[]" value="<?=$vs->price?>" class="form-control price">
                                            </div>
                                            <div class="input-unit col-md-3">
                                                <input type="text" class="form-control offer" name="offer[]"  value="<?=$vs->offer?>" placeholder="offer">
                                            </div>
                                            <div class="input-unit col-md-3">
                                                <input type="text" class="form-control qty" name="qty[]" value="<?=$vs->qty?>" placeholder="Qty">
                                            </div>
                                            <?php if($plusSign == 1){?>

                                            <div class="add_field_button" ><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                                            <?php }else{ ?>
                                                <div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div>
                                            <?php } ?>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <?php $plusSign++ ?>
                            @endforeach
                            <div class="form-group">
                                <div class="col-sm-4"></div>
                                <div id="pTable" class="col-sm-8">
                                    <div class="input_fields_wrap_add" ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Product GST (%)<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="GST" id="p_gst" value="{{$product_details->p_gst}}" class="form-control" name="p_gst">
                                <div class="error-message">{{ $errors->first('p_gst') }}</div>
                            </div>
                        </div>
                    </div>
                   <div class="col-sm-12">
						<div class="form-group row">
						  <label class="col-sm-4 form-control-label">Is In COD :</label>
						   <div class="col-sm-8">
                        <input type="radio" <?PHP if($product_details->is_cod==1) { echo 'checked'; } ?> name="is_cod" id="is_cod" value='1'>Yes
                        <input type="radio" <?PHP if($product_details->is_cod==0) { echo 'checked'; } ?> name="is_cod" id="is_cod" value='0' >No
					  </div>
					  </div>
					</div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label">IS In Return :</label>
                            <div class="col-sm-8">
                                <input type="radio" <?PHP if($product_details->is_return==1) { echo 'checked'; } ?> name="is_return" id="is_return" value='1'>Yes
                                <input type="radio" <?PHP if($product_details->is_return==0) { echo 'checked'; } ?> name="is_return" id="is_return" value='0' >No
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label">IS Exchange :</label>
                            <div class="col-sm-8">
                                <input type="radio" <?PHP if($product_details->is_exchange==1) { echo 'checked'; } ?> name="is_exchange" id="is_exchange" value='1'>Yes
                                <input type="radio" <?PHP if($product_details->is_exchange==0) { echo 'checked'; } ?> name="is_exchange" id="is_exchange" value='0' >No
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select Color<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" multiple="multiple" name="color[]" id="color">
                                    @foreach($colorList as $vs)
                                        <?PHP if(in_array($vs->code,explode(",",$product_details->color))): ?>
                                        <option selected value="{{$vs->code}}">{{$vs->value}}({{$vs->code}})</option>
                                        <?PHP else: ?>
                                        <option value="{{$vs->code}}">{{$vs->value}}({{$vs->code}})</option>
                                        <?PHP endif; ?>
                                    @endforeach
                                </select>
                                <div class="error-message">{{ $errors->first('color') }}</div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Product Description<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" class="form-control">
								{{$product_details->description}}</textarea>
                                <div class="error-message">{{ $errors->first('description') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Related Product</label>
                            <div class="col-sm-8">

                                <select class="form-control" multiple="multiple" name="related_product[]" id="related_product">

                                    <?php $pRelatedIds = explode(',',$product_details->related_product);
                                    foreach($productList as $vs){
                                    $selected = in_array( $vs->id, $pRelatedIds ) ? ' selected="selected" ' : '';
                                    ?>
                                    <option value="<?php echo $vs->id; ?>" <?php echo $selected; ?>><?php echo $vs->name; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="error-message">{{ $errors->first('status') }}</div>
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-12">
                         <div class="form-group row">
                                 <label class="col-sm-4 form-control-label">Product Images</label>
                                 <div class="content box-middle">
                                 <div class="box">
                                 <input type = "file" name = "upload" multiple = "multiple" class = "form-control inputfile inputfile-2 user_picked_files" />
                                     <label for="file-2"><span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span></label>
                                 </div>
                                 </div>
                             <div class = "form-group cvf_order">
                                 <input type = "hidden" class = "form-control cvf_hidden_field" value = "" />
                             </div>
                             <ul class = "cvf_uploaded_files"></ul>
							 
							 <div class="catalog-image-data">
              <div class="upload_img_container">
			   <div class="row">
					 <ul class="cvf_uploaded_files1 ui-sortable" style="height: 0px;">
					 @foreach (Helper::get_catalog_images_list($product_details->id) as $image)
                    <li file="screen2.PNG" item="0" id="remove_{{$image['id']}}">
					 <input type="hidden" name="image_id" value="<?=$image['id'];?>">
					<img class="img-thumb" src="{{URL::asset('public/admin/uploads/product/'.$image['image'])}}">
					<a href="javascript:void(0)" onclick="delete_catalog_image(this.id)" id="{{$image['id']}}" class="cvf_delete_image1"  title="Cancel">
					<img class="delete-btn1" src="{{URL::asset('public/admin/images/delete-btn.png')}}"></a>

					</li>
				    @endforeach
					</ul>
					</div>
			  </div>
            </div>
                         </div>
                     </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="submit" class = "cvf_upload_btn_update btn btn-primary waves-effect waves-light" value = "Submit" />
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- Custom and plugin javascript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
	 <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- Page-Level Scripts -->
	<?PHP
	  $options="";
	  $options= "<option value='1'>Yes</option><option selected value='0'>No</option>";    
    ;	
	?>
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
		var option_list="<?=$options;?>";
		var back_url="{{ URL::previous() }}";
	</script>
	<script>
      $('#seller').select2({
                placeholder : 'Please select Seller',
                tags: true
            });
      $('#related_product').select2({
          placeholder : 'Select Related Product',
          tags: false
      });
      CKEDITOR.replace( 'description');
        CKEDITOR.replace( 'extra_config_details');
        var delete_img="{{ URL::asset('public/admin/images/delete-btn.png') }}";
        var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
    </script>
	<input type="hidden" name="delete_image" id="delete_id" value="{{ URL::asset('public/admin/images/delete-btn.png') }}">
	<input type="hidden" name="loading_image" id="loading_id" value="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}">
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/developer/page_js/product.js') }}"></script>
    <script>
        $(document).ready(function() {
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper   		= $(".input_fields_wrap_add"); //Fields wrapper
            var add_button      = $(".add_field_button"); //Add button ID
            var x = 1; //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();

                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div class="input-box-unit row"><div class="input-unit col-md-3"><input type="text" placeholder="Weight" class="form-control weight" name="weight[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="Price" class="form-control price" name="price[]"></div><div class="input-unit col-md-3"><input placeholder="offer" type="text" class="form-control offer" name="offer[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="Qty" class="form-control qty" name="qty[]"></div><div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>'); //add input box
                }

            });
            $(document).on("click",".remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').remove(); x--;
                arrangeSno();
                return false;
            })
        });
    </script>
@stop
