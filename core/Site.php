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
	private	static $_site = null;
	private	$_router;
	private	$_namespace;
	private $_config;
	private	$_mainDB;
	private	$_siteDB;
	private	$_cssFiles;
	private	$_cssURLs;
	private	$_jsFiles;
	private	$_jsURLs;

	function __construct(Router $router, String $namespace) {
		if (isset(self::$_site))
			throw new \Exception("Site error: site '".\Site::getSite().getNamespace()."' not found !");
		$conf = ROOT."sites/{$namespace}/config.php";
		if (!file_exists($conf))
			throw new \Exception("Site error: site '{$amespace}' not found !");
		$this->_router = $router;
		$this->_namespace = $namespace;
		$this->_config = require $conf;
		$this->_mainDB = null;
		$this->_siteDB = null;
		if (file_exists(ROOT.'database.php')) {
			$conf = require ROOT.'database.php';
			$this->_mainDB = new Database($conf['db_name'], $conf['db_user'], $conf['db_passwd'], $conf['db_host']);
		}
		if (file_exists(ROOT."sites/{$this->_namespace}/database.php")) {
			$conf = require ROOT."sites/{$this->_namespace}/database.php";
			$this->_siteDB = new Database($conf['db_name'], $conf['db_user'], $conf['db_passwd'], $conf['db_host']);
		}
		$this->_cssFiles = [];
		$this->_cssURLs = [];
		$this->_jsFiles = [];
		$this->_jsURLs = [];
		if (!$this->getErrorState()) {
			ini_set('display_errors', 0);
			ini_set('display_startup_errors', 0);
			ini_set('error_reporting', 0);
		}
		self::$_site = $this;
	}

	static function getSite() {
		if (!isset(self::$_site))
			throw new \Exception("Site error: site unset !");
		return self::$_site;
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

	function getGtmId() {
		return (isset($this->_config['gtm_id'])) ? $this->_config['gtm_id'] : '';
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

	function getDb() {
		return $this->_mainDB;
	}

	function getSiteDb() {
		return $this->_siteDB;
	}

	function getTableByModel(String $modelname, String $tablename = null) {
		return (new Table($this->getDb(), "\\Models\\{$modelname}", $tablename));
	}

	function getTableBySiteModel(String $modelname, String $tablename = null) {
		return (new Table($this->getSiteDb(), "\\Sites\\{$this->getNamespace()}\\models\\{$modelname}", $tablename));
	}

	function callController(String $name, String $action, Array $argv = []) {
		$controller = "\\Sites\\{$this->getNamespace()}\\controllers\\{$name}Controller";
		if (!($methods = get_class_methods($controller)))
			throw new \Exception("Site error: controller '{$controller}' not found !");
		$action = "{$action}Action";
		if (!in_array($action, $methods))
			throw new \Exception("Site error: '{$controller}->{$action}' not found !");
		return (new $controller($this))->$action($argv);
	}

	function addCssFile(String $file) {
		$file = "/public/css/{$file}.css";
		if (!file_exists(ROOT.$file))
			throw new \Exception("Site error: file '{$file}' not found !");
		if (!in_array($file, $this->_cssFiles))
			$this->_cssFiles[] = $file;
	}

	function getCssFiles() {
		return $this->_cssFiles;
	}

	function addCssURL(String $url) {
		if (!in_array($url, $this->_cssURLs))
			$this->_cssURLs[] = $url;
	}

	function getCssURLs() {
		return $this->_cssURLs;
	}

	function addJsFile(String $file) {
		$file = "/public/js/{$file}.js";
		if (!file_exists(ROOT.$file))
			throw new \Exception("Site error: file '{$file}' not found !");
		if (!in_array($file, $this->_jsFiles))
			$this->_jsFiles[] = $file;
	}

	function getJsFiles() {
		return $this->_jsFiles;
	}

	function addJsURL(String $url) {
		if (!in_array($url, $this->_jsURLs))
			$this->_jsURLs[] = $url;
	}

	function getJsURLs() {
		return $this->_jsURLs;
	}
}
