<?php
class ROUTER {
	public $params;
	public $custom_routes;

	public function __construct() {
		$this->params = array();
		$this->custom_routes = array();

		$this->post_routes();
		$this->category_routes();
		$this->tag_routes();
		$this->author_routes();
		$this->page_routes();
		$this->search_routes();

		// blocked before

		print_r($this->custom_routes);

		global $wp_rewrite;
		$wp_rewrite->rules = $this->custom_routes + $wp_rewrite->rules;
		$wp_rewrite->flush_rules();

		if ( !empty( $_GET ) ) query_posts( $_GET );
	}

	private function post_routes() {
		$this->custom_routes['posts/?$'] = 'index.php';
		$this->custom_routes['posts/([0-9]+)/?$'] = 'index.php?p='.$wp_rewrite->preg_index(1);
	}

	private function category_routes() {
		$this->custom_routes['categories/([0-9]+)/?$'] = 'index.php?cat='.$wp_rewrite->preg_index(1);
		$this->custom_routes['categories/(.+?)/?$'] = 'index.php?category_name='.$wp_rewrite->preg_index(1);
	}

	private function tag_routes() {
		$this->custom_routes['tags/([0-9]+)/?$'] = 'index.php?tag_id='.$wp_rewrite->preg_index(1);
		$this->custom_routes['tags/([^/]+)/?$'] = 'index.php?tag='.$wp_rewrite->preg_index(1);
	}

	private function author_routes() {
		$this->custom_routes['authors/([0-9]+)/?$'] = 'index.php?author='.$wp_rewrite->preg_index(1);
		$this->custom_routes['authors/(.+)/?$'] = 'index.php?author_name='.$wp_rewrite->preg_index(1);
	}

	private function page_routes() {
		$this->custom_routes['pages/([0-9]+)/?$'] = 'index.php?page_id='.$wp_rewrite->preg_index(1);
		$this->custom_routes['pages/(.+)/?$'] = 'index.php?pagename='.$wp_rewrite->preg_index(1);
	}

	private function search_routes() {
		$this->custom_routes['search/(.+)/?$'] = 'index.php?s='.$wp_rewrite->preg_index(1);
	}

	private function protect_var ( $content ) {
	    if ( is_numeric( $content ) )
	        return preg_replace("@([^0-9])@Ui", "", $content);
	    else if ( is_bool( $content ) )
	        return ($content?true:false);
	    else if (is_float($content))
	        return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
	    else if (is_string($content)) {
	        if (filter_var ($content, FILTER_VALIDATE_URL))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_EMAIL))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_IP))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_FLOAT))
	            return $content;
	        else
	            return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
	    } else false;
	}

	public function get_routes() {
		global $wp_rewrite;  
		return $wp_rewrite->rules;
	}
}