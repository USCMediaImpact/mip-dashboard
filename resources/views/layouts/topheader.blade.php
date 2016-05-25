<div class="top-bar">
	<div class="row">
		<div class="top-bar-left">
			<ul class="dropdown menu" data-dropdown-menu>
				<li class="menu-text">Media Impact Dashboard</li>
				<li><a href="mysql">MySql</a></li>
				<li><a href="bigquery">BigQuery</a></li>
			</ul>
		</div>
		<div class="top-bar-right">
			<ul class="menu">
				<li><a class="{{ $type == 'table' ? 'button' : '' }}" href="table">Table</a></li>
				<li><a class="{{ $type == 'chart' ? 'button' : '' }}" href="chart">Chart</a></li>
			</ul>
		</div>
	</div>
</div>