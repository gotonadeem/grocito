<header id="sticky">
  <div class="bg-blue">
  
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="logo-div"> <a href="{{url::to('/')}}"><img src="{{ URL::asset('public/images/logo.svg') }}" class="img-fluid"></a> </div>
        <div class="d-flex justify-content-between align-items-center search-categroies">
          <!-- Check pincode dropdown  -->
          <div class="location-panel dropdown">
            <?php
            $citySessionVal = session('city_name'); //session('state_name')
            $cityStateSessionVal = session('city_name').' ('.session('pincode').')';
            $cityStateName = $citySessionVal ? $cityStateSessionVal : 'Select City';
            if(!empty($citySessionVal)){
              $modelHideShow = '';
              $showPopUp = '';
            }else{
              $modelHideShow = 'show';
              $showPopUp = 'active';
            }
            ?>
            <div class="location text-left" data-toggle="dropdown"><span><img src="{{URL::asset('public/images/address (1).png')}}" class="img-fluid"></span><span id="add_city">{{$cityStateName}}</span><span><img src="{{URL::asset('public/images/back.png')}}" class="img-fluid arrow"></span></div>
            <div class="dropdown-menu {{$modelHideShow}}">
              <h6 class="text-center">Where do you want the delivery?</h6>
              <div class="input-group custom-search">

            <input type="text" class="form-control pincode onlyNumbar" name="pincode" id="pincode" placeholder="Check Pincode">
            <div class="input-group-pincode">
              <button class="btn checkPinAvailability" type="button"><i class="fa fa-location-arrow" aria-hidden="true"></i><span>Check availability</span></button>
            </div>

          </div>

              <div class="error-msg" id="error" style="display: none; color: red"></div>
              <div class="success-msg" id="success" style="display: none; color: green"></div>
              <div class="location-body">
                <div class="location__separator text-center pt-3"><span class="separator-text">Delivery available in</span></div>
                <div class="city-wraper">
                  <div class="cities-container-list owl-carousel owl-theme location-carousel">
                    <?php foreach ($availableCity as $city){ ?>
                    <div class="item">
                    
                    <div class="cities-container-list__item">
                      <div class="img cities-container-list__item-img">
                        <div class="img-loader__wrapper__wrapper">
                          <div class="img-loader__wrapper">
                            <?php if(!empty($city->icon)){ ?>
                            <img class="img-loader__img img-loader__img--shown " alt="Jaipur" src="{{URL::asset('public/admin/uploads/city_icon/'.$city->icon)}}">
                              <?php } else{ ?>
                              <img class="img-loader__img img-loader__img--shown " alt="Jaipur" src="{{URL::asset('public/admin/uploads/city_icon/default.png')}}">
                              <?php } ?>
                            <span class="img-loader__placeholder img-loader__placeholder--circle img-loader__img img-loader__img--hidden "></span>
                          </div>
                        </div>
                      </div>
                      <div class="cities-container-list__item-name">{{$city->name}}</div>
                    </div>

                  </div>
                      <?php } ?>

                    

                  </div>

                </div>
                
                
                
              </div>
            </div>
          </div>

          <!-- end dropdown -->
           <div class="location__overlay {{$showPopUp}}"></div>
          <div class="input-group custom-search">
            <input type="text" class="form-control display_suggestion" name="q" placeholder="Search">
            <div class="input-group-append">
              <button class="btn" type="submit"><img src="{{URL::asset('public/images/search.png')}}" class="img-fluid"></button>
            </div>
          </div>
        </div> 
        <div class="user-n-cart">
          <div class="d-flex justify-content-end align-items-center flex-sm-nowrap flex-wrap w-100">
            <div class="user-div">
              <?PHP if(Auth::check()):
				  if(Auth::user()->role_id==2):
					  Auth::logout();
					  ?>
					  <script>
					  location.reload();
					  </script>
					  <?Php
				   else:
			      ?>
			     @if(Auth::user()->role_id==3)
              <li class="dropdown dropdown-custom"> <img src="{{ URL::asset('public/images/user-symbol-of-thin-outline.png') }}" class="img-fluid"><span class="user-name pl-2">{{Auth::user()->username}}</span>
                <div class="dropdown-menu"> <a class="dropdown-item" href="{{URL::to('my-account')}}">My Account</a>
                  <a class="dropdown-item" href="{{URL::to('my-order')}}">My Order</a>
                  <a class="dropdown-item" href="{{URL::to('my-wallet')}}">My Wallet</a>
                  <a class="dropdown-item" href="{{URL::to('refer-earn')}}">Refer & Earn</a>
                  <a class="dropdown-item" href="{{URL::to('user-support')}}">Support</a>

                  <a class="dropdown-item" href="{{URL::to('logout')}}">Logout</a></div>
              </li>
			     @else:
					 <span data-toggle="modal" data-target="#mySignup"> <img src="{{URL::asset('public/images/user-outline.png')}}" class="img-fluid"></span>
                     <span class="sign-in" data-toggle="modal" data-target="#mySignup">Login</span>
                     <span class="or" data-toggle="modal" data-target="#mySignup" style="padding:0px 5px">/</span>
                     <span class="sign-up" data-toggle="modal" data-target="#mySignup">Sign Up</span>
					 
			     @endif
				  <?PHP endif; ?>
              @else
                  <span data-toggle="modal" data-target="#mySignup"> <img src="{{URL::asset('public/images/user-outline.png')}}" class="img-fluid"></span>
              <span class="sign-in" data-toggle="modal" data-target="#mySignup">Login</span>
              <span class="or" data-toggle="modal" data-target="#mySignup" style="padding:0px 5px">/</span>
              <span class="sign-up" data-toggle="modal" data-target="#mySignup">Sign Up</span>
              @endif
            </div>
            <!----Noticatcion section Start--->
            @if(Auth::check())
              @if(Auth::user()->role_id==3)
            <div class="notifaction dropdown mr-3" >
              <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="updateNotifyViewStatus()">
              <i class="fa fa-bell" ></i>
                <?php if ($userNotifyCount){ ?>
              <span id="bell_count">{{$userNotifyCount}}</span>
                <?php } ?>
              </span>
              <div class="dropdown-menu notifaction-list" aria-labelledby="dropdownMenuButton">
                <ul>
                  @foreach($usernotifyData as $noti)
                    <li class="clearfix">
                      <span><img src="{{URL::asset('public/admin/uploads/user_notification/'.$noti->image)}}" class=""></span>
                      <p><strong>{{$noti->title}}</strong><br>{{strip_tags($noti->description)}}</p>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
              @endif
            @endif
            <!----Noticatcion section End--->
            <?php $ifChecoutPage =  Request::segment(1);
            if($ifChecoutPage !='checkout'){
            ?>
            <div class="cart-div dropdown dropdown-custom">
               <span data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-shopping-cart"  aria-hidden="true"></i>
                  </span>
                  <span class="sh-cart" data-toggle="dropdown" aria-expanded="true">My Cart

                    <span id="cart_count">{{$cart_count}}</span>

                  </span>

              <div class="dropdown-menu" id="cart_header">
              <div class="cart-overflow-auto">
                <?PHP
                $sum=0;
                $dc=0;
				
                ?>
                
                @if($cart_count>0)
                @foreach($cart_data as $vs)
                <div class="cart-basket">
                  <div class="d-flex justify-content-between align-item-center flex-wrap">
                    <div class="item-img">
                      <img src="{{URL::asset('public/admin/uploads/product/'.$vs->cart_image->image)}}" class="img-fluid">
                    </div>
                    <div class="item-title">
                      <h6>{{$vs->cart_product->brand ? $vs->cart_product->brand->name :'' }}</h6>
                      <h5>{{$vs->product_name}}</h5>
                      <div class="product-weight">{{$vs->weight}}</div>
                      <div class="qty-size">
                        {{$vs->qty}} x {{$vs->sprice}}
                      </div>
                    </div>
                    <div class="input-group input-group-custom qty-sec">
								<span class="input-group-btn">
							  <button type="button" onclick="cart_qty_decrement(this.id)" id="{{$vs->id}}"  class="quantity-cart-minus btn btn-danger btn-number"  data-type="minus" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>
							  </span>
                      <input type="text" id="cart_quantity" value="{{$vs->qty}}" name="quantity" class="form-control input-number qty_{{$vs->id}}" value="1" min="1" max="100" readonly>
								<span class="input-group-btn">
							  <button type="button" onclick="cart_qty_increment(this.id)" id="{{$vs->id}}"  class="quantity-cart-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>
							  </span> </div>

                    <div class="total-price">

                      <span>Rs {{$vs->qty*$vs->sprice}} </span>

                    </div>

                    <div class="close-sec">
                      <i onclick="delete_cart(this.id)" id="{{$vs->id}}" class="fa fa-times-circle" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
                
                <?PHP
                $sum= $sum+ ($vs->qty*$vs->sprice);
                $dc= 0;
                ?>
                @endforeach
                @else
                <div class="empty-basket">
                  <p class="m-0">Your basket is empty. Start shopping now!</p>
                </div>

                @endif
              </div>
                <div class="subtotal-item ml-auto">
                  <table class="table">
                    <tbody>
                    <tr>
                      <td>Sub Total</td>
                      <td>Rs {{$sum}}</td>
                    </tr>
                    <tr>
                      <td>Delivery Charged</td>
                      <td>Rs {{$dc}}</td>
                    </tr>
                    </tbody>
                  </table>
                  <?php if($cart_count){?>
                  <a href="{{URL::to('checkout')}}"><button class="custom-btn">View Cart & Checkout</button></a>
                  <?php } ?>
                </div>
              </div>


            </div>
            <?php } ?>
            <!-- end cart count -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="white-nav">
    <div class="container">
      <ul class="nav">
        <li class="nav-item categories-div">
          <div class="custom-categroies">
            <div class="categories-list dropdown">
              <nav class="main-menu">
                <ul>
                  <li class="c-dropdowns"> <a href="#">Shop by Category<i class="fa fa-angle-down"></i></a>
                    <ul class="cr-dropdown-menu">

                   @foreach($category as $catVal)
                      <li class="c-dropdowns"> <a href="{{URL::to('category/'.$catVal->cat_slug)}}">{{$catVal->cat_name}} <i class="fa fa-angle-right"></i></a>
                        <ul class="cr-dropdown-menu">
                          <?php $subCatData =Helper::get_sub_category($catVal->cat_id);?>
                          @foreach($subCatData as $subCatVal)
                          <li> <a href="{{URL::to('category/'.$catVal->cat_slug.'/'.$subCatVal['slug'])}}">{{$subCatVal['name']}}</a></li>
                          @endforeach
                        </ul>
                      </li>
                      @endforeach

                    </ul>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </li>
      {{--  <li class="nav-item icon-nav"> <a class="nav-link" href="#"><img src="{{URL::asset('public/images/discounts.png')}}"> <span>Offers</span></a> </li>
        <li class="nav-item icon-nav"> <a class="nav-link" href="#"><img src="{{URL::asset('public/images/fast-delivery.png')}}"> <span>Delivery</span></a> </li>
       --}} <li class="nav-item ml-auto become-seller"> <a href="https://www.grocito.com/join-as-seller"><button class="btn"><i class="fa fa-user-o"></i> Join us as seller</button></a> </li>
      </ul>
    </div>
  </div>
</header>