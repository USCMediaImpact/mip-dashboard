<ul class="dropdown menu dropdown-group" data-dropdown-menu>
	<li>
		<a href="#" class="button {{$class}}">{{$text}}</a>
		<ul class="menu" style="z-index: 99;">
		@foreach($buttons as $row)
	  		<li><button type="button" class="button {{$row['class']}}" 
			@foreach($row['attr'] as $a)
				{{$a[0]}}="{{$a[1]}}"
			@endforeach
	  		>{{$row['text']}}</button></li>
	  	@endforeach
		</ul>
	</li>
</ul>