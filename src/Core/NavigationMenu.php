<?php

namespace AudioteriaWP\Core;

class NavigationMenu {
    
    public function __construct()
    {
        add_filter('nav_menu_css_class', [$this, 'add_additional_class_on_li'], 1, 3);
        add_filter( 'nav_menu_link_attributes', [$this, 'add_menu_link_class'], 1, 3 );
    }
    
    public function add_additional_class_on_li($classes, $item, $args) {
        if(property_exists($args, 'add_li_class')) {
            $classes[] = $args->add_li_class;
        }
        return $classes;
    }
    
    public function add_menu_link_class($atts, $item, $args) {
        if (property_exists($args, 'add_anchor_class')) {
            $atts['class'] = $args->add_anchor_class;
        }
        return $atts;
    }
    
    
    /**
     * @return NavigationMenu
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}