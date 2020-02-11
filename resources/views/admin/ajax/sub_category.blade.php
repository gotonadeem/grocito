<option value="">Select Sub Category</option>
@foreach($data as $vs)
 <option  value="{{$vs->id}}">{{$vs->name}}</option>
@endforeach