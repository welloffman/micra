<?

namespace models;

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
			if(property_exists($this, $propName)) {
				$this->$propName = $propValue;
			}
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