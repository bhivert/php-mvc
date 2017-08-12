<?php

namespace Core;

class Controller {
	protected	$site;
	protected	$render;

	function __construct(\Core\Site $site) {
		$this->site = $site;
		$this->render = new \Core\Render($this->site);
	}
}
