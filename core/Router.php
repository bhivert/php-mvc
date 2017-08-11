<?php
/**
 * File: core/Router.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 20.07.2017
 * Last Modified Date: 28.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Router {
	private	$_routing;

	function __construct($routing = 'routing.php') {
		if (!file_exists(ROOT.$routing))
			throw new \Exception("Routing error: routing.php not found !");
		$this->_routing = require ROOT.$routing;
	}

	function route($url) {
		$matches = [];
		foreach($this->_routing as $name => $route) {
			$ret = preg_match($route['regex'], $url, $matches);
			if ($ret === 1) {
				(new Site($this, $route['site']))->callController($route['controller'], $route['action'], $matches);
				exit ;
			} else if ($ret === false) {
				throw new \Exception("Routing error: route {$name} bad regex !");
			}
		}
		echo (new Site($this, 'main'))->callController('default', 'index');
	}
}

