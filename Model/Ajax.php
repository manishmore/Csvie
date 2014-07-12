<?php
class Banners_Model_Ajax {
	
	public static function formatCallbackItem($banner, $template = 'list_banner') {
		return array(
			'template' => $template,
			'html' => html_entity_decode($banner['html']),
			'width' => $banner['width'],
			'height' => $banner['height']
		);
	}
	
}

?>