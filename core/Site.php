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
	private	$_coreDB;
	private	$_siteDB;
	private	$_jsFiles;

	function __construct(Router $router, String $namespace) {
		if (isset(self::$_site))
			throw new \Exception("Site error: site '".\Site::getSite().getNamespace()."' not found !");
		$conf = ROOT."sites/{$namespace}/config.php";
		if (!file_exists($conf))
			throw new \Exception("Site error: site '{$amespace}' not found !");
		$this->_router = $router;
		$this->_namespace = $namespace;
		$this->_config = require $conf;
		$this->_coreDB = null;
		$this->_siteDB = null;
		if (file_exists(ROOT.'database.php')) {
			$conf = require ROOT.'database.php';
			$this->_coreDB = new Database($conf['db_name'], $conf['db_user'], $conf['db_passwd'], $conf['db_host']);
		}
		if (file_exists(ROOT."sites/{$this->_namespace}/database.php")) {
			$conf = require ROOT."sites/{$this->_namespace}/database.php";
			$this->_siteDB = new Database($conf['db_name'], $conf['db_user'], $conf['db_passwd'], $conf['db_host']);
		}
		$this->_jsFiles = [];
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

	function getGtmId() {
		return (isset($this->_config['gtm_id'])) ? $this->_config['gtm_id'] : '';
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

	function getDb() {
		return $this->_coreDB;
	}

	function getSiteDb() {
		return $this->_siteDB;
	}

	function getModel(String $modelname, String $tablename = null) {
		return (new Table($this->getDb(), "\\Models\\{$modelname}", $tablename));
	}

	function getSiteModel(String $modelname, String $tablename = null) {
		return (new Table($this->getSiteDb(), "\\Sites\\{$this->namespace}\\Models\\{$modelname}", $tablename));
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
