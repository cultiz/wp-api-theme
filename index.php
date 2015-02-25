<?php 

//init_theme();
update_wp_query();

$json = new JSON();

while ( have_posts() ) : the_post();
	$post = new POST( $post );
	$json->data[] = $post->get_post();
endwhile;

header( 'Content-type: application/json' );
echo json_encode( $json );