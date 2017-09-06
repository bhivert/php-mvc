<?php
/**
 * File: core/Autoloader.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 20.07.2017
 * Last Modified Date: 20.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Autoloader {
	static function register() {
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	static function autoload($class) {
		if (strpos($class, __NAMESPACE__ . '\\') === 0) {
			$file = __DIR__.'/'.str_replace('\\', '/',
				str_replace(__NAMESPACE__ . '\\', '', $class)).'.php';
			if (!file_exists($file))
				throw new \Exception("Autoload error: class: '{$class}' not found !");
			require $file;
		}
	}
}
