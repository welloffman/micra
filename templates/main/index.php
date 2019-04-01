	<? foreach($js_files as $file) { ?>
		<script src="<?= $file ?><? if(!$debug) { ?>?<?= filemtime( $root . '/web' . $file ) ?><? } ?>"></script>
	<? } ?>

	<script type="text/javascript">
		var app;
		$(function() {
			app = new AppRouter();
			app.user = new UserModel(<?= json_encode($user) ?>);
			app.config = <?= json_encode($config) ?>;
			Backbone.history.start({pushState: true, root: '/'});

			$("body").on('click', '.js-route', function(e) {
				e.preventDefault();
				app.navigate( $(this).attr('href'), true );
			});
		});
	</script>
</head>


<body></body>