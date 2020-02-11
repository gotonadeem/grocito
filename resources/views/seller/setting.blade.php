@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
  <div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="catalog-upload">
      <div class="catalog-upload-heading">Setting</div>
	     {{ Form::open(array('url' =>'seller/update-setting','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'update_setting','name'=>'update_setting')) }}                
        <div class="catalog-upload-form">
		@if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif

		  <div class="form-group">
          <label class="control-label">Is Available :</label>
          <div class="files-upload-button">
		     <input type="radio" <?PHP if(Auth::user()->is_available){ echo 'checked';} ?> name="is_available" value="1"> &nbsp; Active
		     <input type="radio" id="de_active" <?PHP if(!Auth::user()->is_available){ echo 'checked';} ?> name="is_available" value="0"> &nbsp; De-Active
          </div>
        </div>
		<DIV class="form_element">
        <div class="form-group input-append date">
          <label class="control-label">From Date* :</label>
          <input placeholder="From date" name="from_date" id="from_date" value="<?PHP if(Auth::user()->from_date!='0000-00-00'){ echo date('d-m-Y',strtotime(Auth::user()->from_date)); } else { echo date('d-m-Y'); } ?>" class="form-control">
         <span class="error">{{ $errors->first('from_date')}}</span> 
	   </div>
		<div class="form-group">
          <label class="control-label">To Date* :</label>
          <input placeholder="To Date" value="<?PHP if(Auth::user()->to_date!='0000-00-00'){echo date('d-m-Y',strtotime(Auth::user()->to_date)); } else { echo date('d-m-Y'); } ?>" name="to_date" id="to_date" class="form-control ">
		  <span class="error">{{ $errors->first('to_date')}}</span>
        </div>
		</div>
		
        <div class="text-center">
          <div class="inline-block">
            <button class="btn btn-primary cvf_upload_btn catalog-submit" id="submit" type="button">Save</button>
          </div>
        </div>
      </div>
	  </form>
	  
    </div>
  </div>
</div>

<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script>
<?PHP 
if(Auth::user()->is_available==1)
{
	?>
	  <script>
	   $(".form_element").hide(); 
	  </script>
	<?PHP
}
?>
<?PHP 
if(Auth::user()->is_available==0)
{
	?>
	  <script>
	   $(".form_element").show(); 
	  </script>
	<?PHP
}
?>
<script>
$(document).ready(function(){
	
	$("form[name='update_setting']").validate({
        rules: {
            from_date: {
                required: true,
            },
            to_date: {
                required: true,
            }, 
			  },
        // Specify validation error messages
        messages: {
			from_date:"From Date is required",
			  to_date: "To Date is required",
        },
    });
	
	
	$("#submit").click(function()
	{
		var radioValue = $("input[name='is_available']:checked"). val();
		if(radioValue==0)
		{
			var from_date = $("#from_date"). val();
			var to_date = $("#to_date"). val();
			var form = $("#update_setting");
			form.validate();
			if(form.valid()) {
			 $.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: BASE_URL+'/seller/update-setting',
			type: 'POST',
			data: {is_available: radioValue,from_date:from_date,to_date:to_date },
			success: function (data) {
				window.location.reload();
			},
			error: function () {
				console.log('There is some error in user deleting. Please try again.');
			}
			   });
			}
		}
		else if(radioValue==1)
		{
			 $.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: BASE_URL+'/seller/update-setting',
			type: 'POST',
			data: {is_available: radioValue },
			success: function (data) {
				window.location.reload();
			},
			error: function () {
				console.log('There is some error in user deleting. Please try again.');
			}
			   });
		}
	});
  
	$("input[type='radio']"). click(function(){
	var radioValue = $("input[name='is_available']:checked"). val();
	if(radioValue==0){
	   $(".form_element").show();
	}
	if(radioValue==1){
	   $(".form_element").hide();
	}
	
	});
	$('[data-toggle="tooltip"]').tooltip();
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?PHP if(Auth::user()->from_date!='0000-00-00'){ $date=date('d-m-Y',strtotime(Auth::user()->from_date)); } else {  $date=date('d-m-Y'); } ?>
    <script>
        //CKEDITOR.replace( 'description' );
		$('#from_date').datepicker({
		 formate:'dd-mm-yy',
		});
		$('#to_date').datepicker({
		 formate:'dd-mm-yy',
		});
    </script>
@endsection