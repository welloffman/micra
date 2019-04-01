var ModalConfirmView = AbstractView.extend({
	className: 'modal fade',

	templateContext: function() {
		return {
			title: this.title, 
			text: this.text, 
			type: this.type,
			applyButtonText: this.applyButtonText,
			sizeClass: this.sizeClass
		};
	},

	events: {
		'click .js-apply': 'apply'
	},

	initialize: function(options) {
		this.fetchTemplate('modal/modal_confirm');
		this.isApply = false;
		this.applyCallback = options.applyCallback || function() {};
		this.cancelCallback = options.cancelCallback || function() {};
		this.title = options.title;
		this.text = options.text;
		this.type = options.type; // info, question, custom
		this.sizeClass = options.sizeClass ? options.sizeClass : '';
		this.applyButtonText = options.applyButtonText || 'Закрыть';
		this.customView = options.customView;
		this.$el.on('hidden.bs.modal', _.bind(this.closedHandler, this));
	},

	onRender: function() {
		if(this.type == 'custom') {
			this.customView.remove();
			this.$el.find('.modal-content').append( this.customView.render().$el );
		}
	},

	beforeRender: function() {
	},

	show: function() {
		$('body').append(this.render().$el);
		setTimeout(function() {
			this.$el.modal('show');
		}.bind(this), 250);
	},

	close: function() {
		this.$el.modal('hide');
	},

	apply: function() {
		this.isApply = true;
		this.close();
	},

	closedHandler: function() {
		this.remove();

		if(this.isApply) {
			this.applyCallback();
		} else {
			this.cancelCallback();
		}
	}
});