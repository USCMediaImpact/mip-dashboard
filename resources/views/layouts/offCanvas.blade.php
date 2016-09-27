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
    <li class="tracking">
        <a {{$controller == 'DashboardController' ? 'class=active' : ''}} href="{{action('DashboardController@show')}}">
            <i class="fa fa-dashboard"></i>Dashboard
        </a>
    </li>
    <li class="tracking">
        <a {{$controller == 'DataController' ? 'class=active' : ''}} href="{{action('DataController@showUsers')}}">
            <i class="fa fa-data"></i>Data
        </a>
    </li>
    <li class="tracking">
        <a {{$controller == 'AnalysesController' ? 'class=active' : ''}} href="{{action('AnalysesController@show')}}">
            <i class="fa fa-analyses"></i>Analyses
        </a>
    </li>
    <li class="tracking">
        <a {{$controller == 'DataExceptionController' ? 'class=active' : ''}} href="{{action('DataExceptionController@show')}}">
            <i class="fa fa-management"></i>Management
        </a>
    </li>
    @if(Auth::user()->can('SuperAdmin'))
        <li class="tracking">
            <a {{$controller == 'SuperAdmin\AccountController' ? 'class=active' : ''}} href="/admin/account/management">
                <i class="fa fa-settings"></i>Settings
            </a>
        </li>
        <li class="tracking">
            <a {{$controller == 'SuperAdmin\ClientController' ? 'class=active' : ''}} href="/admin/client/management">
                <i class="fa fa-server"></i>Client Management
            </a>
        </li>
        <li class="tracking">
            <a {{$controller == 'SuperAdmin\MaintainController' ? 'class=active' : ''}} href="/admin/maintain">
                <i class="fa fa-refresh"></i>Data Sync Management
            </a>
        </li>
    @elseif(Auth::user()->can('Admin'))
        <li class="tracking">
            <a {{$controller == 'Auth\AccountController' ? 'class=active' : ''}} href="/auth/account/management"><i class="fa fa-settings"></i>Settings</a>
        </li>
    @endif
    <li class="tracking">
        <a href="/auth/logout">
            <i class="fa fa-sign-out"></i>Logout
        </a>
    </li>
</ul>
<div class="left-power-by">
    Powered by: <a href="http://www.mediaimpactproject.org" class="top_logo"> Media Impact Project</a>
</div>