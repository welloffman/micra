<?

use models\Application;

spl_autoload_register(function ($class) {
	$parts = explode("\\", $class);
    require __DIR__ . '/../' . implode('/', $parts) . '.php';
});

session_start();

$app = new Application();

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/routes.php');

$app->run();