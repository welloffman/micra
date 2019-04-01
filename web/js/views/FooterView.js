var FooterView = AbstractView.extend({
	className: 'footer',

	templateContext: function () {
		return {
			currentYear: this.getCurrentYear()
		};
	},

	events: {
	},

	initialize: function(options) {
		this.fetchTemplate('footer_view');
	},

	onRender: function() {
	},

	beforeRender: function() {
		
	},

	getCurrentYear: function() {
		return moment().year();
	}
});