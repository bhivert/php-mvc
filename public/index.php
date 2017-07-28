<?php
/**
 * File: public/index.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 20.07.2017
 * Last Modified Date: 28.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

try {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	ini_set('error_reporting', E_ALL);

	define('ROOT', dirname(__DIR__).'/');

	require ROOT.'core/Autoloader.php';
	\Core\Autoloader::register();

	require ROOT.'sites/Autoloader.php';
	\Sites\Autoloader::register();

	(new \Core\Router())->route($_SERVER['REQUEST_URI']);

} catch (Exception $e) {
	echo "{$e->getMessage()}<br />".PHP_EOL;
}

