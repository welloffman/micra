const HeaderView = AbstractView.extend({
	className: 'header',

	templateContext: function () {
		return {
			user: app.user,
			getMenu: _.bind(this.getMenu, this),
			isActive: _.bind(this.isActive, this),
			name: this.getName(),
			rating: this.getCompanyRating(),
			profileLink: this.getProfileLink()
		};
	},

	events: {
		'click .js-logout': 'logout'
	},

	initialize: function(options) {
		this.fetchTemplate('header_view');
		this.menuItems = [
			{
				route: 'admin/companies', 
				active: ['', 'admin/company/:id', 'admin/companies/:status', 'admin/companies/:status/:page', 'admin/company/:id/:activeTab'], 
				title: 'Партнеры', 
				roles: ['admin', 'controller', 'field', 'project_manager']
			},
			{
				route: 'admin/tenders', 
				active: ['admin/tender/:id', 'admin/tenders/:status', 'admin/tenders/:status/:page', 'admin/tender/:id/bids'], 
				title: 'Тендеры', 
				roles: ['admin', 'field', 'project_manager']
			},
			{
				route: 'admin/users', 
				active: ['admin/user/:id', 'admin/users/:page'], 
				title: 'Пользователи', 
				roles: ['admin']
			},
			{
				route: 'admin/regions',
				active: ['admin/region/:id'],
				title: 'Регионы', 
				roles: ['admin', 'controller', 'field', 'project_manager']
			},
			{
				route: 'admin/projects',
				active: ['admin/projects/:status', 'admin/projects/:status/:page', 'admin/project/:ext_id'],
				title: 'Проекты', 
				roles: ['admin', 'field']
			},
			{
				route: 'partner/profile', 
				active: ['partner/profile/:activeTab'], 
				title: 'Профиль компании', 
				roles: ['partner']
			},
			{
				route: 'partner/tenders', 
				active: ['partner/tender/:id', 'partner/history', ''], 
				title: 'Тендеры', 
				roles: ['partner']
			},
			{
				route: 'partner/faq', 
				active: [], 
				title: 'Важная информация', 
				roles: ['partner']
			}
		];

		// Для контроллера по умолчанию активна вкладка с партнерами
		if(app.user.isController()) {
			var menuItem = _.find(this.menuItems, function(item) {
				return item.route == 'admin/companies';
			});
			menuItem.active.push('');
		}
	},

	onRender: function() {
	},

	beforeRender: function() {
	},

	logout: function() {
		app.request('/post/logout', {}, function(res) {
			$.removeCookie('token', { path: '/' });
			window.location.href = '/';
		});
	},

	getMenu: function() {
		return _.filter(this.menuItems, function(item) {
			return item.roles.indexOf( app.user.get('role') ) > -1;
		});
	},

	isActive: function(menuItem) {
		var routes = menuItem.active.concat([menuItem.route]);
		return routes.indexOf( app.current().route ) == -1 ? '' : 'active';
	},

	getName: function() {
		let name = '';
		if(app.user.isPartner()) {
			name = app.config.company_data.name;
		} else {
			name = app.user.get('name');
		}

		return name;
	},

	getCompanyRating: function() {
		let rating;
		if(app.user.isPartner()) {
			rating = parseFloat(app.config.company_data.rating).toFixed(2);
		}

		return rating;
	},

	getProfileLink: function() {
		let link;
		if(app.user.isPartner()) {
			link = '/partner/profile';
		} else if(app.user.isAdmin()) {
			link = `/admin/user/${app.user.get('id')}`;
		} else {
			link = '#';
		}

		return link;
	}
});