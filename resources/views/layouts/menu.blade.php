@if (isset($controller) && isset($action) && isset(Config::get('menu')[$controller]))
<ul class="tabs report-tabs">
    @foreach (Config::get('menu')[$controller] as $key=>$value)
    <li class="tabs-title {{ $action == $key ? 'active' : ''}}">
        <a href="{{ action($controller . '@' . $key) }}">{{$value}}</a>
    </li>
    @endforeach
    <li class="tabs-title float-right">
		<input id="dateRange" name="date_range" />
    </li>
</ul>
@endif