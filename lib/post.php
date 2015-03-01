<?php

class POST {
	public $post;

	public function __construct($post) {
		$this->post = $post;
		
		if ($this->post->comment_status === "open") $this->post->comment_count = $this->get_comment_count($this->post->ID);
		if (!is_author()) $this->post->post_author = $this->get_author($this->post->post_author);
		if (!is_category()) $this->post->post_categories = $this->get_categories($this->post->ID);
		if (!is_tag()) $this->post->post_tags = $this->get_tags($this->post->ID);
		$this->post->post_html = $this->get_html($this->post->post_content);
		$this->post->custom_fields = $this->get_custom_fields($this->post->ID);
		$this->post->media_attachments = $this->get_media_attachments($this->post->ID);
	}

	public function get_post() {
		return $this->post;
	}

	private function get_tags($post_id) {
		$tags_ids = wp_get_post_terms($post_id);
		$tags = array();

		foreach ($tags_ids as $id) {
			$tag = get_tag($id);
			$tag->tag_link = get_tag_link($id);
			$tags[] = $tag;
		}

		return $tags;
	}

	private function get_comment_count($post_id) {
		return wp_count_comments($post_id);
	}

	private function get_author($author) {
		$author = get_userdata($author);
		$author = $author->data;
		unset($author->user_pass);
		unset($author->user_activation_key);
		return $author;
	}

	private function get_categories($post_id) {
		$cat_ids = wp_get_post_categories($post_id);
		$categories = array();

		foreach ($cat_ids as $id) {
			$cat = get_category($id);
			$cat->category_link = get_category_link($id);
			$categories[] = $cat;
		}

		return $categories;
	}

	private function get_html($content) {
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);

		return $content;
	}

	private function get_custom_fields($post_id) {
		$custom_fields_raw = get_post_custom($post_id);
		$custom_fields_filtered = array();

		if (!$custom_fields_raw) return;

		foreach ($custom_fields_raw as $key => $custom_field) {
			if (substr($key, 0, 1) === '_') continue;
			if (count($custom_field) === 1) $custom_field = $custom_field[0];
			$custom_fields_filtered[$key] = $custom_field;
		}

		return $custom_fields_filtered;
	}

	private function get_media_attachments($post_id) {
		$params = array(
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'nopaging' => true,
			'post_type' => 'attachment',
			'post_parent' => $post_id
		);
		$attachments_raw = get_posts($params);
		$attachments_filtered = array();

		foreach ($attachments_raw as $key => $attachment) {
			$media = ( object ) array(
				'description' => $attachment->post_content,
				'title' => $attachment->post_title,
				'caption' => $attachment->post_excerpt,
				'mime_type' => $attachment->post_mime_type
			);

			if (wp_attachment_is_image($attachment->ID)) {
				$media->images = new stdClass();
				$sizes = get_intermediate_image_sizes();
				$sizes[] = 'full';
				foreach ($sizes as $size) {
					$img = wp_get_attachment_image_src( $attachment->ID, $size );
					$media->images->{ $size } = new stdClass();
					$media->images->{ $size }->url = $img[ 0 ];
					$media->images->{ $size }->width = $img[ 1 ];
					$media->images->{ $size }->height = $img[ 2 ];
				}
			} else {
				$media->url = wp_get_attachment_url( $attachment->ID );
			}

			$attachments_filtered[] = $media;
		}

		if ( count( $attachments_filtered ) === 1 ) $attachments_filtered = $attachments_filtered[0];

		return $attachments_filtered;
	}
}