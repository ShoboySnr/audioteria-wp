<?php

namespace AudioteriaWP\Api;

class Pages {
    
    public function __construct() {
        register_rest_route( 'audioteria-wp/v1', '/pages/(?P<slug>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_static_page'],
            'permission_callback' => '__return_true',
        
        ));
    }
    
    public function get_static_page($request) {
        $data_results = [];
        $page_slug = sanitize_text_field($request->get_param('slug'));
        
        $page = get_page_by_path($page_slug);
        
        if(!empty($page) ) {
            $data_results = self::pages_response_schema($page);
            
            $message = __('Page found', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_page_found', $data_results);
        } else {
            $message = __('Page does not exits', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_page_does_not_exits', $data_results, 404, false);
        }
    }
    
    
    public static function pages_response_schema($page) {
        return  [
            'id'                => $page->ID,
            'slug'              => $page->post_name,
            'title'             => apply_filters('the_title', $page->post_title),
            'content'           => apply_filters('the_content', $page->post_content),
        ];
    }
    
    /**
	 * @return Pages
	 */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
    
}