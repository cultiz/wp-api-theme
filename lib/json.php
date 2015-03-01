<?php
class JSON {
	
	public function __construct() {
		header( 'Content-type: application/json' );
		//header( 'Content-Encoding: gzip' );
	}

	public function set_status_code($code) {
		switch ($code) {
			case 404:
				header("HTTP/1.1 404 Not Found");
				$this->status_code = 404;
				$this->message = "404 Not Found";
				break;
			
			default:
				header("HTTP/1.1 200 OK");
				$this->status_code = 200;
				$this->message = "200 OK";
		}
	}
}