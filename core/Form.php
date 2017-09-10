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

	public function	__construct(Array $request, Array $items = []) {
		$this->_request = $request;
		$this->_items = $items;
	}

	public	function addItem(Array $id, Array $class) {
		$this["{$id}"] = $class;
	}

	public function	__toString() {
		$content = $this->_items;
		ob_start();
		require ROOT.'core/templates/form.phtml';
		return ob_get_clean();
	}
}
