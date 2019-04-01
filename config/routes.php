<?

$app->routes = [
	'/' => 'Controllers\MainController:indexAction:?',
	'/underscore/<templateName>' => 'Controllers\MainController:underscoreTemplateAction:?',
	'/underscore/<pathName>/<templateName>' => 'Controllers\MainController:underscoreTemplateAction:?',
];