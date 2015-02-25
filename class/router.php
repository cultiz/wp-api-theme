<?php
class ROUTER {

	public function __construct() {
		add_filter('mod_rewrite_rules', 'generate_custom_routes' );
		//$this->generate_custom_routes();
	}

	private function generate_custom_routes() {
		global $wp_rewrite;
		
		$custom_routes = array(
			'posts/?$' => 'index.php',
			'posts/([0-9]+)/?$' => 'index.php?p='.$wp_rewrite->preg_index(1),
			'categories/([0-9]+)/?$' => 'index.php?cat='.$wp_rewrite->preg_index(1),
			'categories/(.+?)/?$' => 'index.php?category_name='.$wp_rewrite->preg_index(1),
			'tags/([0-9]+)/?$' => 'index.php?tag_id='.$wp_rewrite->preg_index(1),
			'tags/([^/]+)/?$' => 'index.php?tag='.$wp_rewrite->preg_index(1),
			'authors/([0-9]+)/?$' => 'index.php?author='.$wp_rewrite->preg_index(1),
			'authors/(.+)/?$' => 'index.php?author_name='.$wp_rewrite->preg_index(1),
			'pages/([0-9]+)/?$' => 'index.php?page_id='.$wp_rewrite->preg_index(1),
			'pages/(.+)/?$' => 'index.php?pagename='.$wp_rewrite->preg_index(1),
			'search/(.+)/?$' => 'index.php?s='.$wp_rewrite->preg_index(1)
		);

		$wp_rewrite->rules = $custom_routes;
		return $wp_rewrite->rules;
	}
}