<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Add New Product</h3>
                    <div class="text-right"><a href="{{ URL::to('admin/product/product-list') }}" class="btn btn-info">Back</a>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'admin/product/store-product','class'=>'form-horizontal','id'=>'add_product','name'=>'add_product',"enctype"=>"multipart/form-data")) }}

                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select City<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control city" name="city_id" id="city_id" onchange="getSeller(this.value)">
                                    <option value="">Select City</option>
                                    <?PHP foreach($cityList as $city): ?>
                                    <option value="<?=$city->id?>"><?=$city->name?></option>
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
                        <select class="form-control user_id" name="user_id" id="user_id">
                            <option value="">Select Seller</option>

                        </select>
                        <div class="error-message">{{ $errors->first('user_id') }}</div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Main Category<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                        <select class="form-control" name="category_id" onchange="getSubcategory(this.value)" id="category_id">
                            <option value="">Select Category</option>
                            <?PHP foreach($category_list as $vs): ?>
                            <option value="<?=$vs->id?>"><?=$vs->name?></option>
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
                            <option value="<?=$vs->id?>"><?=$vs->name?></option>
                            <?PHp endforeach; ?>
                        </select>
                        <div class="error-message">{{ $errors->first('subcategory_id') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select Brand</label>
                            <div class="col-sm-8">
                                <select class="form-control brand" name="brand_id" id="brand_id">
                                    <option value="">Select Brand</option>
                                    <?PHP foreach($brandList as $list): ?>
                                    <option value="<?=$list->id?>"><?=$list->name?></option>
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
                                <input type="text" placeholder="Name" id="name" class="form-control" name="name">
                                <div class="error-message">{{ $errors->first('name') }}</div>
                            </div>
                        </div>
                    </div> 
					<div class="col-sm-12">
						<div class="form-group row">
						  <label class="col-sm-4 form-control-label">Is In COD :</label>
						   <div class="col-sm-8">
						  <input type="radio"  name="is_cod" id="is_cod" value='1' checked>Yes
						  <input type="radio"  name="is_cod" id="is_cod" value='0' >No
						  </div>
						</div>
					</div>
		           <div class="col-sm-12">
						 <div class="form-group row">
						  <label class="col-sm-4 form-control-label">IS In Return :</label>
						   <div class="col-sm-8">
						  <input type="radio"  name="is_return" id="is_return" value='1' >Yes
						  <input type="radio"  name="is_return" id="is_return" value='0' checked>No
						   </div>
						 </div>
				    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label">IS In Exchange :</label>
                            <div class="col-sm-8">
                                <input type="radio"  name="is_exchange" id="is_exchange" value='1' >Yes
                                <input type="radio"  name="is_exchange" id="is_exchange" value='0' checked>No
                            </div>
                        </div>
                    </div>
					<!--<div class="col-sm-12">
						<div class="form-group row">
						  <label class="col-sm-4 form-control-label">Is Shipping Free :</label>
						   <div class="col-sm-8">
						  <input type="radio"  name="is_shipping_free" id="is_shipping_free" value='1' >Yes
						  <input type="radio"  name="is_shipping_free" id="is_shipping_free" value='0' checked>No
						  </div>
						</div>
					</div> -->
					

                    <div class="col-sm-12">
                    <div class="form-group">

                        <label for="inputEmail3"  class="col-sm-4 form-control-label">Price and Unit<span class="text-danger">*</span></label>
                        <div id="pTable" class="col-sm-8">
                            <div class="input_fields_wrap">
                            <div class="input-box-unit row">
                                    <div class="input-unit col-md-3">
                                        <small>Ex:(Weight) 1KG,100Ml</small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                    <small>Ex:Product MRP</small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                    <small>Ex:Discount Amount(₹)</small>
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <small>Ex:Product Qty(Stock)</small>
                                    </div>
                                    <div class="add_field_button"></span>

</div>
                                </div>
                                <div class="input-box-unit row">
                                    <div class="input-unit col-md-3">
                                        <input type="text" name="weight[]" placeholder="weight" class="form-control weight">
                                    </div>
                                    <div class="error-message">{{ $errors->first('weight') }}</div>
                                    <div class="input-unit col-md-3">
                                        <input type="text" placeholder="MRP" name="price[]" class="form-control price">
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <input type="text" class="form-control offer" name="offer[]" placeholder="Discount Amount">
                                    </div>
                                    <div class="input-unit col-md-3">
                                        <input type="text" class="form-control qty" name="qty[]" placeholder="Qty">
                                    </div>


                                    <div class="add_field_button"><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>

                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Product GST(%)<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="GST" id="p_gst" class="form-control" name="p_gst">
                                <div class="error-message">{{ $errors->first('p_gst') }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select Color<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" multiple="multiple" name="color[]" id="color">
                                    @foreach($colorList as $vs)

                                    <option value="{{$vs->code}}">{{$vs->value}}({{$vs->code}})</option>
                                    @endforeach
                                </select>
                                <div class="error-message">{{ $errors->first('status') }}</div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Product Description<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <textarea name="description" placeholder="Enter Description" id="description" class="form-control"></textarea>
                                <div class="error-message">{{ $errors->first('description') }}</div>
                            </div>
                        </div>
                    </div>
					



                     <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Related Product<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                            <select class="form-control" multiple="multiple" name="related_product[]" id="related_product">
                                @foreach($productList as $vs)
                                   <option value="{{$vs->id}}">{{$vs->name}}</option>
                                @endforeach
                             </select>
                                <div class="error-message">{{ $errors->first('status') }}</div>
                            </div>
                        </div>
                    </div>


                  <!--<div class="col-sm-12">
						 <div class="form-group row">
						  <label class="col-sm-4 form-control-label">IS Featured :</label>
						   <div class="col-sm-8">
						  <input type="radio"  name="is_featured" id="is_featured" value='1' checked>Yes
						  <input type="radio"  name="is_featured" id="is_featured" value='0' >No
						   </div>
						 </div>
				    </div>-->

                    
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
                         </div>
						 <span class="error_image"></span>
                     </div>
                     <div class="error-message">{{ $errors->first('upload') }}</div>

                     

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type = "submit" class = "cvf_upload_btn btn btn-primary waves-effect waves-light" value = "Submit" />
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
	
	  $options= "<option value='1'>Yes</option><option selected value='0'>No</option>";	
	
	?>
	<script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
        option_list="<?=$options;?>";
		
    </script>
	   <script>
      $('#user_id').select2({
                placeholder : 'Please select Seller',
                tags: false
            }); 
            $('#related_product').select2({
                placeholder : 'Select Related Product',
                tags: false
            });
      $('.city').select2({
          placeholder:'Select city'
      });
         CKEDITOR.replace('description');
        var delete_img="{{ URL::asset('public/admin/images/delete-btn.png') }}";
        var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
    </script>
	<input type="hidden" name="delete_image" id="delete_id" value="{{ URL::asset('public/admin/images/delete-btn.png') }}">
	<input type="hidden" name="loading_image" id="loading_id" value="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}">
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/developer/page_js/product.js') }}"></script>

   <!-- <script>
        $(document).ready(function(){

            // Add new element
            $(".addMoreUnit").click(function(){

                // Finding total number of elements added
                var total_element = $(".add-div").length;

                // last <div> with element class id
                var lastid = $(".add-div:last").attr("id");
                var split_id = lastid.split("_");
                var nextindex = Number(split_id[1]) + 1;

                var max = 10;
                // Check total number elements
                if(total_element < max ){
                    // Adding new div container after last occurance of element class
                    $(".add-div:last").after("<div class='add-div' id='div_"+ nextindex +"'></div>");

                    // Adding element to <div>
                    //$("#div_" + nextindex).append("<input type='text' placeholder='Enter your skill' id='txt_"+ nextindex +"'>&nbsp;<span id='remove_" + nextindex + "' class='remove'>X</span>");
                    $("#div_" + nextindex).append(" <div class='add-div' id='div_1'> <div class='col-sm-2'> <input type='text' class='form-control' id='txt_"+ nextindex +"' placeholder='Unit' name='weight' </div></div> &nbsp;<span id='remove_" + nextindex + "' class='remove'>X</span>");

                }

            });

            // Remove element
            $('.append-div').on('click','.remove',function(){

                var id = this.id;
                var split_id = id.split("_");
                var deleteindex = split_id[1];

                // Remove <div> with id
                $("#div_" + deleteindex).remove();

            });
        });
    </script>-->
    
    
    <script>

function arrangeSno()

 {
           var i=0;
            $('#pTable tr').each(function() {
                $(this).find(".sNo").html(i);
                i++;
             });
 
    }
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID




    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="input-box-unit"><div class="input-unit col-md-3"><input type="text" placeholder="Weight" class="form-control weight" name="weight[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="MRP" class="form-control price" name="price[]"></div><div class="input-unit col-md-3"><input placeholder="Discount" type="text" class="form-control offer" name="offer[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="Qty" class="form-control qty" name="qty[]"></div><div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>'); //add input box
        }

    });


    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
        arrangeSno();
        return false;
    })
});
/*

$(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	
	
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="input-box-unit"><div class="input-unit"><input type="text" class="form-control"></div><div class="input-unit"><input type="text" class="form-control"></div><div class="input-unit"><input type="text" class="form-control"></div><div class="input-unit"><input type="text" class="form-control"></div><div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>'); //add input box
		}
	
	});
	
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
		 arrangeSno();
     return false;
	})
});
*/
</script>
    
    
    
    

@stop
