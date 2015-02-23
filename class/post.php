<?php
class POST {
	var $post;

	public function __construct( $post ) {
		$this->post = $post;
		
		$this->getCommentCount();
		$this->getAuthor();
		$this->getCategories();
		$this->getHtml();
		$this->getCustomFields();
		$this->getMediaAttachments();

		return $this->post;
	}

	private function getPostURI() {
		$this->post->post_uri = '/' . get_page_uri( $this->post->ID );
	}

	private function getCommentCount() {
		$this->post->comment_count = wp_count_comments( $this->post->ID );
	}

	private function getAuthor() {
		$author = get_userdata( $this->post->post_author );
		$this->post->post_author = $author->data;
		unset($this->post->post_author->user_pass);
		unset($this->post->post_author->user_activation_key);
	}

	private function getCategories() {

		$cat_ids = wp_get_post_categories( $this->post->ID );
		$this->post->post_categories = array();

		foreach ( $cat_ids as $id ) {

			$cat = get_category( $id );
			$cat_url = get_category_link( $id );

			$blog_url = $this->getBlogInfo( 'url' );
			$blog_url = $blog_url[ 'url' ];

			$cat->blog_url = $cat_url;
			$cat->clean_uri = str_replace( $blog_url, '', $cat_url );

			$this->post->post_categories[] = $cat;
		}
	}

	private function getHtml() {
		$content = $this->post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);

		$this->post->post_html 	= $content;
	}

	private function getCustomFields() {
		$custom_fields_raw = get_post_custom( $this->post->ID );
		$custom_fields_filtered = array();

		if ( ! $custom_fields_raw ) 	return $post;

		foreach ( $custom_fields_raw as $key => $custom_field ) {
			if ( substr( $key, 0, 1 ) === '_' ) 	continue;
			if ( count( $custom_field ) === 1 ) 	$custom_field = $custom_field[ 0 ];
			$custom_fields_filtered[ $key ] 		= $custom_field;
		}

		$this->post->custom_fields 	= array();
		$this->post->custom_fields 	= $custom_fields_filtered;
	}

	private function getMediaAttachments() {
		$params 	= array(
			'orderby' 		=> 'menu_order',
			'order' 		=> 'ASC',
			'nopaging' 		=> true,
			'post_type' 	=> 'attachment',
			'post_parent' 	=> $this->post->ID
		);
		$attachments_raw 		= get_posts( $params );
		$attachments_filtered 	= array();

		foreach ( $attachments_raw as $key => $attachment ) {
			$media 		= ( object ) array(
				'description' 	=> $attachment->post_content,
				'title' 		=> $attachment->post_title,
				'caption' 		=> $attachment->post_excerpt,
				'mime_type' 	=> $attachment->post_mime_type
			);

			if ( wp_attachment_is_image( $attachment->ID ) ) {
				$media->images 		= new stdClass();
				$sizes 				= get_intermediate_image_sizes();
				$sizes[] 			= 'full';
				foreach ( $sizes as $size ) {
					$img 			= wp_get_attachment_image_src( $attachment->ID, $size );
					$media->images->{ $size } 			= new stdClass();
					$media->images->{ $size }->url 		= $img[ 0 ];
					$media->images->{ $size }->width 	= $img[ 1 ];
					$media->images->{ $size }->height 	= $img[ 2 ];
				}
			} else {
				$media->url 		= wp_get_attachment_url( $attachment->ID );
			}

			$attachments_filtered[] = $media;
		}

		if ( count( $attachments_filtered ) === 1 )  	$attachments_filtered = $attachments_filtered[ 0 ];

		$this->post->media_attachments 	= new stdClass();
		$this->post->media_attachments 	= $attachments_filtered;
	}

	private function getBlogInfo( $info_request ) {
		$blog_info 	= array();

		if ( strlen( $info_request ) ) {
			$requests 	= explode( ';', $info_request );
			foreach ( $requests as $request ) {
				$blog_info[ $request ] 	= get_bloginfo( $request );
			}
		}

		return $blog_info;
	}

}