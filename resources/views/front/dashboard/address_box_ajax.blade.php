<div class="delivery-addr__label">
    <span class="delete-address" onclick="delete_address(this.id)" id="{{$address->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
    <a class="checkout-address-actions__edit" href="{{URL::to('edit-address/'.$address->id)}}" id="{{$address->id}}"></a>
    <div class="checkout-address-item addr-label weight--bold">{{$address->type}}</div>
    <div class="checkout-address-item weight--normal"><span class="capitalize"></span>{{$address->name}}</div>
    <div class="checkout-address-item addr-lines">{{$address->address}}, {{$address->house}}, {{$address->street}}</div>
    <div class="checkout-address-item addr-landmark">{{$address->city}}, {{$address->state}}, {{$address->pincode}}</div>

</div>