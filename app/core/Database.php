<?php

class Database {
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_count = 0,
			$_results;

	private function __construct() {
		try
		{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/dbname') . ';charset=' . Config::get('mysql/charset'), Config::get('mysql/user'), Config::get('mysql/pass'), [
			  PDO::ATTR_EMULATE_PREPARES => false,
			  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
		} catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}

	public static function getDBI() {
		if(!isset(self::$_instance)) {
			self::$_instance = new Database();
		}
		return self::$_instance;
	}
	/**
	 * query database with raw SQL
	 * @method query
	 * @param  string $sql    sql statement
	 * @param  array  $params the array will have values
	 * @return string
	 * $db = Databse::getDBI();
	 * $db->query('SELECT * FROM users WHERE username ? AND password ?', array($username, $password));
	 */
	public function query($sql, $params = []) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			if(count($params)) {
				$setI = 1;
				foreach($params as $set) {
					$this->_query->bindValue($setI, $set);
					$setI++;
				}
			}
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->rowCount();
			} else {
				$this->_error = true;
			}
		}
		return $this;
	}

	public function bind($params, $value, $type = null) {
		if(is_null($type)) {
			switch(true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->_query->bindValue($params, $value, $type);
	}

	public function execute() {
		return $this->_query->execute();
	}

	public function results($set = 'obj') {
		if($set == 'obj' || $set == 'object') {
			$this->execute();
			return $this->_query->fetchAll(PDO::FETCH_OBJ);
		} else if($set == 'arr' || $set == 'array') {
			$this->execute();
			return $this->_query->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	/**
	 * Update function
	 * @method update
	 * @param  string $table
	 * @param  array $idArray ['user_id' => '1']
	 * @param  array $array   ['username' => 'Test']
	 * @return boolean
	 * $db->update('users', ['user_id' => '2'], ['user_username'=>'Ryahn2'])
	 */
	public function update($table, $idArray, $array) {
		$pos = 1;
		$set = '';

		foreach($array as $key => $value) {
			$set .= "{$key} = ?";

			if($pos < count($array)) {
				$set .= ', ';
			}
			$pos++;
		}
		foreach($idArray as $field => $id) {
			$sql ="UPDATE {$table} SET {$set} WHERE {$field} = {$id}";
		}

		if(!$this->query($sql, $array)->error()) {
			return true;
		}
		return false;
	}

	public function delete($table, $array) {
		$set = '';
		$pos = 1;

		foreach($array as $key => $value) {
			$set .= "{$key} = ?";

			if($pos < count($array)) {
				$set .= ', ';
			}
			$pos++;
		}
		$sql = "DELETE FROM {$table} WHERE {$set}";

		if(!$this->query($sql, $array)->error()) {
			return true;
		}
		return false;
	}

	public function insert($table, $array) {
		$set = '';
		$set2 = '';
		$pos = 1;

		foreach($array as $key => $value) {
			$set .= "{$key}";
			$set2 .= "'{$value}'";

			if($pos < count($array)) {
				$set .= ', ';
				$set2 .=', ';
			}
			$pos++;
		}
		$sql = "INSERT INTO {$table} ({$set}) VALUES ({$set2})";
		// Functions::dump($sql);
		if(!$this->query($sql)->error()) {
			$this->_count = $this->rowCount();
			return true;
		}
		return false;
	}

	/**
	 * Select everything $db->select('users');
	 * Select specific rows with no where statement $db->select('users', null, ['user_id','user_username']);
	 * Select everything with where statement $db->select('users',['user_id'=>1,'user_username'=>'Ryahn']);
	 * Select specific rows with where statement $db->select('users',['user_id'=>1,'user_username'=>'Ryahn'],['user_id','user_username']);
	 */
	public function select($table, $where = null, $array = null) {
		$set = '';
		$set2 = '';
		$pos = 1;
		$pos2 = 1;

		if(!is_null($where) && is_null($array)) {
			foreach($where as $select => $value) {
				$set2 .= "{$select} = ?";
				if($pos < count($where)) {
					$set2 .= ' AND ';
				}
				$pos++;
			}
			$sql = "SELECT * FROM {$table} WHERE {$set2}";
			if(!$this->query($sql, $where)->error()) {
				return true;
			}
			return false;
		} else if(!is_null($array) && !is_null($where)) {
			foreach($where as $select => $value) {
				$set2 .= "{$select} = ?";
				if($pos < count($where)) {
					$set2 .= ' AND ';
				}
				$pos++;
			}
			foreach($array as $field) {
				$set .= "{$field}";

				if($pos2 < count($array)) {
					$set .= ', ';
				}
				$pos2++;
			}
			$sql = "SELECT {$set} FROM {$table} WHERE {$set2}";
			if(!$this->query($sql,$where)->error()) {
				return true;
			}
			return false;
		} else if(is_null($where) && !is_null($array)) {
			foreach($array as $field) {
				$set .= "{$field}";

				if($pos < count($array)) {
					$set .= ', ';
				}
				$pos++;
			}
			$sql = "SELECT {$set} FROM {$table}";
			if(!$this->query($sql)->error()) {
				return true;
			}
			return false;
		} else {
			$sql = "SELECT * FROM {$table}";
			if(!$this->query($sql)->error()) {
				return true;
			}
			return false;
		}
	}

	public function single($set = 'obj') {
		if($set == 'obj' || $set == 'object') {
			$this->execute();
			return $this->_query->fetch(PDO::FETCH_OBJ);
		} else if($set == 'arr' || $set == 'array') {
			$this->execute();
			return $this->_query->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function rowCount() {
		return $this->_query->rowCount();
	}

	public function count() {
		return $this->_count;
	}

	public function lastId() {
		return $this->_pdo->lastInsertId();
	}

	public function transStart() {
		return $this->_pdo->beginTransaction();
	}

	public function transEnd(){
		return $this->_pdo->commit();
	}

	public function transClear(){
		return $this->_pdo->rollBack();
	}

	public function error() {
		return $this->_error;
	}
}

?>
