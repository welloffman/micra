$(function() {
	function Preloader() {
		let spinner = new Spinner({position: 'fixed'});
		let target = $('body')[0];
		let mask = $('<div />', {'class': 'preloader-mask'});

		this.show = function() {
			$('body').append(mask);
			spinner.spin(target);
		}

		this.hide = function() {
			spinner.stop();
			mask.remove();
		}
	}

	preloader = new Preloader();
});

var misc = {
	checkEmail: function(email) {
		return /^.+@.+\..+$/.test(email);
	},

	checkPhone: function(phone) {
		return /^\d \(\d\d\d\) \d\d\d\-\d\d\-\d\d$/.test(phone);
	},

	checkInn: function(inn) {
		return (inn.length == 10 || inn.length == 12) && /^[\d]+$/.test(inn);
	},

	checkPassword: function(password) {
		return /^[\w]{8,16}$/.test(password);
	},

	makeId: function(length) {
		if(!length) {
			length = 10;
		}

		let id = '';
		let chars = ['0','1','2','3','4','5','6','7','8','9','Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M'];
		for(let i = 0; i < length; i++) {
			id += chars[ Math.floor( Math.random() * chars.length ) ];
		}
		return id;
	}
};