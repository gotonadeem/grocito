
           
            <?PHP $sum=0; $ssum=0;$use_wallet=0; ?>

             @if(count($cart_data)>0)

             @foreach($cart_data as $vs)

            <div class="product-details-cart">

              <div class="row">

                <div class="col-sm-4">

                  <div class="product-img"> <img src="{{ URL::asset('public/admin/uploads/product/'.$vs->cart_image->image) }}" class="img-fluid"> </div>

                </div>

                <div class="col-sm-8">

                  <div class="product-title">

                    <h6>{{$vs->cart_product->name}}</h6>

                  </div>

                  <div class="d-flex justify-content-start align-items-center flex-wrap my-3">

                    <div class="label-text"><span>QTY:</span></div>

                    <div class="input-group input-group-custom"> <span class="input-group-btn">

                      <button type="button" onclick="qty_decrement(this.id)" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" id="{{$vs->id}}" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>

                      </span>

                      <input type="text" id="quantity" name="quantity" class="form-control input-number increment_{{$vs->id}}" value="{{$vs->qty}}" min="1" max="100">

                      <span class="input-group-btn">

                      <button type="button" onclick="qty_increment(this.id)" id="{{$vs->id}}" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>

                      </span> </div>

                  </div>

                 {{($vs->cart_product->sell_price*$vs->qty)+$vs->cart_product->shipping_free_amount}}/-</span></div>
				  @if($vs->cart_product->shipping_free_amount)
				  <div>
                    <label>Shipping: </label>
                            <span class="info-deta">Free</span>
                   </div>
                   @endif

                </div>

              </div>

            </div>

            <?PHP
            //echo $vs->qty;

            if(!is_null($vs->cart_product))
              {
                if($vs->cart_product->sell_price)

                {
                  
                 //$sell_price=$vs->cart_product->sell_price;
                 $sell_price=$vs->cart_product->sell_price+$vs->cart_product->shipping_free_amount;

                 $sum= $sum + $sell_price * $vs->qty;

                }

                else

                {

                  $sum= $sum + $vs->cart_product->starting_price*$vs->qty; 

                }
				$s=$vs->cart_product->starting_price+$vs->cart_product->shipping_free_amount;
               $ssum= $ssum + $s*$vs->qty;
                 
				 $product_amount=$vs->cart_product->a_sell_price*$vs->qty;
                $wallet_amount= ($product_amount*$vs->cart_product->w_commission/100);
                $use_wallet= $use_wallet+$wallet_amount;

              }

            

            ?>

            @endforeach

            @endif

             <div class="cart-subtotal">
              <table class="table table-bordered">
                   <tr>
				 <td colspan='2'>
				     <div class="saleplus-wallet col-md-6 my-3 my-sm-2 float-left text-left w-100 p-3">
                      <div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="wallet-check" name="wallet-check">
						<label class="custom-control-label" for="wallet-check">Use Saleplus Wallet (<i class="fa fa-inr" aria-hidden="true"></i><span>{{$my_wallet_amount}}</span>)</label>
					  </div>
					 </div>
					 
					 <div class=" col-md-6 my-3 my-sm-2 float-left text-left w-100 p-3">
                      <div class="custom-control custom-checkbox">
						
						<label class='pull-right'>Wallet Pay (<i class="fa fa-inr" aria-hidden="true"></i><span>{{$use_wallet}}</span>)</label>
					  </div>
					 </div>
				 </td>
				</tr>
				<tr>
                  <th>Item Total</th>
                  <td><i class="fa fa-inr" aria-hidden="true"></i><s>{{$ssum}}</s> 
				  &nbsp;&nbsp;<i class="fa fa-inr" aria-hidden="true"></i>{{$sum}}/-</td>
                </tr>
                <tr>

                  <th>Shipping</th>

                  <td><i class="fa fa-inr" aria-hidden="true"></i>0/-</span></td>

                </tr>

             
                <tr>
                  <th><b>Order Total</b></th>
                  <td><i class="fa fa-inr" aria-hidden="true"></i><b style="font-weight: 900;">{{$sum}}/-</b></span></td>
                </tr>
              </table>

            </div>
          
          
         
        
   
