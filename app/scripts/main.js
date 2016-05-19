$(function () {
	window.Topic = window.PubSub;
	/**
	 * register loading event except ajax request is quite
	 */
	$(document).ajaxSend(function (event, xhr, settings) {
		if (settings.quite !== true) {
			Topic.publish('showLoading');
		}
	});
	$(document).ajaxComplete(function (event, xhr, settings) {
		if (settings.quite !== true) {
			Topic.publish('hideLoading');
		}
	});

	/**
	 * override base url
	 */
	var backboneSync = Backbone.sync;
	Backbone.sync = function (method, model, options) {
		if (!options.url) {
			options.url = _.isFunction(model.url) ? model.url() : model.url;
		}
		if (!options.url) {
			options.url = model.urlRoot;
		}
		options.url = '//mip-dashboard.appspot.com/api/' + options.url;
		return backboneSync(method, model, options);
	}



	var LayoutViewInstance = React.createFactory(LayoutView);
	var layoutViewInstance = LayoutViewInstance();
	var layout = ReactDOM.render(layoutViewInstance, document.getElementById('main-container'));

	var appRouter = new AppRouter;
	appRouter.on('route', function () {
		Topic.publish('showDialog');
	});
	Backbone.history.start();
});