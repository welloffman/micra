var AbstractView = Backbone.View.extend({
	template: undefined,
	renderCalled: false,
	templateContext: {},

	fetchTemplate: function(templateName) {
		if(app.templates[templateName]) {
			this.initTemplate(app.templates[templateName]);
		} else {
			$.get('/underscore/' + templateName, function(resp) {
				this.initTemplate(resp);
				app.templates[templateName] = resp;
			}.bind(this));
		}

	},

	makeTemplate: function(options) {
		this.renderCalled = true;
		if(this.template) {
			$(this.el).html(this.template(options));
		}
	},

	initTemplate: function(temp) {
		this.template = _.template(temp);
		if(this.renderCalled) {
			this.render();
		};
	},

	render: function() {
		if(_.isFunction(this.templateContext)) {
			var options = this.templateContext();
		} else {
			options = this.templateContext;
		}
		options.cid = this.cid;

		this.beforeRender();

		this.makeTemplate(options);
		this.delegateEvents();

		if(this.template) {
			this.onRender();
			this.runRenderListener();
		}

		return this;
	},

	onRender: function() {
		// todo: выполняется после формирования шаблона
	},

	beforeRender: function() {
		// todo: выполнится до формирования шаблона
	},

	afterRender: null, // todo: Переопределить как функцию. Выполнится после появления шаблона на странице. В шаблоне задать id как cid.

	runRenderListener: function() {
		if(!_.isFunction(this.afterRender)) {
			return false;
		}

		var intervalRepeat = 0;
		var intervalId = setInterval(function() {
			if(document.getElementById(this.cid)) {
				this.afterRender();
				clearInterval(intervalId);
			} else if(intervalRepeat > 20) {
				clearInterval(intervalId);
			}
			intervalRepeat++;
		}.bind(this), 50);
	}
});