<?

namespace Models;

class ConsoleApplication extends Application {
	public function __construct() {
	}

	public function runAction($arguments) {
		$consoleController = new \Controllers\ConsoleController($this);

		if(!isset($arguments[1])) {
			echo "Укажите метод консольного контроллера\n";
			exit;
		}

		$method = $arguments[1];

		if(!method_exists($consoleController, $method)) {
			echo "Метод с именем '$method' не найден в консольном контроллере\n";
			exit;
		}

		$parameters = array_slice($arguments, 2);
		$consoleController->$method(...$parameters);
	}
}