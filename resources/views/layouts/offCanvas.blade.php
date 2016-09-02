@if(isset($client) || isset($allClient))
    @can('SuperAdmin')
        <select class="clientSelector">
            @foreach($allClient as $item)
            <option value="{!! $item['value'] !!}" {!! $client['id'] == $item['id'] ? 'selected="selected"' : '' !!}>{{ $item['name'] }}</option>
            @endforeach
        </select>
    @else
        <h5 class="client_title">{{$client['name']}}</h5>
    @endcan
@endif
<ul class="vertical menu">
    <li>
        <a {{$controller == 'DashboardController' ? 'class=active' : ''}} href="{{action('DashboardController@show')}}">
            <i class="fa fa-dashboard"></i>Dashboard
        </a>
    </li>
    <li>
        <a {{$controller == 'DataController' ? 'class=active' : ''}} href="{{action('DataController@showUsers')}}">
            <i class="fa fa-data"></i>Data
        </a>
    </li>
    <li>
        <a {{$controller == 'AnalysesController' ? 'class=active' : ''}} href="{{action('AnalysesController@show')}}">
            <i class="fa fa-analyses"></i>Analyses
        </a>
    </li>
    <li>
        <a {{$controller == 'DataExceptionController' ? 'class=active' : ''}} href="{{action('DataExceptionController@show')}}">
            <i class="fa fa-management"></i>Management
        </a>
    </li>
    @if(Auth::user()->can('SuperAdmin'))
        <li>
            <a {{$controller == 'SuperAdmin\AccountController' ? 'class=active' : ''}} href="/admin/account/management">
                <i class="fa fa-settings"></i>Settings
            </a>
        </li>
        <li>
            <a {{$controller == 'SuperAdmin\ClientController' ? 'class=active' : ''}} href="/admin/client/management">
                <i class="fa fa-server"></i>Client Management
            </a>
        </li>
    @elseif(Auth::user()->can('Admin'))
        <li>
            <a {{$controller == 'Auth\AccountController' ? 'class=active' : ''}} href="/auth/account/management"><i class="fa fa-settings"></i>Settings</a>
        </li>
    @endif
    <li>
        <a href="/auth/logout">
            <i class="fa fa-sign-out"></i>Logout
        </a>
    </li>
</ul>
<div class="left-power-by">
    Powered by: <a href="http://www.mediaimpactproject.org" class="top_logo"> Media Impact Project</a>
</div>