<?

$app->config = [
	'debug' => false,
	'log_path' => '/logs/app.log',
	'mysql' => [
		'host' => '127.0.0.1',
		'dbname' => '',
		'user' => '',
		'password' => '',
	],
	'server_timezone' => 'Europe/Moscow',
	'salt' => 'dslierg34dSheJH_sdfsejksdsdf',
	'access' => [
		
	],
	'js_files' => [
		"/js/polyfill.js",
		"/js/misc.js",

		"/js/router/AppRouter.js",

		"/js/models/AbstractCrudModel.js",
		"/js/models/UserModel.js",

		"/js/collections/UsersCollection.js",

		"/js/views/AbstractView.js",
		"/js/views/PageNotFoundView.js",
		"/js/views/modal/ModalConfirmView.js",
		"/js/views/HeaderView.js",
		"/js/views/FooterView.js",
	],
];

$extConfig = __DIR__ . '/config_ext.php';
if(is_file($extConfig)) {
	require_once($extConfig);
}
