<?php
require "class/json.php";
require "class/post.php";

function update_wp_query() {
	if ( empty( $_GET ) ) return;
	query_posts( $_GET );
}