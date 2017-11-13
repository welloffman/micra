<?

namespace models;

class Application {
	public $baseUrl;
	public $routes;
	public $path;
	public $config;
	public $objectCache = [];
	public $routeKey;

	public function __construct() {
		$this->baseUrl = $_SERVER['SERVER_NAME'];
		$this->path = strtok($_SERVER["REQUEST_URI"],'?');
	}

	public function run() {
		$routeFound = false;
		
		if(isset($this->routes[$this->path])) {
			$routeFound = true;
			@list($class, $method, $access) = explode(':', $this->routes[$this->path]);
			$this->routeKey = $this->path;
		} else {
			foreach($this->routes as $key => $value) {
				if($this->matchRoute($key)) {
					$routeFound = true;
					@list($class, $method, $access) = explode(':', $value);
					$this->routeKey = $key;
				}
			}
		}

		if($routeFound) {
			if($access === '?' || $this->isAuth()) {
				$controller = new $class($this);
				$controller->$method();
				return true;
			} else {
				$controller = new \controllers\MainController($this);
				$controller->loginAction();
			}
		}

		$controller = new \controllers\MainController($this);
		$controller->pageNotFoundAction();
		return false;
	}

	public function login() {
		if($this->post('login') === $this->config['auth']['login'] && $this->post('password') === $this->config['auth']['password']) {
			$_SESSION["token"] = uniqid();
		} else {
			$this->logout();
		}
	}

	public function logout() {
		$_SESSION = [];
	}

	public function redirect($path) {
		header('Location: //' . $this->baseUrl);
		exit;
	}

	public function post($name) {
		return @$_POST[$name];
	}

	public function get($name) {
		return @$_GET[$name];
	}

	public function getJsonRequest() {
		return json_decode(file_get_contents('php://input'), true);
	}

	public function getPathRequest($name) {
		$pathParts = explode('/', $this->path);
		$keyParts = explode('/', $this->routeKey);

		foreach($keyParts as $i => $item) {
			if($item == '<' . $name . '>') {
				return $pathParts[$i];
			}
		}

		return null;
	}

	public function isAuth() {
		return !!@$_SESSION['token'];
	}

	private function matchRoute($pathTemplate) {
		$templateParts = explode('/', $pathTemplate);
		$pathParts = explode('/', $this->path);

		if(count($templateParts) != count($pathParts)) {
			return false;
		}

		foreach(array_keys($templateParts) as $i) {
			if(substr($templateParts[$i], 0, 1) == '<' && substr($templateParts[$i], -1) == '>') {
				continue;
			}

			if($templateParts[$i] != $pathParts[$i]) {
				return false;
			}
		}

		return true;
	}

	public function rootPath() {
		return preg_replace('/\/models$/', '', __DIR__);
	}


	public function getFileWrapper() {
		if(!isset($this->objectCache['fileWrapper'])) {
			$this->objectCache['fileWrapper'] = new \wrappers\FileWrapper($this);
		}

		return $this->objectCache['fileWrapper'];
	}

	public function getCategoryWrapper() {
		if(!isset($this->objectCache['categoryWrapper'])) {
			$this->objectCache['categoryWrapper'] = new \wrappers\CategoryWrapper($this);
		}

		return $this->objectCache['categoryWrapper'];
	}
}