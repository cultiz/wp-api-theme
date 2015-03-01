<?php
require "lib/json.php";
require "lib/post.php";
//require "lib/cache.php";

function update_wp_query() {
	if (empty($_GET)) return;
	query_posts($_GET);
}

function get_blog_info($requests) {
	$blog_info = array();

	foreach ($requests as $request) {
		$blog_info[$request] = get_bloginfo($request);
	}

	return $blog_info;
}