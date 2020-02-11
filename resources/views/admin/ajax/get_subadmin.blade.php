@foreach($data as $vs)
@if(in_array($vs->id,explode(",",$check->subadmin_id)))
<div class="col-md-6"><input checked type="checkbox" name="subadmin[]" value="{{$vs->id}}"> {{$vs->username}}</div>
@else
<div class="col-md-6"><input type="checkbox" name="subadmin[]" value="{{$vs->id}}"> {{$vs->username}}</div>
@endif
@endforeach
