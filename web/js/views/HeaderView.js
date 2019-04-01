const HeaderView = AbstractView.extend({
	className: 'header',

	templateContext: function () {
		return {
			user: app.user,
			getMenu: _.bind(this.getMenu, this),
			isActive: _.bind(this.isActive, this)
		};
	},

	events: {
		'click .js-logout': 'logout'
	},

	initialize: function(options) {
		this.fetchTemplate('header_view');
		this.menuItems = [
			{
				route: '/', 
				active: [], 
				title: 'Home', 
				roles: []
			}
		];
	},

	onRender: function() {
	},

	beforeRender: function() {
	},

	getMenu: function() {
		return _.filter(this.menuItems, function(item) {
			return item.roles.indexOf( app.user.get('role') ) > -1;
		});
	},

	isActive: function(menuItem) {
		var routes = menuItem.active.concat([menuItem.route]);
		return routes.indexOf( app.current().route ) == -1 ? '' : 'active';
	}
});