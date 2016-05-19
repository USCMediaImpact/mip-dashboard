'use strict';

window.DashboardView = React.createBackboneClass({
	mixins: [BaseView],
	getInitialState: function getInitialState() {
		return {
			showChart: false
		};
	},
	componentDidMount: function componentDidMount() {
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
			return {
				radialGradient: {
					cx: 0.5,
					cy: 0.3,
					r: 0.7
				},
				stops: [[0, color], [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
				]
			};
		});
	},
	componentDidUpdate: function componentDidUpdate() {
		if ($('#chartContainer').size() > 0) {
			var data = this.getCollection();
			$('#chartContainer').highcharts({
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
								color: Highcharts.theme && Highcharts.theme.contrastTextColor || 'black'
							},
							connectorColor: 'silver'
						}
					}
				},
				series: [{
					name: 'QTY',
					data: data.map(function (i) {
						return {
							name: i.get('Name'),
							y: i.get('Quantity')
						};
					})
				}]
			});
		}
	},
	onChangeView: function onChangeView(e) {
		this.setState({
			showChart: !this.state.showChart
		});
	},
	renderTable: function renderTable(data) {
		return React.createElement(
			'table',
			{ 'class': 'stack' },
			React.createElement(
				'thead',
				null,
				React.createElement(
					'tr',
					null,
					React.createElement(
						'td',
						null,
						'Name'
					),
					React.createElement(
						'td',
						null,
						'Qty'
					)
				)
			),
			React.createElement(
				'tbody',
				null,
				data.map(function (item) {
					return React.createElement(
						'tr',
						{ key: item.get('Name') },
						React.createElement(
							'td',
							null,
							item.get('Name')
						),
						React.createElement(
							'td',
							null,
							item.get('Quantity')
						)
					);
				})
			)
		);
	},
	renderChart: function renderChart(data) {
		return React.createElement('div', { id: 'chartContainer' });
	},
	render: function render() {
		var data = this.getCollection(),
		    showChart = this.state.showChart;
		return React.createElement(
			'div',
			{ className: 'row' },
			React.createElement(
				'div',
				{ className: 'small-12' },
				React.createElement(
					'button',
					{ className: 'button float-right', onClick: this.onChangeView },
					'Show ',
					showChart ? 'Table' : 'Chart'
				)
			),
			React.createElement(
				'div',
				{ className: 'small-12' },
				showChart ? this.renderChart(data) : this.renderTable(data)
			)
		);
	}
});
