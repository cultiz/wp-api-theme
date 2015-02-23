<?php

updateQueryPosts();
$json = array();

while ( have_posts() ) {
	the_post();
	$json[] = new POST( $post );
}

header( 'Content-type: application/json' );
echo json_encode( $json );