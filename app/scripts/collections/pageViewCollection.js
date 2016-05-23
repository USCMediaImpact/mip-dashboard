window.PageViewCollection = Backbone.Collection.extend({
	model: PageViewModel,
	urlRoot: 'report',
	fetchFromMySql: function (opts) {
		var model = this,
			options = {
				url: model.urlRoot + '/mysql',
				type: 'GET'
			};
		options = _.extend(opts, options);

		return this.fetch(options);
	},
	fetchFromBigQuery: function (opts) {
		var model = this,
			options = {
				url: model.urlRoot + '/bigquery',
				type: 'GET'
			};
		options = _.extend(opts, options);

		return this.fetch(options);
	}
});