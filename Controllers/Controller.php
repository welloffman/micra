<?

namespace Controllers;

class Controller {
	public $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function indexAction() {
		$user = $this->app->getCurrentUser();
		$config = [
			'server_timezone' => $this->app->config['server_timezone'],
		];
		
		$this->render('main/index.php', [
			'user' => $user->getPublicData(),
			'config' => $config,
			'js_files' => $this->app->config['js_files'],
			'root' => $this->app->rootPath(),
			'debug' => $this->app->config['debug'],
		]);
	}

	public function pageNotFoundAction() {
		$this->indexAction();
		exit;
	}

	public function render($template, $attributes = []) {
		extract($attributes);
		require_once(__DIR__ . '/../templates/header.php');
		require_once(__DIR__ . '/../templates/' .  $template);
		require_once(__DIR__ . '/../templates/footer.php');
	}

	public function renderCustomTemplate($template, $attributes = []) {
		extract($attributes);
		ob_start();
		require_once(__DIR__ . '/../templates/' .  $template);
		return ob_get_clean();
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

	public function checkAccess($class, $method) {
		$hasAccess = true;
		$accessKey = $class . '::' . $method;
		if(isset($this->app->config['access'][$accessKey])) {
			$hasAccess = in_array($this->app->getCurrentUser()->get('role'), $this->app->config['access'][$accessKey]);
		}

		if(!$hasAccess) {
			if($this->isPost()) {
				$this->jsonResponse(['success' => false, 'message' => 'Доступ запрещен']);
				exit;
			} else {
				$this->pageNotFoundAction();
			}
		}
	}

	public function isPost() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
}