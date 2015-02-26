<?php

class CACHE {

	public $query;
	public $file;
	public $json;
	public $exists;
	private $cache_dir;
	private $expire;

	public function __construct() {
		$this->cache_dir = get_template_directory() . '/cache/';
		$this->expire = time() - 86400;

		$this->query = 'test';
		$this->file = $this->cache_dir . 'abcd.cache';
		$this->exists = (file_exists($this->file) && filemtime($this->file) > $this->expire);
	}

	public function get() {
		if ($this->exists) {
			$this->json = $this->read();
			return $this->json;
		} else {
			return false;
		}
	}

	public function set($json) {
		$this->json = $json;
		$file = fopen($this->file, 'w');

		if ($file) {
		    fwrite($file, $this->json); 
			fclose($file); 
		}
	}

	private function read() {
        return file_get_contents($this->file);
	}

	private function flush($file) {

	}
}