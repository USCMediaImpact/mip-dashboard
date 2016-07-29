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
    
    {{-- <li><a href="{{action('ReportsController@showContent')}}"><i class="fa fa-file-text-o"></i>Reports</a></li>
    <li><a href="{{action('MetricsController@showContent')}}"><i class="fa fa-bar-chart"></i>Metrics</a></li> --}}
    <li><a href="{{action('AnalysesController@show')}}"><i class="fa fa-database"></i>Analyses</a></li>
    <li><a href="{{action('DataController@showUsers')}}"><i class="fa fa-database"></i>Data</a></li>
    @can('Admin')
    <li><a href="/auth/account/management"><i class="fa fa-gear"></i>Management</a></li>
    <li><a href="/auth/account/management"><i class="fa fa-users"></i>User Management</a></li>
    @endcan
    @can('SuperAdmin')
    <li><a href="/admin/client/management"><i class="fa fa-gear"></i>Client Management</a></li>
    <li><a href="/admin/account/management"><i class="fa fa-users"></i>User Management</a></li>
    @endcan
    <li><a href="/auth/logout"><i class="fa fa-sign-out"></i>Logout</a></li>
</ul>
<div class="left-power-by">
    Powered by: <a href="http://www.mediaimpactproject.org" class="top_logo"> Media Impact Project</a>
</div>