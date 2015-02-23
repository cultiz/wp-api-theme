<?php
require "class/post.php";

/*add_action('init', 'routing');

function routing() {
	add_rewrite_rule(
		'posts/([0-9]+)/?$',
		'index.php?pagename=posts&post_id=$matches[1]',
		'top');
}*/

function updateQueryPosts() {
	if ( empty( $_GET ) ) 	return;
	query_posts( $_GET );
}