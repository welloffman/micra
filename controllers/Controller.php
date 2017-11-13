<?

namespace controllers;

class Controller {
	public $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function render($template, $attributes = []) {
		extract($attributes);
		require_once(__DIR__ . '/../templates/header.php');
		require_once(__DIR__ . '/../templates/' .  $template);
		require_once(__DIR__ . '/../templates/footer.php');
	}

	public function jsonResponse($data) {
		header('Content-Type: application/json');
		echo json_encode($data);
		return true;
	}

	public function includeTemplate($path, $attributes = []) {
		extract($attributes);
		require(__DIR__ . '/../templates/' . $path);
	}
}