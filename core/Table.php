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
			$this->_table_name = strtolower(end(explode('\\', $modelname))) . 's';
		}
	}

	public function query(String $command, String $condition, $attr = array()) {
		return ($this->_db->prepared_query
			(
				"{$command} FROM {$this->_table_name} WHERE {$condition}",
				$this->_model_name,
				$attr
			));
	}

	public function queryOne(String $command, String $condition, Array $attr = array()) {
		return ($this->_db->prepared_queryOne
			(
				"{$command} FROM {$this->_table_name} WHERE {$condition}",
				$this->_model_name,
				$attr
			));
	}

	public function all() {
		return ($this->_db->prepared_query
			(
				"SELECT * FROM {$this->_table_name}",
				$this->_model_name
			));
	}

	public function findId($id) {
		return ($this->_db->prepared_query
			(
				"SELECT * FROM {$this->_table_name} WHERE id = ?",
				$this->_model_name,
				[$id]
			));
	}

	public function create(Array $fields) {
		$req = '';
		$vars = [];
		foreach ($fields as $k => $v) {
			$req .= (($req === '') ? '' : ' , ') . "{$k}=:{$k}";
			$vars["{$k}"] = $v;
		}
		return (($this->_db->query
			(
				"INSERT INTO {$this->_table_name} SET {$req}",
				$this->_model_name,
				$vars
			) === true) ? $this->_db->lastInsertedId() : false);
	}

	public function updateId($id, Array $fields) {
		$req = '';
		$vars = [];
		foreach ($fields as $k => $v) {
			$req .= (($req === '') ? '' : ' , ') . "{$k}=:{$k}";
			$vars["{$k}"] = $v;
		}
		$vars['id'] = $id;
		return ($this->_db->query
			(
				"UPDATE {$this->_table_name} SET {$req} WHERE id=:id",
				$this->_model_name,
				$vars
			));
	}

	public function deleteId($id) {
		return ($this->_db->query
			(
				"DELETE FROM {$this->_table_name} WHERE id=:id",
				$this->_model_name,
				['id' => $id]
			));
	}
}
