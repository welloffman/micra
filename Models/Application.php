<?

namespace Models;

class Application {
	public $baseUrl;
	public $routes;
	public $path;
	public $config;
	public $objectCache = [];
	public $routeKey;

	public function __construct() {
		$this->protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
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
				$controller->checkAccess($class, $method);
				$controller->$method();
				return true;
			} else {
				$controller = new \Controllers\MainController($this);
				$controller->loginAction();
			}
		}

		$controller = new \Controllers\MainController($this);
		$controller->pageNotFoundAction();
		return false;
	}

	public function redirect($path) {
		header('Location: //' . $this->baseUrl . $path);
		exit;
	}

	public function post($name) {
		return @$_POST[$name];
	}

	public function get($name) {
		return @$_GET[$name];
	}

	public function cookie($name) {
		return @$_COOKIE[$name];
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
		$user = $this->getCurrentUser();
		return $user->get('role') && $user->get('role') != 'guest';
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
		return preg_replace('/\/Models$/', '', __DIR__);
	}

	public function getUserWrapper() {
		if(!isset($this->objectCache['userWrapper'])) {
			$this->objectCache['userWrapper'] = new \Wrappers\UserWrapper($this);
		}

		return $this->objectCache['userWrapper'];
	}

	public function getCurrentUser() {
		if(!isset($this->objectCache['currentUser'])) {
			$this->objectCache['currentUser'] = $this->getUserWrapper()->getCurrentUser();
		}

		return $this->objectCache['currentUser'];
	}

	public function getLogger() {
		if(!isset($this->objectCache['monolog'])) {
			$this->checkLogFile();
			$log = new \Monolog\Logger('name');
			$log->pushHandler(new \Monolog\Handler\StreamHandler($this->rootPath() . $this->config['log_path'], \Monolog\Logger::WARNING));
			$this->objectCache['monolog'] = $log;
		}

		return $this->objectCache['monolog'];
	}

	public function makeCssFromLess() {
		$less_name = 'less.less';
		$css_name = 'build.css';
		$source_path = $this->rootPath() . '/less/';
		$dest_path = $this->rootPath() . '/web/css/build/';

		$less_update_time = 0;
		foreach(scandir($source_path) as $less) {
			if(strpos($less, '.less') !== false) {
				$filetime = filemtime( $source_path . $less );
				if($filetime > $less_update_time) {
					$less_update_time = $filetime;
				}
			}
		}

		if( !is_file($dest_path . $css_name) ) {
			$css_update_time = 0;
		} else {
			$css_update_time = filemtime($dest_path . $css_name);
		}

		if($less_update_time > $css_update_time) {
			$command = 'lessc ' . $source_path . $less_name . ' > ' . $dest_path . $css_name;
			exec($command);
		}
	}

	public function checkLogFile() {
		$logFile = $this->rootPath() . $this->config['log_path'];
		if( is_file($logFile) && filesize($logFile) > 10485760) { // 10 мегабайт
			$oldLogFile = $logFile . '1';
			if(is_file($oldLogFile)) {
				unlink($oldLogFile);
			}
			rename($logFile, $oldLogFile);
		}
	}
}