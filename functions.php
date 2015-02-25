<?php
require "class/json.php";
require "class/post.php";
require "class/router.php";

//add_action('load-themes.php', 'init_theme');

function init_theme() {
	$router = new ROUTER();
}

function update_wp_query() {
	if ( empty( $_GET ) ) return;
	query_posts( $_GET );
}