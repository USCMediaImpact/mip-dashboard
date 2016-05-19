"use strict";

window.BaseView = {
	getDefaultProps: function getDefaultProps() {
		return {
			registerTopic: {}
		};
	},
	subscribe: function subscribe(name, callback) {
		var signal = Topic.subscribe(name, callback);
		this.props.registerTopic[name] = signal;
		return signal;
	},
	publish: function publish() {
		Topic.publish.apply(this, arguments);
	},
	unsubscribe: function unsubscribe(name) {
		var signal = this.props.registerTopic[name];
		_.unset(this.props.registerTopic, name);
		signal && Topic.unsubscribe(signal);
	},
	componentWillUnmount: function componentWillUnmount() {
		_.forEach(this.props.registerTopic, function (i) {
			Topic.unsubscribe(i);
		});
	}
};
