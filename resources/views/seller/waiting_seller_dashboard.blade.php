@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
      <div class="catalog-upload-heading">Waiting</div>
	     {{ Form::open(array('url' =>'seller/update-setting','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'update_setting','name'=>'update_setting')) }}                
        <div class="catalog-upload-form">
		    <p class="text-center">Thank you for submitting all the details, Please wait while we verify your details, Your account will be activated after verification.</p>
	    </div>
	  </form>
	  
    </div>
  </div>
</div>

<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>

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
		$('.datepicker').datepicker({
		formate:'yyyy-mm-dd',
		});
    </script>
@endsection