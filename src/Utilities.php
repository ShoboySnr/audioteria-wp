<?php

namespace AudioteriaWP\src;

class Utilities {


    /**
     * @param $post_id
     * @param $category_type
     * @return array|mixed
     */
    public function return_category($post_id, $category_type, $first_item = true) {
        $return_cat = [];
        $categories = get_the_terms($post_id, $category_type);

        if($categories && ! empty($categories)) {
            foreach($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => html_entity_decode($category->name),
                    'slug' => $category->slug,
                    'link' => get_term_link($category->term_id),
                ];
            }
        } else return [];

        if ($first_item) {
            return $return_cat[0];
        } else return $return_cat;
    }
    

    //get the lists of terms
    public function get_terms_of_posts($taxonomy = 'category')
    {
        $return_cat = [];
        $args = [
            'taxonomy' => $taxonomy,
            'include_parent' => 0,
            'hide_empty' => true,
        ];

        $categories = get_terms($args);

        if (is_wp_error($categories)) {
            return [];
        }

        if (!empty($categories)) {
            foreach ($categories as $category) {
                $return_cat[] = [
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'slug' => $category->slug,
                    'link' => get_term_link( $category->term_id ),
                ];
            }
        }

        return $return_cat;
    }


    /**
     * @param $post_type
     * @return string
     */
    public function getTaxonomiesList($post_type) {
        $return_data = [];
        $taxonomies = get_object_taxonomies($post_type, 'object');
        unset($taxonomies['nav_menu'], $taxonomies['post_tag'], $taxonomies['link_category'], $taxonomies['post_format']);

        foreach($taxonomies as $key => $value) {
            $return_data[$key]['slug'] = $value->name;
            $return_data[$key]['name'] = $value->label;
        }

        return $return_data;
    }
    

    public function getCustomCategories($categories = [])
    {
        $return_data = [];

        $args = [
            'taxonomy'      => $categories,
            'hide_empty'    => true,
            'parent' => 0
        ];

        if(empty($categories)) {
            return [];
        }

        $terms = get_terms($args);

        if(!empty($terms)) {
            foreach ($terms as $key => $value) {
                if(isset($value->term_id)) {
                    $return_data[$key]['id'] = $value->term_id;
                    $return_data[$key]['slug'] = $value->slug;
                    $return_data[$key]['name'] = $value->name;
                    $return_data[$key]['category'] = $value->taxonomy;
                    $return_data[$key]['link'] = get_category_link($value->term_id);
                }
            }
        } else return $return_data;

        return $return_data;
    }


    public static function getTaxonomiesAttachedToPostType($post_type = 'post') {
        $return_data = [];
        $taxonomies = get_object_taxonomies($post_type, 'object');
        unset($taxonomies['nav_menu'], $taxonomies['post_tag'], $taxonomies['link_category'], $taxonomies['post_format'], $taxonomies['countries_categories']);

        foreach($taxonomies as $key => $value) {
            $return_data['slug'] = $value->name;
            $return_data['name'] = $value->label;
            $return_data['post_type'] = $post_type;
        }

        return $return_data;
    }


    /**
     * Singleton poop.
     *
     * @return Utilities|null
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