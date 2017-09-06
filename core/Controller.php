<?php
/**
 * File: core/Controller.php
 * @author Benoit HIVERT <hivert.benoit@gmail.com>
 * Date: 06.09.2017
 * Last Modified Date: 06.09.2017
 * Last Modified By: Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Controller {
	protected	$site;
	protected	$render;

	function __construct(\Core\Site $site) {
		$this->site = $site;
		$this->render = new \Core\Render($this->site);
	}
}
