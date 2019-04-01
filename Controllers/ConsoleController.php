<?

namespace Controllers;

class ConsoleController {
	public $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function renderCustomTemplate($template, $attributes = []) {
		extract($attributes);
		ob_start();
		require_once(__DIR__ . '/../templates/' .  $template);
		return ob_get_clean();
	}
}