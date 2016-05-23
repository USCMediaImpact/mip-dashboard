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
		if (this.state.showChart) {
			var data = this.getCollection(),
			    categories = data.map(function (i) {
				return i.get('date');
			}),
			    chartData = data.map(function (i) {
				return {
					//name: moment(i.get('date'), 'MMMM DD, YYYY').toDate(),
					name: i.get('date'),
					y: i.get('pageView')
				};
			});
			if (this.chart && this.chart.series) {
				this.chart.series[0].setData(chartData);
			} else {
				this.chart = $('#chartContainer').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'spline'
					},
					title: {
						text: 'Page View. Apr, 2016'
					},
					xAxis: {
						categories: categories
					},
					yAxis: {
						title: {
							text: 'Page View'
						},
						min: 0
					},
					plotOptions: {
						spline: {
							marker: {
								enabled: true
							}
						}
					},
					series: [{
						name: 'page view',
						data: chartData
					}]
				});
			}
		}
	},
	componentWillUnmount: function componentWillUnmount() {
		this.chart && this.chart.destroy && this.chart.destroy();
	},
	onLoadFromMysql: function onLoadFromMysql() {
		var self = this;
		this.getCollection().fetchFromMySql();
	},
	onLoadFromBigQuery: function onLoadFromBigQuery() {
		var self = this;
		this.getCollection().fetchFromBigQuery();
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
						'Date'
					),
					React.createElement(
						'td',
						null,
						'Page View'
					)
				)
			),
			React.createElement(
				'tbody',
				null,
				data.map(function (item) {
					return React.createElement(
						'tr',
						{ key: item.get('date') },
						React.createElement(
							'td',
							null,
							item.get('date')
						),
						React.createElement(
							'td',
							null,
							item.get('pageView')
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
					{ className: 'button', onClick: this.onLoadFromMysql },
					'Load Data From MySql'
				),
				React.createElement(
					'button',
					{ className: 'button', onClick: this.onLoadFromBigQuery },
					'Load Data From BigQuery'
				),
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
