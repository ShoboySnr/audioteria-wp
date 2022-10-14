<?php

namespace AudioteriaWP\Pages;

class Faq {
    
    public string $post_type = 'faq';
    
    /**
     * @return array
     */
    public function get_faq()
    {
        $output = [];

        $args = [
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
        ];

        $results = get_posts($args);

        if (!empty($results)) {
            foreach ($results as $result) {
                array_push($output, [
                    'id' => $result->ID,
                    'title' => $result->post_title,
                    'content' => $result->post_content,
                ]);
            }
        }

        return $output;
    }
    
    
    /**
     * @return Faq
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