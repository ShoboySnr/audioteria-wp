<?php

namespace AudioteriaWP\Pages;


class AuthenticationPages {
    
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', [$this, 'login_scripts_styles'] );
    }
    
    
    public function login_scripts_styles() {
        wp_enqueue_script('audioteria-wp-login-script', get_template_directory_uri().'/js/login.js', [], AUDIOTERIA_WP_THEME_VERSION, true);
    }
    
    
    /**
     * @return AuthenticationPages
     */
    public static function get_instance()
    {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}
