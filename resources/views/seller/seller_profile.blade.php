@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload profile-setting">
      <div class="catalog-upload-heading">My Profile</div>
	  <br><br>
	   @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
	     {{ Form::open(array('url' =>'seller/store-profile','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'update_seller','name'=>'update_seller')) }}
                              	 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-12 form-control-label">Pickup Address</label>
                                     <div class="col-sm-12">
                                         <textarea id="address_1" autocomplete="off" class="form-control" placeholder="Address1" name="address_1"><?=$user_info['address_1']; ?></textarea>
                                         <div class="error-message">{{ $errors->first('address_1') }}</div>
                                     </div>
                                 </div>
								 </div>
                                 <div class="col-sm-12">
				                   <div class="form-group row">
                                         <div class="col-sm-12">
                                             <button type="submit" name="add_user"  class="btn btn-primary waves-effect waves-light">
                                                 Update
                                             </button>
                                            
                                         </div>
                                     </div>
                                     </div>
                                 {{ Form::close() }}
	  
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
      <input type="file"  name = "upload" id="file-2" class="inputfile inputfile-2 inputfile inputfile-2 user_picked_files" data-multiple-caption="{count} files selected" multiple />
      <label for="file-2"><span class="plus">click to select files</span></label>
        </div>     
                             <div class = "form-group cvf_order">
                                 <input type = "hidden" class = "form-control cvf_hidden_field" value = "" />
                             </div>
                             <ul class = "cvf_uploaded_files"></ul>
							 
        </div>
        <div class="modal-footer text-center"><button type="button" data-dismiss="modal" class="btn btn-success">Submit</button></div>
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
        var loading_img="{{ URL::asset('public/front/image/loading.gif') }}";
   </script>
   
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
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script src="{{ URL::asset('public/front/js/bootstrap-datepicker.js') }}"></script>
    <script>
        //CKEDITOR.replace( 'description' );
		$('.datepicker').datepicker()

    </script>
@endsection