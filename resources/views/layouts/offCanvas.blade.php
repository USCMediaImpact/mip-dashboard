<ul class="vertical menu">
    <li><a href="{{action('ReportsController@showContent')}}"><i class="fa fa-file-text-o"></i>Reports</a></li>
    <li><a href="{{action('MetricsController@showContent')}}"><i class="fa fa-bar-chart"></i>Metrics</a></li>
    <li><a href="{{action('DataController@showContent')}}"><i class="fa fa-database"></i>Data</a></li>
    @can('Admin')
    <li><a href="/auth/account/management"><i class="fa fa-gear"></i>Settings</a></li>
    @endcan
    @can('SuperAdmin')
    <li><a href="/admin/client"><i class="fa fa-gear"></i>Client Management</a></li>
    <li><a href="/auth/account/management"><i class="fa fa-gear"></i>User Management</a></li>
    @endcan
    <li><a href="/auth/logout"><i class="fa fa-sign-out"></i>Logout</a></li>
</ul>