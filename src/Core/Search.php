<?php

namespace AudioteriaWP\Core;


class Search {
    
    public function __construct()
    {
        add_action('wp_ajax_nopriv_audioteria_search', [$this, 'audioteria_search']);
        add_action('wp_ajax_audioteria_search', [$this, 'audioteria_search']);
    }
    
    
    public function audioteria_search() {
        if(!wp_verify_nonce($_POST['nonce'], 'wp-audioteria-search-form-nonce')) {
            wp_die();
        }
        
        $s = sanitize_text_field($_POST['s']);
    
        $request = new \WP_REST_Request( 'POST', '/audioteria-wp/v1/search' );
        $request->add_header('Content-Type', 'application/json');
        $request->set_body(wp_json_encode(['s' => $s]));
        
        $response = rest_do_request( $request );
        
        echo wp_json_encode($response);
        wp_die();
    }
    
    public function search_templates() {
    
    }
    
    /**
     * Singleton poop.
     *
     * @return Search
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}
