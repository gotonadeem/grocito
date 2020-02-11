	@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
    <div class="stock_div">
	<div> @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif</div>
        <div class="tab inventory">
            <a href="{{URL::to('seller/inventory')}}"><button class="tablinks">In Stock</button></a>
            <a href="{{URL::to('seller/out-of-stock')}}"><button style="background-color: #1a549c; color:#fff;" class="tablinks" id="defaultOpen" >Out Of Stock</button></a>
        </div>
        <div id="instok" style="display:block" class="tabcontent">
            <div class="stock_head">
                 @foreach($catatlog_list as $vs)  
                 <div class="col-md-6">
				<div class="catalog-container box-shadow inventry-custom">
                
                    <div>
                    <div class="catalog-content padding-10">
                        <div class="catalog-name padding-5 text-center bold font-16">{{ $vs->name}}</div>
						 <div class="catalog-name text-center font-16">No of Products: {{$vs->design}}</div>
                     </div>
                     </div>
                     <div class="row">
                     <div class="col-md-12">
                    <div class="catalog-action">
                        <a class="button-color-2 inventory-button display-block" href="{{'/seller/product-list-out-of-stock'}}/{{ $vs->id}}">View Products</a>
                        <!--<div class="button-2 pull-right">-->
                            <?PHP 
							 //$status=Helper::check_category_status($vs->id,Auth::user()->id);
							 //if($status):					 
							?>							
							<!--<button onclick="manage_stock(this.id,'in_stock')" id="{{$vs->id}}" class="button-color-3 inventory-button button-color-4">Mark In Stock</button>-->
							<?PHP //else: ?>
							<!--<button onclick="manage_stock(this.id,'out_stock')" id="{{$vs->id}}" class="button-color-3 inventory-button button-color-2">Mark Out of Stock</button>-->
							<?PHP //endif; ?>
                        <!--</div>-->
                    </div>
                    </div>
                    </div>
                    
                </div>
                </div>
                
				@endforeach				
            </div>
        </div>
        <div id="outstok" class="tabcontent">
            <div class="stock_head">
                <h3>All catalogs are in stock.</h3>
            </div>

        </div>

    </div>

</div>

<script>
 function manage_stock(value1,value2)
 {
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/change-category-status',
        type: 'POST',
        data: {id: value1,type:value2},
        success: function (response) {
		  var response= JSON.parse(response);
          if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			
			if(response.status)
			{
	           location.replace(BASE_URL+"/seller/inventory");
			}
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       }); 
 }
 
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    // Get the element with id="defaultOpen" and click on it
   //document.getElementById("defaultOpen").click();
</script>

@endsection