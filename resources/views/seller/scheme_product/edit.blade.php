@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('public/front/css/catalog.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
      <div class="catalog-upload-heading">Edit Catalog</div>
      <div class="catalog-upload-guidelines">
        <div class="bold">Guidelines:</div>
	  <div>Follow these guidelines for faster approvals:
          <div><strong>Description:</strong>Kindly include all the details required for your category which the customer would need to know before buying. Sharing wrong description will lead to more returns.</div>
          <div><strong>Category:</strong> Upload the products from the same category that you have chosen.</div>
          <div><strong>Products:</strong>Include at least 6 products in the catalog.</div>
          <div><strong>Prices:</strong>Enter final selling prices inclusive of commission and GST. Don't include shipping because the seller is not charged for shipping.<!-- /react-text --></div>
          <div><strong>Files:</strong>It is recommended you follow the template shared by Saleplus.</div>
          <div><strong>Images:</strong>Make sure you are sharing product images, either directly or through a link in the Excel template</div>
        </div>
      </div>
	    <form class="attireCodeToggleBlock" id="edit_catalog" name="edit_catalog" action="">
		<input type="hidden" name="catalog_id" id="catalog_id" value="{{$product_details->id}}">
        <div class="catalog-upload-form">
		 <div class="errorTxt"></div>
		  <div class="form-group">
          <label class="control-label">Catalog Name :</label>
          <div class="files-upload-button">
		     <input type="text" placeholder="Enter product Name"  name="name" id="name" value="{{$product_details->name}}" class="form-control">
          </div>
        </div>
		
		<div class="form-group">
          <label class="control-label">Brand Name* :</label>
          <div class="files-upload-button">
		      <select name="brand_id" id="brand_id" class="form-control">
			   <option value="">Select Brand</option>
			  @foreach($brand as $vs)
			      @if($product_details->brand_id==$vs->id)
			      <option selected value="{{$vs->id}}">{{$vs->name}}</option>
			      @else:
				   <option value="{{$vs->id}}">{{$vs->name}}</option>
			      
				  @endif
				  
			  @endforeach
			  </select>
          </div>
        </div>
		
		
        <div class="form-group">
          <label class="control-label">Catalog / Product Description:</label>
          <textarea rows="6" class="form-control" placeholder="Enter the catalog / product description. 
Include the description, material/ fabric, colour, pattern, sizes, dimensions/ measurements, type etc. whatever applicable. 
Add other relevant information if applicable." id="description" name="description">
     {{$product_details->description}}
</textarea>
        </div>
        <div class="form-group">
          <label class="control-label">Category*:</label>
          <div class="multi-select-container">
            <select class="form-control" onChange="get_subcat(this.value)" name="category" id="category">
			 @foreach ($category_list as $category)
			    @if($product_details->main_category->id==$category->id)
				<option selected value="{{$category->id}}">{{$category->name}}</option>
			    @else
				<option value="{{$category->id}}">{{$category->name}}</option>	
                @endif			
             @endforeach
			</select>
          </div>
        </div>
		
		<div class="form-group">
          <label class="control-label">Sub Category*:</label>
            <select onChange="get_super_subcat(this.value)" class="form-control" name="sub_category_id" id="sub_category_id">
			  <option value="">Select Sub category</option> 
			  @foreach(Helper::get_sub_category($product_details->category_id) as $vs):
					@if($product_details->sub_category_id==$vs['id'])
					<option selected value="{{$vs['id']}}">{{$vs['name']}}</option>
					@else
					 <option value="{{$vs['id']}}">{{$vs['name']}}</option>>	
					@endif				   
			  @endforeach
			</select>
        </div>
		
		<div class="form-group">
          <label class="control-label">Super Sub Category*:</label>
            <select  class="form-control" name="super_sub_category_id" id="super_sub_category_id">
			  <option value="">Select Super Sub category</option> 
			  @foreach(Helper::get_super_sub_category($product_details->sub_category_id) as $vs):
					@if($product_details->super_sub_category_id==$vs['id'])
					<option selected value="{{$vs['id']}}">{{$vs['name']}}</option>
					@else
					 <option value="{{$vs['id']}}">{{$vs['name']}}</option>>	
					@endif				   
			  @endforeach
			</select>
        </div>
		
		
		 <div class="form-group">
                   
                    <label for="inputEmail3"  class="control-label">Price and Unit<span class="text-danger">*</span></label>
             <?php $plusSign =1; ?>
             @foreach($product_details->product_item as $ks=>$vs)
					<div id="pTable" class="col-sm-12">
                    <div class="input_fields_wrap">
                     <div class="input-box-unit">
						<div class="input-unit col-md-3">
						   <input type="text" name="weight[]" placeholder="weight" value="<?=$vs->weight?>" class="form-control weight">
						</div>
						<div class="input-unit col-md-3">
						  <input type="text" placeholder="price" name="price[]" value="<?=$vs->price?>" class="form-control price">
						</div>
						<div class="input-unit col-md-3">
						    <input type="text" class="form-control sprice" name="sprice[]" value="<?=$vs->sprice?>" placeholder="Special Price">
						</div>
						<div class="input-unit col-md-3">
						    <input type="text" class="form-control qty" name="qty[]" value="<?=$vs->qty?>" placeholder="Qty">
						</div>


                         <?php if($plusSign == 1){?>

                         <div class="add_field_button" ><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span></div>
                             <?php }else{ ?>
                             <div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div>
                             <?php } ?>


					  </div>
                       </div>     
                     </div>
                 <?php $plusSign++ ?>
					@endforeach


             <div id="pTable" class="col-sm-12">
                 <div class="input_fields_wrap_add" ></div>
             </div>
                    
                    </div>
					
	     
        <div class="form-group">
          <label class="control-label">Images:</label>
          <div class="relative">
          <div class="content box-middle">
          <div class="box2">
	             <input type="file" name="file-2[]" id="file-2"  name="upload" id="upload" class="inputfile inputfile-2  inputfile-2 user_picked_files" data-multiple-caption="{count} files selected" multiple />
                 <label for="file-2"><span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></br>
click to select files</span></label>
                 
                 </div>
                 </div>
                 <div class = "form-group cvf_order">
                                 <input type = "hidden" class = "form-control cvf_hidden_field" value = "" />
                             </div>
                             
                             
                             <ul class = "cvf_uploaded_files" id="image_collection"></ul>
							 
    		 <div class="catalog-image-data">
              <div class="upload_img_container">
			   <div class="row">
					 <ul class="cvf_uploaded_files1 ui-sortable" style="height: 0px;">
					 @foreach (Helper::get_catalog_images_list($product_details->id) as $image)
                    <li file="screen2.PNG" item="0" id="remove_{{$image['id']}}">
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
		        
         <div class="row">
        <div class="text-center">
          <div class="inline-block">
            <button class="btn btn-primary cvf_upload_btn_update catalog-submit" type="button">Submit Catalog</button>
          </div>
        </div>
		</div>
      </div>
	  
	  <div class="modal fade add_img" id="add_image" role="dialog">
    <div class="modal-dialog"> 
     <!-- Modal content-->
      
	  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Drop Files Here Or Click to Add Files</h4>
        </div>
        <div class="modal-body">  
        <div class="box">
      <input type="file"  name="upload" id="upload" class="inputfile inputfile-2 inputfile inputfile-2 user_picked_files" data-multiple-caption="{count} files selected" multiple />
      <label for="file-2"><span class="plus">click to select files</span></label>
    </div>     
      
							 
        </div>
        <div class="modal-footer text-center"><button type="button" onclick="set_image" class="btn btn-success">Submit</button></div>
      </div>
	  
    </div>
  </div>
  
	  </form>
	  
    </div>
  </div>
</div>


  
  <div class="modal fade add_file" id="add_file" role="dialog">
    <div class="modal-dialog"> 
      
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Drop Files Here Or Click to Add Files</h4>
        </div>
        <div class="modal-body"> 
        <div class="box">
      <input type="file" name="file-3[]" id="file-3" class="inputfile inputfile-2" data-multiple-caption="{count} files selected" multiple />
      <label for="file-3"><span class="plus">click to select files</span></label>
    </div>  
        </div>
        <div class="modal-footer text-center"><button class="btn btn-success">Submit</button></div>
      </div>
    </div>
  </div>
 
     <script>  	 
	    var delete_img="{{ URL::asset('public/front/image/delete-btn.png') }}";
        var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
   </script>

<script src="{{ URL::asset('/public/front/js/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('public/front/developer/js/validation_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/developer/js/page_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
  
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('.multipleSelect').fastselect();
});
</script> 
<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction_btn() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

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
	var wrapper   		= $(".input_fields_wrap_add"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	
	
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="input-box-unit"><div class="input-unit col-md-3"><input type="text" placeholder="Weight" class="form-control weight" name="weight[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="Price" class="form-control price" name="price[]"></div><div class="input-unit col-md-3"><input placeholder="Special Price" type="text" class="form-control sprice" name="sprice[]"></div><div class="input-unit col-md-3"><input type="text" placeholder="Qty" class="form-control qty" name="qty[]"></div><div class="remove_field"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>'); //add input box
		}
	
	});
	
	
	$(document).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
		 arrangeSno();
     return false;
	})
});
</script>

<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        //CKEDITOR.replace( 'description' );
    </script>
@endsection