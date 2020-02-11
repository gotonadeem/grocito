@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload ch-pass-div">
      <div class="catalog-upload-heading">Change Password</div>
	   @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif
	   @if(Session::has('error_message'))
		   <p class="alert alert-danger">{{ Session::get('error_message') }}</p>
       @endif
	   
	        {{ Form::open(array('url' =>'seller/change-password','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'update_seller','name'=>'update_seller')) }}
            
            <div class="col-md-3"></div>
                              	 <div class="col-md-6">
                                 <div class="change-pass-div">
								 <div class="col-sm-12">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-md-12 form-control-label">Current Password</label>
                                     <div class="col-sm-12">
                                            {{Form::password('current_password',['class'=>'form-control','autofill'=>'false','placeholder'=>'Enter Current Password','id'=>'current_password'])}}
                                            <div class="error-message">{{$errors->first('current_password')}}</div>
                                     </div>
                                 </div>
								 </div> 
								 <div class="col-sm-12">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-md-12 form-control-label">New Password</label>
                                     <div class="col-sm-12">
                                   {{Form::password('password',['class'=>'form-control','autofill'=>'false','placeholder'=>'Enter New Password','id'=>'password'])}}
                                   <div class="error-message">{{$errors->first('password')}}</div>
                                      </div>
                                 </div>
								 </div> 
								 
								 <div class="col-sm-12">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-md-12 form-control-label">Confirm Password</label>
                                     <div class="col-sm-12">
                                         {{Form::password('password_confirmation',['class'=>'form-control','id'=>'password_confirmation','placeholder'=>'Enter Confirm Password'])}}
                                        <div class="error-message">{{$errors->first('password_confirmation')}}</div>
               
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
                                     </div>
								 </div>	 
                                 <div class="col-md-3"></div>
                                 {{ Form::close() }}
	  
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