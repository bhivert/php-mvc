<?php
/**
 * File              : core/Table.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 06.09.2017
 * Last Modified Date: 06.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Table {
	private $_table_name;
	private $_model_name;
	private $_db;

	public function __construct(Database $db, String $modelname, String $tablename = NULL) {
		$this->_db = $db;
		$this->_model_name = $modelname;
		if (isset($tablename)) {
			$this->_table_name = strtolower($tablename);
		} else {
			$tmp = explode('\\', $modelname);
			$this->_table_name = strtolower(end($tmp)) . 's';
		}
	}

	public function select(String $fields, String $where, Array $attr = []) {
		return ($this->_db->fetchAll
			(
				"SELECT {$fields} FROM {$this->_table_name} WHERE {$where}",
				$this->_model_name,
				$attr

			));
	}

	public function selectAll(String $fields = '*') {
		return ($this->_db->fetchAll
			(
				"SELECT {$fields} FROM {$this->_table_name}",
				$this->_model_name
			));
	}

	public function insert(Array $attr) {
		$req_cells = $req_values = '';
		foreach ($attr as $k => $v) {
			$req_cells .= (($req_cells === '') ? "{$k}" : ", {$k}");
			$req_values .= (($req_values === '') ? ":{$k}" : ", :{$k}");
		}
		return (($this->_db->execute
			(
				"INSERT INTO {$this->_table_name} ({$req_cells}) VALUES ({$req_values})",
				$this->_model_name,
				$attr
			) === true) ? $this->_db->lastInsertedId() : false);
	}

	public function update(String $set, String $where, Array $attr = []) {
		return ($this->_db->execute
			(
				"UPDATE {$this->_table_name} SET {$req} WHERE {$where}",
				$this->_model_name,
				$attr
			));
	}

	public function delete(String $where, Array $attr = []) {
		return ($this->_db->execute
			(
				"DELETE FROM {$this->_table_name} WHERE {$where}",
				$this->_model_name,
				$attr
			));
	}
}
