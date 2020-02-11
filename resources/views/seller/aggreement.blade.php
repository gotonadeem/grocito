@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
  <div id="rightSidenav" class="right_side_bar right_side_bar_new padding-low">     
	 <fieldset id="step3">
              <h2 class="heading-seller">Seller Agreement</h2>
              <div class="article-summary full-width"> 
			  <div class="text-agreement text-agreement-seller">
			  <?PHP
			   $tillDate = date('d-m-Y', strtotime("+3 months", strtotime($user_info['created_at'])));
			   $agreement_data= str_replace("@@current_date@@",date('d-m-Y', strtotime($user_info['created_at'])),$agreement->description);
			   $agreement_data= str_replace("@@seller_name@@",$user_info['f_name']." ".$user_info['l_name'],$agreement_data);
			   $agreement_data= str_replace("@@seller_address@@",$user_info['address_1'],$agreement_data);
			   $agreement_data= str_replace("@@valid_till@@",$tillDate,$agreement_data);
			   $agreement_data= str_replace("@@commission@@",$user_info['cartlay_commission'],$agreement_data);
			  ?>
			  {!!$agreement_data!!}
                <div style="text-align:center;"> </div>
                </div> </div>
              
          </fieldset>
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