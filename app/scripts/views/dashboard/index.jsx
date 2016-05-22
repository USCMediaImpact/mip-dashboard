window.DashboardView = React.createBackboneClass({
	mixins: [BaseView],
	getInitialState: function () {
		return {
			showChart: false
		}
	},
	componentDidMount: function(){
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
			return {
				radialGradient: {
					cx: 0.5,
					cy: 0.3,
					r: 0.7
				},
				stops: [
					[0, color],
					[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
				]
			};
		});
	},
	componentDidUpdate: function () {
		if (this.state.showChart) {
			var data = this.getCollection(),
				chartData = data.map(function (i) {
					return {
						name: i.get('name'),
						y: i.get('quantity')
					}
				});
			if (this.chart) {
				this.chart.series[0].setData(chartData);
			} else {

				this.chart = $('#chartContainer').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: 'USA Name Usage. January, 2015 to May, 2015'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.percentage:.1f} %',
								style: {
									color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
								},
								connectorColor: 'silver'
							}
						}
					},
					series: [{
						name: 'QTY',
						data: chartData
					}]
				});
			}
		}
	},
	componentWillUnmount: function () {
        this.chart && this.chart.destroy && this.chart.destroy();
    },
	onLoadFromMysql: function(){
		var self = this;
		this.getCollection().fetchFromMySql();
	},
	onLoadFromBigQuery: function(){
		var self = this;
		this.getCollection().fetchFromBigQuery();
	},
	onChangeView: function(e){
		this.setState({
			showChart: !this.state.showChart
		});
	},
	renderTable: function(data){
		return (
			<table class="stack">
				<thead>
					<tr>
						<td>Name</td>
						<td>Qty</td>
					</tr>
				</thead>
				<tbody>
					{data.map(function(item){
						return (
							<tr key={item.get('name')}>
								<td>{item.get('name')}</td>
								<td>{item.get('quantity')}</td>
							</tr>
		          		);
					})}
				</tbody>
			</table>
		);
	},
	renderChart: function(data){
		return (
			<div id="chartContainer"></div>
		);
	},
	render: function () {
		var data = this.getCollection(),
			showChart = this.state.showChart;
		return(
			<div className="row">
				<div className="small-12">
					<button className="button" onClick={this.onLoadFromMysql}>
						Load Data From MySql
					</button>
					<button className="button" onClick={this.onLoadFromBigQuery}>
						Load Data From BigQuery
					</button>
					<button className="button float-right" onClick={this.onChangeView}>
						Show {showChart ? 'Table' : 'Chart'}
					</button>
				</div>
				<div className="small-12">
					{showChart ? this.renderChart(data) : this.renderTable(data)}
				</div>
			</div>
			
		);
	}
});