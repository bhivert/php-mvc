<?php
/**
 * File              : core/Mail.php
 * @author           : Benoit HIVERT <hivert.benoit@gmail.com>
 * Date              : 09.09.2017
 * Last Modified Date: 09.09.2017
 * Last Modified By  : Benoit HIVERT <hivert.benoit@gmail.com>
 */

namespace Core;

class Mail {
	private $_to;
	private $_from;
	private $_object;
	private $_message;
	private $_header;

	public function __construct(String $to, String $from, String $object, String $message) {
		$this->_to = $to;
		$this->_from = $from;
		$this->_object = $object;
		$this->_message = $message;
		$this->_header = "From: {$this->_from}\r\n";
		$this->_header .= 'MIME-Version: 1.0'."\r\n";
		$this->_header .= 'Content-type: text/html;'.Site::getSite()->getCharset()."\r\n";
	}

	public function send() {
		mail($this->_to, $this->_object, $this->_message, $this->_header);
	}
}
