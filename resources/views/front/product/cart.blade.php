@extends('front.layout.front')
@section('content')
  <section class="breadcrumbs-custum">
    <div class="container">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Home</a></li>
        <li class="breadcrumb-item active">Cart</li>
      </ul>
    </div>
  </section>
  <section class="cart-table my-5">
    <div class="container">
      <h2 class="shoping-cart-text">Shopping Cart</h2>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
          <tr class="bg-black">
            <th width="20px">Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>QTY</th>
            <th>Subtotal</th>
            <th>Saving</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
          </tr>
          </thead><tbody>
          <tr>
            <td><div class="product-img-cart"><img src="{{URL::asset('public/images/product-list-img/coca.jpg')}}" class="img-fluid"></div></td>
            <td>Brick dress</td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>199</span></td>
            <td><div class="d-flex justify-content-center align-items-center flex-wrap">
                <div class="input-group input-group-custom"> <span class="input-group-btn">
                  <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>
                  </span>
                  <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                  <span class="input-group-btn">
                  <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>
                  </span> </div>
              </div></td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>199</span></td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>50</span></td>
            <td><i class="fa fa-pencil-square-o" aria-hidden="true"></i></td>
            <td><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>
          </tr>
          <tr>
            <td><div class="product-img-cart"><img src="{{URL::asset('public/images/product-list-img/glucon-d-500x500.jpg')}}" class="img-fluid"></div></td>
            <td>Brick dress</td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>299</span></td>
            <td><div class="d-flex justify-content-center align-items-center flex-wrap">
                <div class="input-group input-group-custom"> <span class="input-group-btn">
                  <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field=""> <i class="fa fa-minus" aria-hidden="true"></i> </button>
                  </span>
                  <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                  <span class="input-group-btn">
                  <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""> <i class="fa fa-plus" aria-hidden="true"></i> </button>
                  </span> </div>
              </div></td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>299</span></td>
            <td><i class="fa fa-inr" aria-hidden="true"></i><span>50</span></td>
            <td><i class="fa fa-pencil-square-o" aria-hidden="true"></i></td>
            <td><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>
          </tr>
          </tbody>


        </table>
      </div>

      <div class="d-flex justify-content-end my-5">
        <button class="btn custom-btn mr-auto c-cart">Back TO Shopping</button>

        <a href="{{URL::to('checkout')}}"><button class="btn custom-btn c-cart"><i class="fa fa-check-square-o" aria-hidden="true"></i><span>Checkout</span></button></a>


      </div>


    </div>
  </section>

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
@endsection
