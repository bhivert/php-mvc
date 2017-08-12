<?php

namespace Core;

class Render {
	private	$_site;

	function __construct(\Core\Site $site) {
		$this->_site = $site;
	}

	function template(Array $kwargs) {
		ob_start();
		foreach ($kwargs as $view => $content) {
			$file = ROOT."sites/{$this->_site->getNamespace()}/views/"
				.str_replace('.', '/', $view).".phtml";
			if (!file_exists($file))
				throw new \Exception("Render error: view: '{$view}' not found !");
			require $file; 
		}
		return ob_get_clean();
	}

	function site(Array $kwargs) {
		ob_start();
		$site = $this->_site;
		require ROOT."core/templates/head.phtml";
		$head = ob_get_clean();
		ob_start();
		ob_start();
		foreach ($kwargs as $view => $content) {
			$file = ROOT."sites/{$this->_site->getNamespace()}/views/"
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
