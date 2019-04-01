<?

require_once __DIR__ . '/vendor/autoload.php';
$app = new Models\ConsoleApplication();

require_once(__DIR__ . '/config/config.php');

$app->runAction($argv);