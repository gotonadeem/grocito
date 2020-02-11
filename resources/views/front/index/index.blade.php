@extends('front.layout.front')
@section('content')

  <section class="top-slider">
    <div class="container-custom">
      <div class="row-custom">
        <div class="large-12 columns">
          <div id="main-slider" class="owl-carousel owl-theme">
            @foreach($slider_list as $vs)
              <?php
              if($vs->link_type == 'product'){
                $sliderUrl = $vs->product?'product/'.$vs->product->slug:'';
              }else{
                $sliderUrl =$vs->main_category?'category/'.$vs->main_category->slug:'';
              }
              ?>

            <a href="{{URL::to($sliderUrl)}}">
            <div class="item">
              <div class="slide-img"><img src="{{URL::asset('public/admin/uploads/slider_image/'.$vs->images)}}"></div>
            </div>
            </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="why-choose py-sm-3 py-2">
    <div class="container">
      <div class="why-choose-grocito text-center"><img src="{{URL::asset('public/images/why-choose-img.jpg')}}" class="img-fluid"></div>
    </div>
  </section>
  <!--Advertisement Banner-->
  <section class="add-banner mt-3">
  <div class="container-fluid">
    <div class="add-box-main">
      <ul class="clearfix">
        @foreach($firstOfferBanner as $offerFirst)
          <?php
          if($offerFirst->link_type == 'product'){
          $offerFirstUrl = $offerFirst->product?'product/'.$offerFirst->product->slug:'';
          }else{
            $offerFirstUrl = $offerFirst->main_category?'category/'.$offerFirst->main_category->slug:'';
          } ?>
        <li>
          <a href="{{URL::to($offerFirstUrl)}}"><img src="{{URL::to('public/admin/uploads/banner_image/'.$offerFirst->images)}}"></a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>
  <section class="sec-categories my-3 my-sm-5">
    <div class="container">
      <div class="title mb-3 mb-sm-5">
        <h3 class="text-center">Shop by category</h3>
      </div>

      <div class="row">
        @foreach($catData as $catList)
        <div class="col-lg-3 col-md-4 col-6">
          <a class="categori-href" href="{{URL::to('category/'.$catList->slug)}}">
          <div class="categori-div">

            <img src="{{URL::asset('public/admin/uploads/category/'.$catList->image)}}" class="img-fluid">

              <h4>{{$catList->name}}</h4>
          </div>
          </a>
        </div>
        @endforeach
      </div>
      <div class="viewAll">
        <div class="row">
          @foreach($catDataAll as $catList)
			<div class="col-lg-3 col-md-4 col-6">
			  <a class="categori-href" href="{{URL::to('category/'.$catList->slug)}}">
			  <div class="categori-div">

				<img src="{{URL::asset('public/admin/uploads/category/'.$catList->image)}}" class="img-fluid">

				  <h4>{{$catList->name}}</h4>
			  </div>
			  </a>
			</div>
        @endforeach
         

        </div>
      </div>
      <div class="d-flex justify-content-center w-100 my-3"> <a class="moreless-button custom-btn" href="javascript:void(0)">View All</a> </div>
    </div>
  </section>
<!-- Middle Banner -->
  <section class="banner-adds">

    <?php
    if($middleBanner->link_type == 'product'){
      $middleUrl = $middleBanner->product?'product/'.$middleBanner->product->slug:'';
    }else{
      $middleUrl = $middleBanner->main_category?'category/'.$middleBanner->main_category->slug:'';
    }
   ?>
    <a href="{{URL::to($middleUrl)}}">
    <img src="{{URL::asset('public/admin/uploads/banner_image/'.$middleBanner->images)}}" class="img-fluid">
      </a>
  </section>
  <!-- new arrival offer-->
  <section class="product-feature my-5">
    <div class="container">
      <div class="title">
        <h3 class="text-center">New Arrival</h3>
        <a class="view-all moreless-button custom-btn"  href="{{URL::to('product-type-list/'.encrypt('new'))}}">View All</a>
      </div>
      <div class="row-custom my-3 my-sm-5">
        <div class="large-12 columns">
          <!-- Product List-->
          <div id="product-slider" class="owl-carousel owl-theme">
            <?php $exclusive = Helper::getProductByType('new'); ?>
            @foreach($exclusive as $exc)
              <?php //$ifScheme = Helper::checkSchemeOnProduct($exc->id); ?>
              <div class="item">
                <a href="{{URL::to('product/'.$exc->slug)}}">
                  <div class="slide-img">
                    <span class="off-discont ">OFFER</span>
                    <figure><img class="img-fluid" src="{{URL::asset('public/admin/uploads/product/'.$exc->image)}}"></figure>
                    <h5>{{$exc->name}}</h5>
                  </div>
                </a>
              </div>
              @endforeach
          </div>
          <!-- End Product -->
        </div>
      </div>
    </div>
  </section>
  <!-- End new arrival oofer-->
  <!-- Sunday bazar oofer-->
  <?php $sundayBazar = Helper::getProductByType('is_sunday_bazar');
  if(count($sundayBazar)>0){
  ?>
  <section class="product-feature my-5">
    <div class="container">
      <div class="title">
        <h3 class="text-center">Sunday Bazaar</h3>
        <a class="view-all moreless-button custom-btn"  href="{{URL::to('product-type-list/'.encrypt('is_sunday_bazar'))}}">View All</a>
      </div>
      <div class="row-custom my-3 my-sm-5">
        <div class="large-12 columns">
          <!-- Product List-->
          <div id="product-slider" class="owl-carousel owl-theme">

            @foreach($sundayBazar as $bazar)
              <?php //$ifScheme = Helper::checkSchemeOnProduct($bazar->id); ?>
              <div class="item">
                <a href="{{URL::to('product/'.$bazar->slug)}}">
                  <div class="slide-img">
                    <span class="off-discont ">OFFER</span>
                    <figure><img class="img-fluid" src="{{URL::asset('public/admin/uploads/product/'.$bazar->image)}}"></figure>
                    <h5>{{$bazar->name}}</h5>
                  </div>
                </a>
              </div>
              @endforeach
          </div>
          <!-- End Product -->
        </div>
      </div>
    </div>
  </section>
  <?php } ?>
  <!-- End Sunday bazar oofer-->
  <!-- grocito exclusive offer-->
  <?php $exclusive = Helper::getProductByType('is_grocito_exclusive');
    if(count($exclusive)>0){
  ?>
  <section class="product-feature my-5">
    <div class="container">
      <div class="title">
        <h3 class="text-center">Grocito Exclusive</h3>
        <a class="view-all moreless-button custom-btn"  href="{{URL::to('product-type-list/'.encrypt('is_grocito_exclusive'))}}">View All</a>
      </div>
      <div class="row-custom my-3 my-sm-5">
        <div class="large-12 columns">
          <!-- Product List-->
          <div id="product-slider" class="owl-carousel owl-theme">
            <?php $exclusive = Helper::getProductByType('is_grocito_exclusive'); ?>
            @foreach($exclusive as $exc)
              <?php //$ifScheme = Helper::checkSchemeOnProduct($exc->id); ?>
              <div class="item">
                <a href="{{URL::to('product/'.$exc->slug)}}">
                  <div class="slide-img">
                    <span class="off-discont ">OFFER</span>
                    <figure><img class="img-fluid" src="{{URL::asset('public/admin/uploads/product/'.$exc->image)}}"></figure>
                    <h5>{{$exc->name}}</h5>
                  </div>
                </a>
              </div>
              @endforeach
          </div>
          <!-- End Product -->
        </div>
      </div>
    </div>
  </section>
  <?php } ?>
  <!-- End Sunday bazar oofer-->

  <!-- Product list -->
  @foreach($allCat as $catData)
  <section class="product-feature my-5">
    <div class="container">
      <div class="title">
        <h3 class="text-center">{{$catData->cat_name}}</h3>
        <a class="view-all moreless-button custom-btn"  href="{{URL::to('/category/'.$catData->cat_slug)}}">View All</a>
      </div>
      <div class="row-custom my-3 my-sm-5">
        <div class="large-12 columns">
          <!-- Product List-->
         <div id="product-slider" class="owl-carousel owl-theme">
           <?php $productByCat = Helper::getProductByCat($catData->cat_id); ?>
            @foreach($productByCat as $productList)
           <?php $ifScheme = Helper::checkSchemeOnProduct($productList->id); ?>
            <div class="item">
              <a href="{{URL::to('product/'.$productList->slug)}}">
                <div class="slide-img">
                  @if($ifScheme)
                  <span class="off-discont ">OFFER</span>
                  @endif
                  <figure><img class="img-fluid" src="{{URL::asset('public/admin/uploads/product/'.$productList->image)}}"></figure>
                  <h5>{{$productList->name}}</h5>
                </div>
              </a>
            </div>
            @endforeach
          </div>
          <!-- End Product -->
        </div>
      </div>
    </div>
  </section>
  @endforeach
  <!-- End product list -->

  <section class="beauty-groom my-4">
    <div class="container">
	 <div class="title mb-sm-5 mb-3">
        <h3 class="text-center">{{$isSpecial->name}}</h3>
      </div>

      <div class="row">
        <div class="col-md-6 col-sm-6">
          @if(count($isSpecial->main_category_special)>0)
		  <div class="img-beauty">
            <a href="{{URL::to('category/'.$isSpecial->slug.'/'.$isSpecial->main_category_special[0]->slug)}}">
            <img src="{{URL::asset('public/admin/uploads/category/'.$isSpecial->image)}}" class="img-fluid">
            </a>
          </div>
          @endif
		</div>
        <div class="col-md-6 col-sm-6 small-img-div">
          <div class="row">
             @foreach($isSpecial->main_category_special as $ks=>$vs)

				<div class="col-md-6 col-sm-6 col-6 small-img-div-inner">
				  <div class="img-beauty-small mb-2 mb-sm-4">
                    <a href="{{URL::to('category/'.$isSpecial->slug.'/'.$vs->slug)}}">
                    <img src="{{URL::asset('public/admin/uploads/category/'.$vs->image)}}" class="img-fluid">
                    </a>
                  </div>
				</div>

			@endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- Bottom Banner -->
  <section class="banner-adds">
    <?php
          if($bottomBanner){
    if($bottomBanner->link_type == 'product'){
    $bottomUrl = $bottomBanner->product?'product/'.$bottomBanner->product->slug:'';
    }else{
    $bottomUrl = $bottomBanner->main_category?'category/'.$bottomBanner->main_category->slug:'';
    }
    ?>

    <a href="{{URL::to($bottomUrl)}}">
    <img src="{{URL::asset('public/admin/uploads/banner_image/'.$bottomBanner->images)}}" class="img-fluid">
      </a>
    <?php } ?>
  </section>
  <section class="content-writing my-5">
    <div class="container">
      <div class="description">
        <h4>Grocito – online grocery store</h4>
        <p>Did you ever imagine that the freshest of fruits and vegetables, top quality pulses and food grains, dairy products and hundreds of branded items could be handpicked and delivered to your home, all at the click of a button? India’s first comprehensive online megastore, Grocito.com, brings a whopping 20000+ products with more than 1000 brands, to over 4 million happy customers. From household cleaning products to beauty and makeup, Grocito has everything you need for your daily needs. Grocito.com is convenience personified We’ve taken away all the stress associated with shopping for daily essentials, and you can now order all your household products and even buy groceries online without travelling long distances or standing in serpentine queues. Add to this the convenience of finding all your requirements at one single source, along with great savings, and you will realize that Grocito- India’s largest online supermarket, has revolutionized the way India shops for groceries. Online grocery shopping has never been easier. Need things fresh? Whether it’s fruits and vegetables or dairy and meat, we have this covered as well! Get fresh eggs, meat, fish and more online at your convenience. Hassle-free Home Delivery options </p>
      </div>
    </div>
  </section>

<script>
//=============clock-time===============
  
 function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector('.days');
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);

    daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
initializeClock('clockdiv', deadline);
</script>

@endsection