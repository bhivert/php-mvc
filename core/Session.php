<?php
/**
 * File              : core/Session.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 07.09.2017
 * Last Modified Date: 07.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Session {
	public static function init() {
		session_start();
		if (empty($_SESSION)) {
			$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
		}
	}

	public static function getKey(String $key) {
		if (array_key_exists($key, $_SESSION))
			return $_SESSION[$key];
		return null;
	}
}
