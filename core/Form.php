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
		$this->_request = [];
		foreach ($request as $key => $value) {
			$this->_request[preg_replace('/[^a-zA-Z0-9]/', '_', $key)] = $value;
		}
		$this->_items = $items;
		$this->_model = $model;
		$this->_valid = [];
	}

	public function isValid() {
		$valid = true;
		if (empty($this->_request) || !isset($this->_request['csrf']) || $this->_request['csrf'] !== \Core\Session::getKey('csrf'))
			$valid = false;
		foreach(get_class_methods($this->_model) as $method) {
			if (strncmp($method, 'x_', 2) === 0) {
				$key = substr($method, 2);
				if (!isset($this->_request[$key])) {
					$this->_valid[$key] = 'error'; 
					$valid = false;
				} else {
					$this->_request[$key] = strip_tags(htmlentities(trim(stripslashes($this->_request[$key])), ENT_QUOTES, \Core\Site::getSite()->getCharset()));
					if ($this->_model->$method($this->_request[$key]) == false) {
						$this->_valid[$key] = 'warning';
						$valid = false;
					} else {
						$this->_valid[$key] = 'success';
					}
				}
			}
		}
		return $valid;
	}

	public function __get($key) {
		if (isset($this->_request[$key]))
			return $this->_request[$key];
		return false;
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
