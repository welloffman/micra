<?

namespace wrappers;

abstract class MysqlWrapper {
	public $app;
	private $pdo;

	abstract public function getName();

	abstract public function getObject();

	public function __construct($app) {
		$this->app = $app;
	}

	public function count($filter = [], $options = []) {
		$sql = "SELECT count(*) as `cnt` FROM `" . $this->getName() . '`';
		$result = $this->query($sql, $filter, $options);
		return (int)$result[0]['cnt'];
	}

	public function find($filter = [], $options = []) {
		$sql = "SELECT * FROM `" . $this->getName() . '`';
		$result = $this->query($sql, $filter, $options);
		return $this->mapToObject($result);
	}

	private function query($sql, $filter = [], $options = []) {
		$filterParts = [];
		foreach(array_keys($filter) as $field) {
			$sign = isset($options[$field]) ? $options[$field] : '=';
			$filterParts[] = "`$field` $sign :$field";
		}

		if(count($filterParts)) {
			$logic = @$options['or'] ? 'OR' : 'AND';
			$sql .= ' WHERE ' . implode(" $logic ", $filterParts);
		}

		if(isset($options['limit'])) {
			$sql .= ' LIMIT ' . (int)$options['limit'];
		}

		if(isset($options['offset'])) {
			$sql .= ' OFFSET ' . (int)$options['offset'];
		}

		if(isset($options['order'])) {
			$sql .= ' ORDER BY `' . $options['order'] . '`';
		}

		if(isset($options['order_desc'])) {
			$sql .= ' ORDER BY `' . $options['order_desc'] . '` DESC';
		}

		return $this->fetch($sql, $filter);
	}

	public function findById($id) {
		$result = $this->find(['id' => (int)$id], ['limit' => 1]);
		return count($result) ? $result[0] : null;
	}

	public function save($object) {
		$properties = $object->getProperties();
		if( isset($properties['id']) ) {
			$this->update($properties);
		} else {
			$id = $this->create($properties);
			$object->setProperties(['id' => $id]);
		}
	}

	public function create($properties) {
		$sql = "INSERT INTO `" . $this->getName() . '`';
		
		$keys = [];
		$values = [];
		foreach(array_keys($properties) as $fieldName) {
			$keys[] = "`$fieldName`";
			$values[] = ":$fieldName";
		}

		$sql .= ' (' . implode(', ', $keys) . ')';
		$sql .= ' VALUES (' . implode(', ', $values) . ')';
		
		$this->fetch($sql, $properties);
		return $this->pdo->lastInsertId();
	}

	public function update($properties) {
		$sql = "UPDATE `" . $this->getName() . '`';
		
		$parts = [];
		foreach(array_keys($properties) as $fieldName) {
			$parts[] = "`$fieldName`=:$fieldName";
		}

		$sql .= ' SET ' . implode(', ', $parts);
		$sql .= ' WHERE `id` = :id';

		$this->fetch($sql, $properties);
	}

	public function delete($id) {
		$sql = "DELETE FROM `" . $this->getName() . '` WHERE `id` = :id';
		$this->fetch($sql, ['id' => $id]);
	}

	public function customQuery($sql, $variables) {
		return $this->fetch($sql, $variables);
	}

	private function mapToObject($rawItems) {
		$result = [];
		foreach($rawItems as $item) {
			$obj = $this->getObject();
			$obj->setProperties($item);
			$result[] = $obj;
		}

		return $result;
	}

	private function fetch($sql, $variables) {
		$sth = $this->getPdo()->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

		$vars = [];
		foreach($variables as $key => $value) {
			$vars[':' . $key] = $value;
		}

		$sth->execute($vars);
		return $sth->fetchAll();
	}

	protected function getPdo() {
		if(!$this->pdo) {
			$dsn = 'mysql:dbname=' . $this->app->config['mysql']['dbname'] . ';host=' . $this->app->config['mysql']['host'];

			try {
			    $this->pdo = new \PDO($dsn, $this->app->config['mysql']['user'], $this->app->config['mysql']['password']);
			} catch (PDOException $e) {
			    echo 'Подключение не удалось: ' . $e->getMessage();
			}
		}

		return $this->pdo;
	}
}