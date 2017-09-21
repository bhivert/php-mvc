<?php
/**
 * File              : core/Database.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 06.09.2017
 * Last Modified Date: 06.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

use \PDO;

class Database {
	private $_db_name;
	private $_db_user;
	private $_db_passwd;
	private $_db_host;
	private $_pdo;

	public function __construct(String $db_name, String $db_user = 'root', String $db_passwd = 'root', String $db_host = 'localhost') {
		$this->_db_name = $db_name;
		$this->_db_user = $db_user;
		$this->_db_passwd = $db_passwd;
		$this->_db_host = $db_host;
		unset ($this->_pdo);
	}

	private function getPdo() {
		if (!isset($_pdo)) {
			$this->_pdo = new PDO('mysql:dbname=' . $this->_db_name . ';host=' . $this->_db_host, $this->_db_user, $this->_db_passwd);
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return ($this->_pdo);
	}

	public function execute(String $statement, String $modelname, Array $attr = array()) {
		$request = $this->getPdo()->prepare($statement);
		return ($request->execute($attr));
	}

	public function fetchAll(String $statement, String $modelname, Array $attr = array()) {
		$request = $this->getPdo()->prepare($statement);
		$request->execute($attr);
		return ($request->fetchAll(PDO::FETCH_CLASS, $modelname));
	}

	public function fetchOne(String $statement, String $modelname, Array $attr = array()) {
		$request = $this->getPdo()->prepare($statement);
		$request->execute($attr);
		$request->setFetchMode(PDO::FETCH_CLASS, $modelname);
		return ($request->fetch());
	}

	public function lastInsertedId() {
		return ($this->getPdo()->lastInsertId());
	}
}
