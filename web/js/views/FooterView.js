var FooterView = AbstractView.extend({
	className: 'footer',

	templateContext: function () {
		return {
			currentYear: this.getCurrentYear()
		};
	},

	events: {
		'click .js-feedback': 'showFeedbackForm'
	},

	initialize: function(options) {
		this.fetchTemplate('footer_view');
	},

	onRender: function() {
	},

	beforeRender: function() {
		
	},

	showFeedbackForm: function() {
		var messageView = new ModalConfirmView({
			title: 'Обратная связь',
			type: 'custom',
			customView: new ModalFeedbackView()
		});
		messageView.show();
		return false;
	},

	getCurrentYear: function() {
		return moment().year();
	}
});