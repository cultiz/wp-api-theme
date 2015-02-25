<?php 

update_wp_query();

$json = new JSON();

if (is_404()) {
	$json->set_status_code(404);
} else {
	$json->set_status_code(200);
	while ( have_posts() ) : the_post();
		$post = new POST( $post );
		$json->data[] = $post->get_post();
	endwhile;
}

echo json_encode( $json );