<?php

namespace AudioteriaWP\Pages;


class AboutPage {


    public function __construct()
    {
          add_action('audioteria_wp_before_close_header', [$this, 'add_about_banner']);
    }


    public function add_about_banner()
    {
        if(is_page_template('page-about.php')) {
            $title = get_the_title();
            $about_headerbg = get_the_post_thumbnail_url(get_the_ID());
            ob_start();
    ?>
          <img class="about-heading" src="<?= $about_headerbg ?: AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/about_heading_bg.png'; ?>" alt="<?= $title ?>" />
    <?php
            echo ob_get_clean();
        }
    }
    

    /**
     * @return Aboutpage
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