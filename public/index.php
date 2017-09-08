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

	require ROOT.'models/Autoloader.php';
	\Models\Autoloader::register();

	\Core\Session::init();

	if (strpos($_SERVER['REQUEST_URI'], '/public') === 0
		&& $_SERVER['REQUEST_URI'] !== '/public/index.php'
		&& is_file(ROOT.$_SERVER['REQUEST_URI'])
	) {
		$mine_t = [
			'css'	=>	'text/css',
			'js'	=>	'application/x-javascript',
			'png'	=>	'image/png',
			'mp4'	=>	'video/mp4'
		];
		$m = [];
		if (preg_match('/^.*\.(.*)$/', $_SERVER['REQUEST_URI'], $m) === 0
			|| !isset($mine_t[$m[1]]))
			throw new Exception("Index Error: UnAutorized mine type !");
		header("Content-type: ".$mine_t[$m[1]]);
		require ROOT.$_SERVER['REQUEST_URI'];
	} else {
		(new \Core\Router())->route($_SERVER['REQUEST_URI']);
	}

} catch (Exception $e) {
	echo "{$e->getMessage()}<br />".PHP_EOL;
}

