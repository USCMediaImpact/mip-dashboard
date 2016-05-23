window.PageViewModel = Backbone.Model.extend({
    idAttribute: 'date',
    defaults: {
        'date': null,
        'pageView': 0
    }
});