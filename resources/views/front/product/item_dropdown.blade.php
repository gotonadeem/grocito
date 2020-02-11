@foreach($item as $vs)
	<option value="{{$vs->id}}">{{$vs->weight}} - RS {{$vs->sprice}}</option>
@endforeach