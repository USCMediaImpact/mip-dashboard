@if (isset($controller) && isset($action) && isset(Config::get('menu')[$controller]))
<ul class="tabs report-tabs">
    @foreach (Config::get('menu')[$controller] as $key=>$value)
    @if($key == 'showQuality')
		@can('SuperAdmin')
		<li class="tabs-title {{ $action == $key ? 'active' : ''}}">
	        <a href="{{ action($controller . '@' . $key) }}">{{$value}}</a>
	    </li>
	    @endcan
	@else
	<li class="tabs-title {{ $action == $key ? 'active' : ''}}">
        <a href="{{ action($controller . '@' . $key) }}">{{$value}}</a>
    </li>
    @endif
    @endforeach
</ul>
@endif