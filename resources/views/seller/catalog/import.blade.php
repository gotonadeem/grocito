@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('public/front/css/catalog.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
      <div class="catalog-upload-heading">Import Catalog</div>
      <div class="catalog-upload-guidelines">
        <div class="bold">Guidelines:</div>
	      <div>
          </div>
      </div>
	   
     {{ Form::open(array('url' =>'seller/catalog-store-import','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'catalog_store','name'=>'catalog_store')) }}
         
        <div class="catalog-upload-form">
		 <div class="errorTxt"></div>
		  <div class="form-group">
          <label class="control-label">Main Category*:</label>
          
            <select onChange="get_subcat(this.value)" class="form-control" name="category" id="category">
			<option value="">Select Category</option>
			 @foreach ($category_list as $category)
				<option value="{{$category->id}}">{{$category->name}}</option>
             @endforeach
			</select>
          
        </div>
		<div class="form-group">
          <label class="control-label">Sub Category*:</label>
            <select onChange="get_super_subcat(this.value)" class="form-control" name="sub_category_id" id="sub_category_id">
			<option value="">Select Sub Category</option>
			</select>
         
        </div>
		
		
		
		  <div class="form-group">
          <label class="control-label">Upload csv :</label>
          <div class="files-upload-button">
		     <input type="file" name="product_csv">
          </div>
		   <div class="pull-right"><a download href="{{ URL::asset('public/uploads/csv/demo.csv') }}">Download Template</a></div>
        </div>
        <div class="text-center">
          <div class="inline-block">
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit Catalog</button>
          </div>
        </div>
      </div>
	   {{ Form::close() }}
    </div>
  </div>
</div>
<script>  	 
	var delete_img="{{ URL::asset('public/front/image/delete-btn.png') }}";
	var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
</script>
<script src="{{ URL::asset('public/front/developer/js/validation_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/developer/js/page_js/catalog.js') }}"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('public/front/js/custom-file-input.js') }}"></script> 
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
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        //CKEDITOR.replace( 'description' );
    </script>
@endsection