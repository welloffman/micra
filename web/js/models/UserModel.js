var UserModel = Backbone.Model.extend({
	defaults: {
		id: 0,
		name: '',
		email: '',
		password: '',
		token: '',
		created: '',
		updated: '',
		role: '',
		status: 'pending'
	},

	initialize: function(options) {

	},

	isGuest: function() {
		return !this.get('role') || this.get('role') == 'guest';
	},

	isAdmin: function() {
		return this.get('role') == 'admin';
	}
});