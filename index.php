<?php 

// Update query using $_GET params
update_wp_query();

// Create the JSON object
$json = new JSON();

if (is_404()) {

	$json->set_status_code(404);

} else {

	$json->set_status_code(200);
	$json->blog_info = get_blog_info(array('name', 'description', 'url', 'language'));

	if (is_category()) {

		$category = get_the_category()[0];
		$category->category_link = get_category_link($category->term_id);
		$json->category = $category;

	/*} elseif (is_tag()) {

		$tag = get_tags()[0];
		$json->tag = $tag;
	*/
	} elseif (is_search()) {
		
		$query = get_search_query();
		$json->search_query = $query;

	} elseif (is_author()) {
		
		$author = get_the_author();
		$author = get_user_by('login', $author);
		$author = $author->data;
		unset($author->user_pass);
		unset($author->user_activation_key);
		$json->author = $author;

	}

	if (is_single() || is_page()) {

		$post = new POST(get_post());
		$json->post = $post->get_post();

	} else {

		$json->posts = array();
		
		while (have_posts()) : the_post();
			$post = new POST($post);
			$json->posts[] = $post->get_post();
		endwhile;

	}
}

echo json_encode( $json );