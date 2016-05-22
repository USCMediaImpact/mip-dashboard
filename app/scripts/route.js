window.AppRouter = Backbone.Router.extend({
	routes: {
		'*actions': 'defaultAction'
	},
	defaultAction: function () {
		var list = new NameAnalysisCollection();
		list.fetchFromMySql().done(function(){
			Topic.publish('loadView', DashboardView, {
				collection: list
			});
		});
	}
});