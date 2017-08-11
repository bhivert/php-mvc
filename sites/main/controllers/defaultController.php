<?php
/**
 * File: sites/main/controllers/defaultController.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 28.07.2017
 * Last Modified Date: 28.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Sites\main\controllers;

class defaultController {
	public function indexAction(\Core\Site $site, Array $argv) {
		return $site->render(['home' => 'hello world !']);
	}
}

