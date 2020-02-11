<select required name="rider_id" id="rider_id" class="form-control">
<option value="" selected>Select Delivery Boy</option>
@foreach($data as $vs)
<option value="{{$vs->id}}">{{$vs->username}}</option>
@endforeach
</select>