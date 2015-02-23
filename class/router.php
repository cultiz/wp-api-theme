<?php
class ROUTER {
	var $params = array();

	public function __construct() {
		add_action('init', function(){
			add_rewrite_rule(
				'categories/([0-9]+)/?$',
				'index.php?cat=$matches[1]',
				'top');
		});

		foreach ($_GET as $key => $value) {
			$this->params[$key] = protect_var($value);
		}
	}

	private function protect_var ( $content ) {
	    if ( is_numeric( $content ) )
	        return preg_replace("@([^0-9])@Ui", "", $content);
	    else if ( is_bool( $content ) )
	        return ($content?true:false);
	    else if (is_float($content))
	        return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
	    else if (is_string($content)) {
	        if (filter_var ($content, FILTER_VALIDATE_URL))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_EMAIL))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_IP))
	            return $content;
	        else if (filter_var ($content, FILTER_VALIDATE_FLOAT))
	            return $content;
	        else
	            return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
	    } else false;
	}
}