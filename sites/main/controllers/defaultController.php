<?php
/**
 * File: sites/main/controllers/defaultController.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 28.07.2017
 * Last Modified Date: 28.07.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Sites\main\controllers;

class defaultController extends \Core\Controller {
	public function indexAction(Array $argv) {
		$home = $this->render->template(['home' => 'hello world !']);
		$this->render->site(['home' => $home]);
	}
}

