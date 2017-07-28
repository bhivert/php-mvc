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

	function __construct(Router $router, String $namespace) {
		$file = ROOT."sites/{$namespace}/config.php";
		if (!file_exists($file))
			throw new \Exception("Site error: site '{$amespace}' not found !");
		$this->_router = $router;
		$this->_namespace = $namespace;
		$this->_config = require $file;
	}

	function getFolder() {
		return $this->_folder;
	}

	function getErrors() {
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

	function callController(String $name, String $action, Array $argv = []) {
		$controller = "\\Sites\\{$this->_namespace}\\controllers\\{$name}Controller";
		if (!($methods = get_class_methods($controller)))
			throw new \Exception("Site error: controller '{$controller}' not found !");
		$action = "{$action}Action";
		if (!in_array($action, $methods))
			throw new \Exception("Site error: '{$controller}->{$action}' not found !");
		return (new $controller)->$action($this, $argv);
	}

	function render(Array $kwargs) {
		ob_start();
		$site = $this;
		require ROOT."core/templates/head.phtml";
		$head = ob_get_clean();
		ob_start();
		ob_start();
		foreach ($kwargs as $view => $content) {
			$file = ROOT."sites/{$this->_namespace}/views/"
				.str_replace('.', '/', $view).".phtml";
			if (!file_exists($file))
				throw new \Exception("Render error: view: '{$view}' not found !");
			require $file; 
		}
		$content = ob_get_clean();
		require ROOT."core/templates/body.phtml";
		$body = ob_get_clean();
		require ROOT."core/templates/html.phtml";
	}
}
