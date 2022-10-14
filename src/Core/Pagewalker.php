<?php

namespace AudioteriaWP\Core;

use Walker_Nav_Menu;

class Pagewalker extends Walker_Nav_Menu
{
    public function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0)
    {

        $class = '';
        if ($current_page == $page->ID) $class = 'active';
        $output .= sprintf(
            '<button class="' . $class . '"><a href="%s" >%s</a></button>',
            get_permalink($page->ID),
            apply_filters('the_title', $page->post_title, $page->ID)
        );
    }
}