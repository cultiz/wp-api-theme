<?php 
require "class/json.php";
require "class/post.php";
require "class/router.php";

//updateQueryPosts();
$json = new JSON();

while ( have_posts() ) : the_post();
	$post = new POST( $post );
	$json->posts[] = $post->getPost();
endwhile;

header( 'Content-type: application/json' );
echo json_encode( $json );