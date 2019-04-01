const AppRouter = Backbone.Router.extend({

	routes: {
		"": "index",
		"*path": "pageNotFound"
	},

	initialize: function () {
		this.templates = {};
	},

	// в это свойство можно передать функцию, которая будет выполнена перед сменой роута
	beforeChangeRouteFunction: false,

	execute: function(callback, args, name) {
		if(this.beforeChangeRouteFunction) {
			this.beforeChangeRouteFunction( _.bind(this.applyRouteFunction, this, callback, args) );
		} else {
			this.applyRouteFunction(callback, args);
		}
	},

	// этот метод применяет обработчик для роута
	applyRouteFunction: function(callback, args) {
		if(callback) {
			callback.apply(this, args);
		}
	},

	current: function() {
		let Router = this,
			fragment = Backbone.history.fragment,
			routes = _.pairs(this.routes),
			route = null, params = null, matched;

		matched = _.find(routes, function(handler) {
			route = _.isRegExp(handler[0]) ? handler[0] : this._routeToRegExp(handler[0]);
			return route.test(fragment);
		}, this);

		if(matched) {
			params = this._extractParameters(route, fragment);
			route = matched[0];
		}

		return {
			route : route,
			fragment : fragment,
			params : params
		};
	},

	getRouteVal: function(key) {
		let params = _.clone(app.current().params);
		let routeValues = {};
		_.each(app.current().route.split('/'), function(item) {
			if(item.indexOf(':') === 0) {
				routeValues[ item.substring(1, item.length) ] = params.shift();
			}
		});
		return routeValues[key];
	},

	renderPage: function(mainView) {
		this.clear();
		this.renderHeader();
		$('body').append(mainView.render().$el);
		this.renderFooter();
	},

	renderHeader: function() {
		let headerView = new HeaderView();
		$('body').append(headerView.render().$el);
	},

	renderFooter: function() {
		let footerView = new FooterView();
		$('body').append(footerView.render().$el);
	},

	index: function() {
		
	},

	pageNotFound: function() {
		this.renderPage(new PageNotFoundView());
	},

	clear: function() {
		$('body').empty();
	},

	request: async function(url, data, type) {
		if(!type) {
			type = 'POST';
		}

		let ajaxData = {
			type: type,
			url: url,
			data: data
		};

		if(data instanceof FormData) {
			ajaxData = _.extend(ajaxData, {cache: false, contentType: false, processData: false});
		}
		
		return new Promise((resolve, reject) => {
			preloader.show();
			$.ajax(ajaxData).done(resp => {
				preloader.hide();
				resolve(resp);
			}).fail(resp => {
				preloader.hide();
				reject(resp);
			});
		});
	},

	showError: function(text) {
		let messageView = new ModalConfirmView({
			title: 'Извините',
			text: text || 'Сервис временно недоступен, попробуйте позже.',
			type: 'info'
		});
		messageView.show();
	},

	getLocalDate: function(dateString) {
		let date = moment.tz(dateString, this.config.server_timezone);
		date.tz( moment.tz.guess() );
		return date;
	},

	getServerDate: function(dateString, format) {
		let date = format ? moment(dateString, format) : moment(dateString);
		date.tz(this.config.server_timezone);
		return date;
	}
});