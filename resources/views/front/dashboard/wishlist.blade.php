@extends('front.layout.front')
@section('content')
<div class="container-fluid my-3">
  <ul class="breadcrumb justify-content-start">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Wishlist</li>
  </ul>
</div>
<section class="cart-table my-5">
  <div class="container">
    <h2 class="shoping-cart-text">My Wishlist</h2>
    <div class="table-responsive">
      <table class="table table-bordered Wishlist">
        <thead>
            <tr class="bg-black">
            <th>&nbsp;</th>
            <th width="20px">Image</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Stock Status</th>
            <th width="100px">&nbsp;</th>
            
          </tr>
        </thead><tbody>
         @foreach($wishlist as $vs)
		 <tr>
          <td><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>
          <td><div class="product-img-cart"><img src="{{ URL::asset('public/admin/uploads/product/'.$vs->product->product_image[0]->image) }}" class="img-fluid"></div></td>
            <td>{{$vs->product->name}}</td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>{{$vs->product->sell_price}}</span></td>
            <td><span class="green"><?=($vs->product->stock_status==1)?'In stock':'Out of stock'?></span></td>
            <td><a href="{{URl::to('product/'.$vs->product->slug)}}" class="btn custom-btn">Add to cart</button></td>
          </tr>
          @endforeach
		  
        </tbody>
          
        
      </table>
    </div>
    
    
    
  </div>
</section>
@endSection
