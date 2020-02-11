<!-- Modal Header -->

<div class="modal-header">

  <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>

 <input type="hidden" id="product_id" name="product_id" value="{{$product_details->id}}">

    <input type="hidden" id="product_weight" name="product_weight" value="{{$product_details->weight}}">

<!-- Modal body -->

<div class="modal-body">

 <div class="row">

 <div class="col-md-5 col-sm-5">

 <div class="quick-slide-img">
 <?PHP
foreach($product_details->product_image as $vs1)
        {
           if($vs1->is_default): 
             $image= $vs1->image;
           endif;
        }
        ?>
 <img class="card-img-top img-fluid" src="{{ URL::asset('public/admin/uploads/product/'.$image) }}" alt="image" style="width:100%">

 </div> 

 </div> 

 

 <div class="col-md-7 col-sm-7">

 <div class="quick-view-details">

 <h4 class="heading-title">{{$product_details->name}}</h4>

 <div class="price-box"> <span class="price">Rs.

  <?PHP 

     if($product_details->sell_price>0)

     {

        echo $product_details->sell_price;  

     }

  ?>

 </span> 



  <?PHP if($product_details->sell_price>0)

     {

        ?>

         <del class="price old-price">Rs.

         <?=$product_details->starting_price;?>

         </del>

        <?php 

     }

     else

     {

       echo  $product_details->starting_price;

     } 

       ?>

  </div>

 <div class="d-flex product-info-stock-sku">

    <div>

      <label>Availability: </label>

      <span class="info-deta">In stock</span> </div>

    <div>

      <label>SKU: </label>

      <span class="info-deta"><?=$product_details->sku?></span> </div>

  </div>

  <div class="product_size">

    <div class="product_size_title">Select Size</div>

    <ul class="d-flex flex-row align-items-start justify-content-start flex-wrap">

     <?PHP 

              $sizes= explode(',',$product_details->size);
              $i=0;
              $j=1;
              foreach($sizes as $vs){
               $j++;
              ?>
              <li>
                <input type="radio" value="{{$vs[$i]}}" id="radio_{{$j}}"  name="product_radio" class="regular_radio radio_{{$j}}">
                <label for="radio_{{$j}}" style="text-transform:uppercase;">{{$vs[$i]}}</label>
              </li>
              <?PHP 
                    //$i++;
            } ?> 
             <li id="size_error"></li>
    </ul>

  </div>

  <div class="d-flex justify-content-start align-items-center flex-wrap my-3">

    <div class="label-text"><span>QTY:</span></div>

    <div class="input-group input-group-custom"> <span class="input-group-btn">

      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>

      </span>

      <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">

      <span class="input-group-btn">

      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>

      </span> </div>

    <div class="addcart">

      <button class="btn custom-btn" onclick="add_to_cart()"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Add to Cart</button>

    </div>

  </div>

  

  <div class="product-details my-5">

  

  <div class="pro-title" data-toggle="collapse" data-target="#details-pro"><h4>Details</h4></div>

  <div id="details-pro" class="collapse block-content">

  <ul>

    <?=$product_details->description?>

  </ul>                             

  </div>

  

  

  </div>

 </div>

 

 </div>

 </div>

</div>



<script src="{{ URL::asset('public/front/developer/js/product_details.js') }}"></script>

<script>

$(document).ready(function(){

var quantitiy=0;

   $('.quantity-right-plus').click(function(e){

       

        // Stop acting like a button

        e.preventDefault();

        // Get the field name

        var quantity = parseInt($('#quantity').val());        

        // If is not undefined

            $('#quantity').val(quantity + 1);

            // Increment

        

    });



     $('.quantity-left-minus').click(function(e){

        // Stop acting like a button

        e.preventDefault();

        // Get the field name

        var quantity = parseInt($('#quantity').val());

        

        // If is not undefined

      

            // Increment

            if(quantity>0){

            $('#quantity').val(quantity - 1);

            }

    });

    

});

 </script>

