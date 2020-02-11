@extends('front.layout.front')

@section('content')

<section class="banner-inner-page">

<img src="{{ URL::asset('public/front/images/banner-inner.jpg') }}" class="img-fluid">

</section>



<div class="container-fluid my-3">



<ul class="breadcrumb justify-content-center">

    <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Home</a></li>

    <li class="breadcrumb-item"><a href="{{URL::to('whats-new')}}">Whats New</a></li>

    <li class="breadcrumb-item active"><?=((Request::segment(2))?Request::segment(2):'')?></li>

  </ul>



</div>



    @if(count($new_list)>0)

<div class="container">



<div class="text-listing text-center">

<h1 class="heading-title">WOMEN'S SHIRTS</h1>

<p>There is so much you can treat yourself to when it comes to pants. A sharp formal pair, a casual printed pair, a chic palazzo, those evening ready cigarette pants, trendy joggers or a smart pair of culottes. So much to try, so little time! Grab your favourite now and style it in those gazillion ways you are already visualizing right now!</p>



</div>





</div>





<section class="listing-products my-5">





<div class="container-fluid">





<div class="row">



<div class="col-md-3">



<nav class='nav-sidebar animated bounceInDown'>

	<ul>

        <li class='sub-menu'><a href='#settings'>Category<div class='fa fa-caret-down right float-right'></div></a>

  			<ul>

  				 @foreach($category as $vs)

           <?PHP

                $slug=(!is_null($vs->main_category)?$vs->main_category->slug:"");

            ?>

  				<li><a href='{{URl::to("whats-new/".$slug)}}'>{{(!is_null($vs->main_category)?$vs->main_category->name:'')}}</a></li>

          @endforeach

  			</ul>

		</li>

		    

		<?PHP 

      $url= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    if(strpos($url,"?")):

     $url= explode("?",$url);

     $base= $url[0];

     else:

     $base= $_SERVER['REQUEST_URI'];

     endif;

    $url= (isset($_GET['size']))?$base."?size=".$_GET['size']."&price=":$base."?price=";

     ?>

		<li class='sub-menu'><a href='javascript:void(0)'>Price<div class='fa fa-caret-down right float-right'></div></a>

			<ul>



				<li><a href="<?php echo $url; ?>0-1000"><i class="fa fa-inr" aria-hidden="true"></i>0</span> - <span><i class="fa fa-inr" aria-hidden="true"></i>1000</span></a></li>

            

				<li><a href="<?php echo $url; ?>1000-"><span><i class="fa fa-inr" aria-hidden="true"></i>1000 and Above</span></a></li>

			</ul>

		</li>

        

        <li class='sub-menu'><a href='#message'>Size<div class='fa fa-caret-down right float-right'></div></a>

			<?PHP

        $url= (isset($_GET['price']))?$base."?price=".$_GET['price']."&size=":$base."?size="; 

       ?>

      <ul>

				<li><a href="<?=$url?>xs">XS</a></li>

				<li><a href="<?=$url?>xxl">XXL</a></li>

				<li><a href="<?=$url?>xl">XL</a></li>

			</ul>

		</li>

	</ul>

</nav>









</div>



<div class="col-md-9">



<div class="rigt-sidebar product-list-div">



<div class="container-fluid p-0">

<div class="short-product d-flex justify-content-end align-items-center w-100 mb-3">



<div class="sh-text px-3"><h5>Short</h5></div>

<div class="drop-short">



<form action="/action_page.php">

    <select name="price" class="custom-select">

      <option selected>New</option>

      <option value="Low">Price: Low To High</option>

      <option value="High">Price: High To Low</option>

    </select>

  </form>

</div>



</div>

</div>





<div class="row">





 

     @foreach($new_list as $vs)

    <div class="col-md-4 col-sm-4 col-6">



    <div class="products-wrap">



    <div class="products-img-wrap">

    

    <a href="{{URL::to('product/'.$vs->slug)}}"><figure class="imghvr-slide-left"> <img class="card-img-top img-fluid" src="{{ URL::asset('public/admin/uploads/product/'.$vs->product_image[0]->image) }}" alt="image" style="width:100%">

                    <figcaption> <img class="card-img-top img-fluid" src="{{ URL::asset('public/admin/uploads/product/'.$vs->product_image[1]->image) }}" alt="image" style="width:100%"> 

                    </figcaption>

                  </figure></a>



    

    <div class="d-flex flex-column quickView" data-toggle="modal" data-target="#quickView"><div><i class="fa fa-eye" aria-hidden="true"></i></div><h5>Quick View</h5></div>

    </div>



    <div class="title-product">AILLAA WOOL WRAP OVER CAPE</div>

    <div class="price-product"><span class="price-new"><i class="fa fa-inr" aria-hidden="true"></i>40</span><span class="price-old"><del><i class="fa fa-inr" aria-hidden="true"></i>50</del></span>



      





    </div>

    </div>

    </div>

    @endforeach

  



</div>

   





</div>

</div>

</div>

   @else



    <div class="container">

       <div class="text-listing text-center">

       <h1 class="heading-title">No Product Found</h1>

       </div> 

  </div>



  @endif



</section>



@endsection