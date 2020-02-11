@extends('front.layout.front')
@section('content')
    <?php
           // echo '<pre>';print_r($product_details->slug); die('asddsff');
        $ifSchemeProduct = Helper::getIfSchemeProduct($product_details->id, $product_details->product_item[0]['id']);
         if($ifSchemeProduct ){
            $productName = $ifSchemeProduct->offer_name;
            $imageUrl = 'public/admin/uploads/scheme_product/'.$ifSchemeProduct->image;
        }else{
            $productName = $product_details->name;
            $imageUrl = 'public/admin/uploads/product/'.$product_details->image;
        }
    ?>

    <section class="breadcrumbs-custum">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{URL::to('/category/'.$product_details->main_category->slug)}}">{{$product_details->main_category->name}}</a></li>
            </ul>
            <input type="hidden" id="product_id" name="product_id" value="{{$product_details->id}}">
            <input type="hidden" id="product_weight" name="product_weight" value="{{$product_details->weight}}">
            <input type="hidden" id="seller_id" name="seller_id" value="{{$product_details->user_id}}">
            <input type="hidden" id="is_return" name="is_return" value="{{$product_details->is_return}}">
            <input type="hidden" id="is_exchange" name="is_exchange" value="{{$product_details->is_exchange}}">
        </div>
    </section>
    <section class="products-detail-page my-sm-3 my-3">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="ubislider-image-container left main-image" data-ubislider="#sliderItem" id="sliderItemZoom"></div>
                    <div id="sliderItem" class="ubislider left"> <a class="arrow prev"></a> <a class="arrow next"></a>
                        <ul id="gal1" class="ubislider-inner">
                           <?php if($ifSchemeProduct){ ?>
                                <li> <a> <img class="product-v-img product-image" src="{{URL::asset($imageUrl)}}" > </a> </li>
                           <?php  }else{  ?>
                           @foreach($all_image as $img)
                                <li> <a> <img class="product-v-img product-image" src="{{URL::asset('public/admin/uploads/product/'.$img->image)}}"> </a> </li>
                            @endforeach
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="col-md-7 col-sm-7">
                    <div class="product-details-frame">
                        <h4 class="title-product product-name produt-title">{{$productName}}</h4>
                            <?php if($product_details->product_item[0]['qty'] == 0){
                                $stockHide = 'block';
                            }else{
                                $stockHide = 'none';
                            } ?>
                            <span class="out-of-stock out-of-stock-ajax m-0" style="display:{{$stockHide}};"> OUT OF STOCK</span>

                        <h6 class="brand-title">{{$product_details->brand ?$product_details->brand->name:''}}</h6>
                        <div class="pricetag">

                            <span class="new-price">
                                <img src="{{URL::asset('public/images/icons8-rupee-32.png')}}" class="img-fluid"><span class="set-sprice">{{$product_details->product_item[0]['sprice']}}</span>
                            </span>
                            <span class="old-price">
                                <del><img src="{{URL::asset('public/images/icons8-rupee-32.png')}}" class="img-fluid"><span class="set-price">{{$product_details->product_item[0]['price']}}</span></del>
                            </span>
                            <span class="off-discont set-offer">{{$product_details->product_item[0]['offer']}} ₹ OFF</span>
                        </div>

                        <h6 class="title-seller seller-name mt-2"> Seller: <?php Helper::getSellerName($product_details->user_id); ?></h6>
                        <?php
                        $defaultSellerItem = Helper::getProductItemBySellerId($product_details->id,$product_details->user_id);
                        $sellerList = Helper::getDuplicateSeller($product_details->id);
                                $sellerCount = count($sellerList) +1 ;
                            if($sellerCount > 1){
                        ?>
                        <div class="select-type">
                            <div class="select-title" data-toggle="collapse" data-target="#select-type">
                                <h4> ({{$sellerCount}}) Seller Available</h4>
                            </div>
                            <div id="select-type" class="select-box-content collapse">
                                <ul>
                                    @foreach($sellerList as $vs)
                                        <?php $relatedSeller = Helper::getProductItemBySellerId($product_details->id, $vs->get_seller->id); ?>
                                    <li><input type="radio" class="get-product-item" name="seller_id" value="{{$vs->get_seller['id']}}"><label></label><span>{{$vs->get_seller['username']}} <b>({{$relatedSeller[0]['weight']}} - RS {{$relatedSeller[0]['sprice']}}) </b></span></li>
                                    @endforeach
                                        <li><input type="radio" class="get-product-item" name="seller_id" value="{{$product_details->user_name['id']}}" checked><label></label><span>{{$product_details->user_name['username']}} <b>({{$defaultSellerItem[0]['weight']}} - RS {{$defaultSellerItem[0]['sprice']}}) </b></span></li>
                                </ul>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <input type="radio" class="get-product-item" name="seller_id" value="{{$product_details->user_name['id']}}" checked style="visibility: hidden;">
                        <?php } ?>
                        <div class="custom-select-size">
                            <div>Select pack size:</div>
                            <input type="hidden" value="{{$product_details->id}}" class="product_id" data-id="{{$product_details->id}}">
                            <?php $productItem = Helper::getProductItemBySellerId($product_details->id,$product_details->user_id);
                               $itemQty = $productItem[0]['qty'];
                            ?>
                            <select name="item-size" class="custom-select change-product-price item item-dropdowm-list">
                                @foreach($productItem as $vs)
                                    <option value="{{$vs->id}}">{{$vs->weight}} - RS {{$vs->sprice}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <?PHP
                        $color=explode(",",$product_details->color);
                        if($color[0]!="" and $color[0]!="null"):
                        ?>
                        <div class="product_color" id="1">
                            <div class="product_size_title">Select Color</div>
                            <ul class="d-flex flex-row align-items-start justify-content-start">
                                <?PHP foreach($color as $ks=>$vs): ?>
                                <li>
                                    <input type="radio" class="color" name="color" id="color" value="<?=$vs?>" >
                                    <label style="background-color:<?=$vs?>;"></label>
                                </li>
                                <?PHp endforeach; ?>
                                <li id="color_error"></li>
                            </ul>
                        </div>
                        <?PHP else: ?>
                        <div class="product_color" id="0"></div>
                        <?PHP endif; ?> -->
                        <div class="d-flex justify-content-start align-items-center flex-wrap my-3">
                        <div class="label-text"><span>QTY:</span></div>
                        <div class="input-group input-group-custom">
                             <span class="input-group-btn">
                              <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                                  <i class="fa fa-minus" aria-hidden="true"></i>
                              </button>
                              </span>
                               <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100" readonly>
                              <span class="input-group-btn">
                              <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                  <i class="fa fa-plus" aria-hidden="true"></i>
                              </button>
                              </span>
                        </div>
                            <?php if($itemQty == 0) {
                                $hide = 'none';
                             }else{
                                $hide = 'block';
                            } ?>
                            <div class="addcart my-sm-0 my-3 in-out-stock" style="display:{{$hide}}">
                                <button class="btn custom-btn" onclick="add_to_cart()" id="add_to_cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Add to Cart</button>
                            </div>
                            <!-- <div class="favrite-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></div>-->

                        </div>
                        <div class="d-flex align-items-center justify-content-start flex-wrap share-link">
                            <label>Share This : </label>
                            <div class="social-link">

                                <ul class="nav justify-content-center align-items-center">
                                    <li class="nav-item"> <a class="nav-link" id="share_button" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>
                                    <li class="nav-item"> <a href="https://api.whatsapp.com/send?text={{URL::to('product/'.$product_details->slug)}}" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i></a> </li>
                                </ul>


                            </div>
                        </div>
                        <div class="rating product-rating">
                           <?php  $avgRating = Helper::get_rating($product_details->id);
                            $starNumber = $avgRating['average'];
                            for( $x = 0; $x < 5; $x++ )
                            {
                                if( floor( $starNumber )-$x >= 1 )
                                { echo '<i class="fa fa-star"></i>'; }
                                elseif( $starNumber-$x > 0 )
                                { echo '<i class="fa fa-star-half-o"></i>'; }
                                else
                                { echo '<i class="fa fa-star-o"></i>'; }

                            }

                            ?>
                            <small>{{$starNumber}}</small>
                        </div>
                        <span class="stock-error error" style="display: none"></span>
                        <div class="product-details mt-5 mb-3">
                            <div class="pro-title" data-toggle="collapse" data-target="#details-pro" aria-expanded="true">
                                <h4>Products details</h4>
                            </div>
                            <div id="details-pro" class="block-content collapse show">
                                <?php echo $product_details->description; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="dropdown-divider"></section>
    <?php
    if(!empty($product_details->related_product) and ($product_details->related_product !='null')){ ?>
    <section class="product-feature similar-product my-5">
        <div class="container">
            <div class="title">
                <h3 class="text-left">Similar Products</h3>
            </div>
            <div class="row-custom my-2 my-sm-2">
                <div class="large-12 columns">
                    <div id="product-slider" class="owl-carousel owl-theme">
                        <?php
                        $relatedId = explode(',',$product_details->related_product); ?>
                        @foreach ($relatedId as $id)
                            <?php $relatedProduct =  Helper::getRelatedProduct($id);
                                if($relatedProduct){
                                ?>
                            <a href="{{URL::to('/product/'.$relatedProduct->slug)}}">
                        <div class="item">
                            <div class="slide-img">
                                <figure><img class="img-fluid" src="{{URL::asset('public/admin/uploads/product/'.$relatedProduct->image)}}"></figure>
                                <h5>{{$relatedProduct->name}}</h5>
                            </div>
                        </div>
                            </a>
                        <?php } ?>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <section class="product-review my-5">
        <div class="container">
            <div class="title">
                <h3 class="text-left">Customer Review</h3>
            </div>
            <div class="review-list">
                <?php $ratingData = Helper::getProductAllRating($product_details->id);?>
                @foreach($ratingData as $ratng)
                <div class="review">

                        <h5>{{$ratng->user->username}}</h5>
                    <p class="rating">
                        <?php for($i=1;$i<=5;$i++){
                        if ($i <= $ratng->rating){?>
                            <i class="fa fa-star active"></i>
                       <?php }else{ ?>
                            <i class="fa fa-star "></i>
                        <?php    }
                            }?>

                    </p>
                    <p class="review-dis">
                        {{$ratng->message}}
                    </p>
                    <span> {{$ratng->created_at}}</span>
                </div>
                    @endforeach

            </div>
        </div>
    </section>
@section('scripts')

    <script>
        function getSellerName(sellerId) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL+'/get-seller-name',
                type: 'POST',
                data: {sellerId: sellerId },
                success: function (data) {
                    response=JSON.parse(data);
                    if(response.status) {
                        var sellerName = response.seller_name;
                        $('.seller-name').text('Seller Name: '+sellerName);
                    }

                },
                error: function () {
                    console.log('There is some error to get seller name. Please try again.');
                }
            });
        }
        $(document).on('change','.get-product-item', function () {
            var sellerId = $(this).val();
            getSellerName(sellerId);
            $('#quantity').val(1);
            $('.stock-error').hide();
            var productId = $('#product_id').val();
            $(".loader-div").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL+'/get-item-list',
                type: 'POST',
                data: {sellerId: sellerId, productId:productId },
                success: function (data) {
                    $('.item-dropdowm-list').html(data);
                    var priceId = $(".item-dropdowm-list option:selected").val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: BASE_URL+'/get-product-price',
                        type: 'POST',
                        data: {priceId: priceId },
                        success: function (data) {
                            response=JSON.parse(data);
                            if(response.status){
                                var productOffer = response.offer;
                                var productPrice = response.price;
                                var productSprice = response.sprice;
                                var productSchemeName = response.scheme_name;
                                var productImagePath = response.image_path;
                                var itemQty = response.qty;
                                if(itemQty == 0){
                                    $('.in-out-stock').hide();
                                    $('.out-of-stock-ajax').show();
                                }else {
                                    $('.in-out-stock').show();
                                    $('.out-of-stock-ajax').hide();
                                }
                                $('.set-offer').text(productOffer+' ₹ OFF');
                                $('.set-price').text(productPrice);
                                $('.set-sprice').text(productSprice);
                                $('.product-name').text(productSchemeName);
                                $('.product-image').prop('src', productImagePath);
                                $('div.main-image > img').remove();
                                $('.main-image').append("<img  src="+productImagePath+" />");
                                console.log(productSchemeName);
                                $('.select-box-content').removeClass('show');
                                //$("#success").html(response.message).show();
                            }else{
                                $("#error").html(response.message).show();
                            }
                        },
                        error: function () {
                            console.log('There is some error. Please try again.');
                        }
                    });
                    $(".loader-div").hide();
                },
                error: function () {
                    console.log('There is some error to get item. Please try again.');
                }
            });
        });
        $(document).on('change','.change-product-price', function () {
            $(".loader-div").show();
            var priceId = $(this).val();
            $('#quantity').val(1);
            $('.stock-error').hide();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL+'/get-product-price',
                type: 'POST',
                data: {priceId: priceId },
                success: function (data) {

                    response=JSON.parse(data);
                    if(response.status){
                        var productOffer = response.offer;
                        var productPrice = response.price;
                        var productSprice = response.sprice;
                        var productSchemeName = response.scheme_name;
                        var productImagePath = response.image_path;
                        var itemQty = response.qty;
                        if(itemQty == 0){
                            $('.in-out-stock').hide();
                            $('.out-of-stock-ajax').show();
                        }else {
                            $('.in-out-stock').show();
                            $('.out-of-stock-ajax').hide();
                        }

                        $('.set-offer').text(productOffer+' ₹ OFF');
                        $('.set-price').text(productPrice);
                        $('.set-sprice').text(productSprice);
                        $('.product-name').text(productSchemeName);
                      //  $('.product-image').prop('src', productImagePath);
                       // $('div.main-image img').remove();
                        //$('.main-image').append("<img src="+productImagePath+" />");
                        console.log(productSchemeName);
                        $(".loader-div").hide();
                        //$("#success").html(response.message).show();
                    }else{
                        $("#error").html(response.message).show();
                    }
                },
                error: function () {
                    console.log('There is some error. Please try again.');
                }
            });
        })
    </script>
    <script>

        $('#sliderItem').ubislider({
            arrowsToggle: true,
            type: 'ecommerce',
            hideArrows: false,
            autoSlideOnLastClick: true,
            modalOnClick: true,
            position: 'vertical',onTopImageChange: function(){
                $('#sliderItemZoom img').elevateZoom();
            }
        });

    </script>
    <script>
$(document).ready(function(){
  $("#ham").click(function(){
    $("#ham").toggleClass("color");
  });
});
</script> 
<script>
$('.sub-menu ul').show();
$(".sub-menu a").click(function () {
  $(this).parent(".sub-menu").children("ul").slideToggle("100");
  $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
});
</script> 
<script>
    function checkItemStock(itemId,quantity) {
        $(".loader-div").show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: BASE_URL+'/check-item-stock',
            type: 'POST',
            data: {itemId: itemId, quantity:quantity},
            success: function (data) {
                response=JSON.parse(data);
                if(response.status == 1) {
                    $('#quantity').val(quantity);
                }else if(response.status == 2){
                    $('.stock-error').html('Out of stock.').show();
                }else if(response.status == 3){
                    $('.stock-error').html('Only '+(quantity-1)+' items available in stock.').show();
                }
                $(".loader-div").hide();
            },
            error: function () {
                console.log('There is some error to get seller name. Please try again.');
            }
        });
    }
$(document).ready(function(){
var quantitiy=1;
   $('.quantity-right-plus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
       var itemId = $(".item-dropdowm-list option:selected").val();
       checkItemStock(itemId,quantity+1);

    });
     $('.quantity-left-minus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
        if(quantity>1){
            $('#quantity').val(quantity - 1);
            $('.stock-error').hide();
        }
    });
});

 </script>
 
 <script>

$('.wishlist').click(function(){
  $(this).toggleClass('fa-heart-o fa-heart');
});

</script>
    <script src="{{ asset('public/js/share.js') }}"></script>
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({appId: '2928389843902996', status: true, cookie: true,
                xfbml: true});
        };
        (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
            document.getElementById('fb-root').appendChild(e);
        }());
    </script>
    <script>
        $(document).ready(function(){
            $('#share_button').click(function(e){
                e.preventDefault();
                FB.ui(
                        {
                            method: 'feed',
                            name: $(".produt-title").text(),
                            link: '{{URL::to("product/".$product_details->slug)}}',
                            picture: "{{ URL::asset('public/admin/uploads/product/'.$product_details->product_image[0]->image)}}",
                            caption: $(".produt-title").text(),
                            description: "{{substr($product_details->description,0,10)}}",
                            message: ""
                        });
            });
        });
    </script>
 @stop
 <style>
  .product-slider #show-img{ width:100% }
 </style>
@endsection