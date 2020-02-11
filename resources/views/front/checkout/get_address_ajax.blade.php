@foreach($data as $vs)

  <div class="delivery-addr__label ">
    <span class="delete-address" onclick="delete_address(this.id)" id="<?=$vs->id?>"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
	<div class="checkout-address-actions__edit" onclick="edit_address(this.id)" id="{{$vs->id}}"></div>
	<div class="checkout-address-item addr-label weight--bold">{{$vs->type}}</div>
	<div class="checkout-address-item weight--normal"><span class="capitalize">{{$vs->title}}</span>{{$vs->name}}</div>
	<div class="checkout-address-item addr-lines">{{$vs->address}},{{$vs->street}},{{$vs->state}} {{$vs->city}}, {{$vs->pincode}}</div>
	<div class="checkout-address-item addr-landmark">{{$vs->house}}</div>
	  <label class="container-radio">
		  <input class="radioput" type="radio" name="d_address"  id="{{$vs->id}}" value="{{$vs->id}}" onclick="deliver_here('{{$cityId}}',this.id)">
		  <span class="checkmark">Deliver Here</span>
	  </label>
	<!--<button class="btn btn--full btn-select-address" type="button">Deliver Here</button>-->
  </div>

@endforeach