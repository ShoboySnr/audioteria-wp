<?php

namespace AudioteriaWP\Pages;

use AudioteriaWP\Api\Product;
use AudioteriaWP\Data\AbstractProducts;

class SearchPage
{
    public function __construct()
    {
        add_action( 'pre_get_posts', [$this, 'filter_by_search'] );
        add_action( 'wp_enqueue_scripts', [$this, 'search_page_scripts'] );
    }
    
    public function search_page_scripts() {
        if ( is_search() ) {
            wp_enqueue_script('audioteria-wp-search-js', get_template_directory_uri() . '/js/search.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
            wp_enqueue_script('audioteria-wp-frontend-search-js', get_template_directory_uri() . '/audioteria-frontend/public/scripts/search.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
        }
    }
    
    public function filter_by_search($query) {
        if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
            $query->set( 'post_type', AbstractProducts::get_instance()->post_type);
            $tax_query = [];
            if(!empty($_GET['genres'])) {
                $genres = explode(',', $_GET['genres']);
                array_push($tax_query, [
                    [
                        'taxonomy'  => AbstractProducts::get_instance()->genre,
                        'field'     => 'slug',
                        'terms'     => (array) $genres
                    ]
                ]);
            }
    
            if(!empty($_GET['categories'])) {
                $categories = explode(',', $_GET['categories']);
                array_push($tax_query,  [
                    [
                        'taxonomy'  => AbstractProducts::get_instance()->taxonomy,
                        'field'     => 'slug',
                        'terms'     => (array) $categories
                    ]
                ]);
            }
            
            if (!empty($tax_query)) {
                $query->set( 'tax_query', $tax_query);
            }
    
            if(!empty($_GET['order_by'])) {
                $order = $_GET['order_by'];
                switch ($order) {
                    case 'a-z':
                        $query->set( 'orderby', 'title');
                        $query->set( 'order', 'ASC');
                        break;
                    case 'z-a':
                        $query->set( 'orderby', 'title');
                        $query->set( 'order', 'DESC');
                        break;
                    case 'popular':
                        $query->set( 'meta_key', 'total_sales');
                        $query->set( 'orderby', 'meta_value_num');
                        $query->set( 'order', 'DESC');
                        break;
                    default:
                        $query->set( 'orderby', 'menu_order title');
                        $query->set( 'order', 'DESC');
                }
            }
            
            if(!empty($_GET['ratings'])) {
                $ratings = explode(',', $_GET['ratings']);

                $reviews = get_comments( [
                    'status'      => 'approve',
                    'post_type'   => AbstractProducts::get_instance()->post_type,
                    'meta_query'  => [
                        [
                            'key'     => 'rating',
                            'value'   => $ratings,
                        ]
                    ],
                ] );
                $post_ids = wp_list_pluck($reviews, 'comment_post_ID');
                if($query->have_posts()) {
                    $temp_post_ids = wp_list_pluck($query->posts, 'ID');
                    $post_ids = array_merge($post_ids, $temp_post_ids);
                }
    
                $query->set( 'post__in', array_unique($post_ids));
            }
        }
    }
    
    
    /**
     * @return SearchPage
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