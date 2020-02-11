<div class="cart-overflow-auto">
                    <?PHP
				    $sum=0;
				    $dc=0;
				   ?>
                    @if(count($cart_data)>0)
						@foreach($cart_data as $vs)
					<div class="cart-basket">
						<div class="d-flex justify-content-between align-item-center flex-wrap">
							<div class="item-img">
								<img src="{{URL::asset('public/admin/uploads/product/'.$vs->cart_image->image)}}" class="img-fluid">
							</div>
							<div class="item-title">
								<h6>{{$vs->cart_product->brand?$vs->cart_product->brand->name:''}}</h6>
								<h5>{{$vs->product_name}}</h5>
								<div class="product-weight">{{$vs->weight}}</div>
								<div class="qty-size">
									{{$vs->qty}} x {{$vs->sprice}}
								</div>
							</div>
							<div class="input-group input-group-custom qty-sec">
								<span class="input-group-btn">
							  <button type="button" onclick="cart_qty_decrement(this.id)" id="{{$vs->id}}" class="quantity-cart-minus btn btn-danger btn-number"  data-type="minus" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>
							  </span>
								<input type="text" id="cart_quantity" value="{{$vs->qty}}" name="quantity" class="form-control input-number qty_{{$vs->id}}" min="1" max="100" readonly>
								<span class="input-group-btn">
							  <button type="button" onclick="cart_qty_increment(this.id)" id="{{$vs->id}}" class="quantity-cart-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>
							  </span>
								<small class="cart_stock_error_{{$vs->id}} error d-block w-100" style="display: none;float: left"></small>
							</div>
							<input type="hidden" class="item_id_{{$vs->id}}" value="{{$vs->item_id}}">
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
								{{--<tr>
									<td>Delivery Charged</td>
									<td>Rs {{$dc}}</td>
								</tr>--}}
							</tbody>
						</table>
						<?php if(count($cart_data)>0) {?>
						<a href="{{URL::to('checkout')}}"><button class="custom-btn">View Cart & Checkout</button></a>
						<?php }?>
					</div>