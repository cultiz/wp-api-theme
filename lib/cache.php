<?php

class CACHE {

	public $file;
	public $binary;
	private $cache_dir;
	private $expire;

	public function __construct() {
		$this->cache_dir = get_template_directory() . '/cache/';
		$this->expire = time() - 86400;

		$this->file = $this->cache_dir . $this->get_file_name();
	}

	public function exists() {
		return (file_exists($this->file) && filemtime($this->file) > $this->expire);
	}

	public function get() {
	    //$binary = gzfile($this->file); 
		//$this->json = implode($binary);
		$this->binary = file_get_contents($this->file);
		return $this->binary;
	}

	public function set($json) {
		//$this->json = $json;
		//$file = gzopen($this->file, 'w');
	    //gzwrite($file, $this->json); 
		//gzclose($file); 
		$this->binary = gzencode($json);
		$file = fopen($this->file, 'w');
	    fwrite($file, $this->binary); 
		fclose($file); 
	}

	private function flush($file) {

	}

	private function get_file_name() {
		return md5($_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI]) . '.gz';
	}
}