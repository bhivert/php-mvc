<?php
/**
 * File: core/Site.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 20.07.2017
 * Last Modified Date: 28.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Site {
	private	$_router;
	private	$_namespace;
	private $_config;
	private	$_jsFiles;

	function __construct(Router $router, String $namespace) {
		$file = ROOT."sites/{$namespace}/config.php";
		if (!file_exists($file))
			throw new \Exception("Site error: site '{$amespace}' not found !");
		$this->_router = $router;
		$this->_namespace = $namespace;
		$this->_config = require $file;
		$this->_jsFiles = [];
		if (!$this->getErrorState()) {
			ini_set('display_errors', 0);
			ini_set('display_startup_errors', 0);
			ini_set('error_reporting', 0);
		}
	}

	function getFolder() {
		return $this->_folder;
	}

	function getErrorState() {
		if (!isset($this->_config['errors']) || $this->_config['errors'] !== true)
			return false;
		return true;
	}

	function getHttps() {
		if (!isset($this->_config['https']) || $this->_config['https'] !== true)
			return false;
		return true;
	}

	function getCharset() {
		if (!isset($this->_config['charset']))
			return 'utf-8';
		return $this->_config['charset'];
	}

	function getTitle() {
		if (!isset($this->_config['title']))
			throw new \Exception("Config error: key, title not set !");
		return $this->_config['title'];
	}

	function getAuthor() {
		if (!isset($this->_config['author']))
			throw new \Exception("Config error: key, author not set !");
		return $this->_config['author'];
	}

	function getEmail() {
		if (!isset($this->_config['email']))
			throw new \Exception("Config error: key, email not set !");
		return $this->_config['email'];
	}

	function getKeywords() {
		return $this->_config['keywords'];
	}

	function getDescription() {
		return $this->_config['description'];
	}

	function getNamespace() {
		return $this->_namespace;
	}

	function callController(String $name, String $action, Array $argv = []) {
		$controller = "\\Sites\\{$this->_namespace}\\controllers\\{$name}Controller";
		if (!($methods = get_class_methods($controller)))
			throw new \Exception("Site error: controller '{$controller}' not found !");
		$action = "{$action}Action";
		if (!in_array($action, $methods))
			throw new \Exception("Site error: '{$controller}->{$action}' not found !");
		return (new $controller($this))->$action($argv);
	}

	function addJsFile(String $file) {
		$file = "public/js/{$file}.js";
		if (!file_exists(ROOT.$file))
			throw new \Exception("Site error: file '{$file}' not found !");
		if (!in_array($file, $this->_jsFiles))
			$this->_jsFiles[] = $file;
	}

	function getJsFiles() {
		return $this->_jsFiles;
	}
}
