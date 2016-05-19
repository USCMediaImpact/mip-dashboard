window.AppRouter = Backbone.Router.extend({
	routes: {
		'*actions': 'defaultAction'
	},
	defaultAction: function () {
		var list = new NameAnalysisCollection();
		list.add([{
			Name: 'James',
			Quantity: 4942431
		}, {
			Name: 'John',
			Quantity: 4834422
		}, {
			Name: 'Robert',
			Quantity: 4718787
		}, {
			Name: 'Michael',
			Quantity: 4297230
		}, {
			Name: 'William',
			Quantity: 3822209
		}, {
			Name: 'Mary',
			Quantity: 3737679
		}, {
			Name: 'David',
			Quantity: 3549801
		}, {
			Name: 'Richard',
			Quantity: 2531924
		}, {
			Name: 'Joseph',
			Quantity: 2472917
		}, {
			Name: 'Charles',
			Quantity: 2244693
		}]);
		// list.fetch().done(function () {
		Topic.publish('loadView', DashboardView, {
			collection: list
		});
		// });
	}
});