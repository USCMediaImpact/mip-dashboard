{{-- keys => ['daily', 'weekly', 'monthly'], values => stdClass { $name => Array} --}}
@for ($i = 0; $i < count($keys); $i++)
<input name="{{$name}}[]" value="{{$keys[$i]}}" 
	{{array_key_exists($name, $values) && $values[$name] !== null && in_array($keys[$i], $values[$name]) ? 'checked':''}}
	id="{{$name}}_{{$i}}" type="checkbox">
	<label for="{{$name}}_{{$i}}">{{$keys[$i]}}</label>
@endfor