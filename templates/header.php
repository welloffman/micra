<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Micra</title>

		<base href="/">

		<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/img/favicon-16x16.png" sizes="16x16">
		
		<link href="/css/libs/bootstrap.min.css" type="text/css" rel="stylesheet">
		<link href="/css/libs/font-awesome/css/fontawesome-all.min.css" type="text/css" rel="stylesheet">
		<link href="/css/build/build.css<? if(!$debug) { ?>?<?= filemtime( $root . '/web/css/build/build.css' ) ?><? } ?>" type="text/css" rel="stylesheet">

		<script src="/js/libs/jquery-3.2.0.min.js"></script>
		<script src="/js/libs/jquery.cookie.js"></script>
		<script src="/js/libs/underscore-min.js"></script>
		<script src="/js/libs/backbone-min.js"></script>
		<script src="/js/libs/bootstrap.min.js"></script>
		<script src="/js/libs/spin.min.js"></script>
		<script src="/js/libs/moment-with-locales.js"></script>
		<script src="/js/libs/moment-timezone-with-data.js"></script>