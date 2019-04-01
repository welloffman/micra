<?

namespace Models;

abstract class Model {
	public $app;

	public function __construct($app) {
		$this->app = $app;
	}

	abstract public function getWrapper();

	protected function getHiddenProperties() {
		return ['app'];
	}

	public function setProperties($properties) {
		foreach($properties as $propName => $propValue) {
			$this->set($propName, $propValue);
		}
	}

	public function set($key, $value) {
		if(property_exists($this, $key)) {
			$this->$key = $value;
		}
	}

	public function getProperties() {
		$properties = [];
		$data = get_object_vars($this);
		foreach (array_keys($data) as $key) {
			if (!in_array($key, $this->getHiddenProperties())) {
				$properties[$key] = $this->get($key);
			}
		}
		return $properties;
	}

	public function get($key) {
		$value = null;

		if (!in_array($key, $this->getHiddenProperties())) {
			$value = $this->$key;
		}

		return $value;
	}

	public function save() {
		$this->getWrapper()->save($this);
	}

	public function delete() {
		if($this->get('id')) {
			$this->getWrapper()->delete( $this->get('id') );
		}
	}
}