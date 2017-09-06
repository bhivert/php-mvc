<?php
/**
 * File              : core/Model.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 06.09.2017
 * Last Modified Date: 06.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

use Core;

class Model {
	public function __get($key) {
		$methode = 'get' . ucfirst($key);
		$this->$key = $this->$methode();
		return ($this->$key);
	}
}
