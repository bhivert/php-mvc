<?php
/**
 * File              : core/Forms.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 07.09.2017
 * Last Modified Date: 07.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Form {
	private	$_request;
	private	$_items;
	private	$_model;
	private	$_valid;

	public function	__construct(Array $request, \Core\Model $model, Array $items = []) {
		$this->_request = $request;
		$this->_items = $items;
		$this->_model = $model;
		$this->_valid = [];
	}

	public	function addItem(Array $id, Array $class) {
		$this["{$id}"] = $class;
	}

	public function isPosted() {
		if (!empty($_POST) && isset($_POST['submit']) && empty(array_diff($_POST, $this->_request)))
			return true;
		return false;
	}

	public function isValid(Array $request = null) {
		$valid = true;
		if ($request == null)
			$request = $this->_request;
		else
			$this->_request = $request;
		if (empty($request) || !isset($request['csrf']) || $request['csrf'] !== \Core\Session::getKey('csrf'))
			$valid = false;
		foreach(get_class_methods($this->_model) as $method) {
			if (strncmp($method, 'form_', 5) === 0) {
				$key = substr($method, 5);
				if (!isset($this->_request[$key])) {
					$this->_valid[$key] = 'error'; 
					$valid = false;
				} else if ($this->_model->$method($this->_request[$key]) === false) {
					$this->_valid[$key] = 'warning'; 
					$valid = false;
				} else {
					$this->_valid[$key] = 'success'; 
				}
			}
		}
		return $valid;
	}

	public function	__toString() {
		$items = $this->_items;
		$request = $this->_request;
		$valid = $this->_valid;
		ob_start();
		require ROOT.'core/templates/form.phtml';
		return ob_get_clean();
	}
}
