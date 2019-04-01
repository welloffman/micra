const PageNotFoundView = AbstractView.extend({
	className: '',

	templateContext: function () {
		return {
		};
	},

	events: {
	},

	initialize: function(options) {
		this.fetchTemplate('page_not_found_view');
	},

	onRender: function() {
	},

	beforeRender: function() {
	}
});