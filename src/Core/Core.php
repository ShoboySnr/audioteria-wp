<?php

namespace AudioteriaWP\Core;

class Core {
    
    public function __construct()
    {
        $this->init();

        add_action('after_setup_theme', [$this, 'remove_admin_bar']);
    }
    
    public function init() {
        AdminCustomizer::get_instance();
        NavigationMenu::get_instance();
        WooComerce::get_instance();
        Search::get_instance();
    }
    
    
    public function remove_admin_bar() {
        if (!is_admin()) {
            add_filter('show_admin_bar', '__return_false');
        }
    }
    /**
     * @return Core
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}