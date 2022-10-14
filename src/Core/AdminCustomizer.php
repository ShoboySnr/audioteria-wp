<?php

namespace AudioteriaWP\Core;

use AudioteriaWP\Data\AbstractProducts as Product;
use WP_Error;

class AdminCustomizer {
    public function __construct() {
        add_action( 'customize_register', [$this, 'register']);
        add_action( 'customize_preview_init', [$this, 'audioteria_wp_theme_customize_preview_js']);
        add_action( 'save_post', [$this, 'check_product_character_limit'], 20, 3 );
        add_action( 'save_post_product', [$this, 'check_product_character_limit'], 20, 3);
    }

    public function register( $wp_customize ) {
        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
        $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


         if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial(
                'blogname',
                array(
                    'selector'        => '.site-title a',
                    'render_callback' => [$this, 'audioteria_wp_theme_customize_partial_blogname'],
                )
            );
            $wp_customize->selective_refresh->add_partial(
                'blogdescription',
                array(
                    'selector'        => '.site-description',
                    'render_callback' => [$this, 'audioteria_wp_theme_customize_partial_blogdescription'],
                )
            );
        }

        $wp_customize->add_panel('Audioteria_wp', [
            'title' => __('Audioteria Settings', 'audioteria_wp'),
            'description' => '<p> Audioteria Theme Settings</p>',
            'priority' => 10
        ]);

        // Audioteria Logo

        $wp_customize->add_setting('audioteria-wp-payment-logo');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'audioteria-wp-payment-logo-control',
            array(
                'label' => 'Payment Logo',
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-payment-logo',
            )
        ));

        //Audioteria Social Links

         $wp_customize->add_section( 'audioteria-wp-social-section' , array(
            'title'      => __( 'Social Media', 'audioteria-wp' ),
            'priority'   => 50,
            'panel' => 'Audioteria_wp'

        ) );

        $wp_customize->add_setting('audioteria-wp-facebook', [
            'default' => 'https://www.facebook.com/Audioteria',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-facebook-link',
            array(
                'label' => __('Facebook', 'audioteria-wp'),
                'section' => 'audioteria-wp-social-section',
                'settings' => 'audioteria-wp-facebook',
                'type' => 'url',
            )
        ));

        //Twitter
        $wp_customize->add_setting('audioteria-wp-twitter', [
            'default' => 'https://www.twitter.com/audioteria',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-twitter-link',
            array(
                'label' => __('Twitter', 'audioteria-wp'),
                'section' => 'audioteria-wp-social-section',
                'settings' => 'audioteria-wp-twitter',
                'type' => 'url',
            )
        ));

         //Youtube
        $wp_customize->add_setting('audioteria-wp-youtube', [
            'default' => 'https://www.youtube.com/audioteria/',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-youtube-link',
            array(
                'label' => __('Youtube', 'audioteria-wp'),
                'section' => 'audioteria-wp-social-section',
                'settings' => 'audioteria-wp-youtube',
                'type' => 'text',
            )
        ));

         //Instagram
        $wp_customize->add_setting('audioteria-wp-instagram', [
            'default' => 'https://www.instagram.com/audioteria/',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-instagram-link',
            array(
                'label' => __('Instagram', 'audioteria-wp'),
                'section' => 'audioteria-wp-social-section',
                'settings' => 'audioteria-wp-instagram',
                'type' => 'text',
            )
        ));

         //Instagram
        $wp_customize->add_setting('audioteria-wp-cloud', [
            'default' => 'https://www.icloud.com/audioteria/',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-cloud-link',
            array(
                'label' => __('Cloud', 'audioteria-wp'),
                'section' => 'audioteria-wp-social-section',
                'settings' => 'audioteria-wp-cloud',
                'type' => 'text',
            )
        ));

        $wp_customize->add_setting('mobile_logo');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'mobile_logo',
            array(
                'label' => 'Upload Mobile Logo',
                'section' => 'title_tagline',
                'settings' => 'custom_logo_2',
            )
        ));


        //Contact information( get input value)

          $wp_customize->add_setting('audioteria-wp-contact-email', [
            'default' => 'info@example.co.uk',
        ]);
        $wp_customize->add_setting('audioteria-wp-contact-address', [
            'default' => '',
        ]);

         $wp_customize->add_setting('audioteria-wp-company-registration', [
            'default' => '',
        ]);

         $wp_customize->add_setting('audioteria-wp-stayupdated', [
            'default' => '',
        ]);

         $wp_customize->add_setting('audioteria-wp-copyright', [
            'default' => '&copy; 2022 b7 Enterprise Ltd - All rights reserved.',
        ]);
        $wp_customize->add_section( 'audioteria-wp-contact-section' , array(
            'title'      => __( 'Contact Information', 'audioteria-wp' ),
            'priority'   => 30,
            'panel' => 'Audioteria_wp'

        ) );

        $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-email-input',
            array(
                'label' => __('Email Address', 'audioteria-wp'),
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-contact-email',
                'type' => 'text',
            )
        ));

        $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-address',
            array(
                'label' => __('Contact Address', 'audioteria-wp'),
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-contact-address',
                'type' => 'textarea',
            )
        ));

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-input-company-registration',
            array(
                'label' => __('Company Registration', 'audioteria-wp'),
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-company-registration',
                'type' => 'textarea',
            )
        ));

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-stayupdated-input',
            array(
                'label' => __('Stay Updated', 'audioteria-wp'),
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-stayupdated',
                'type' => 'textarea',
            )
        ));

        $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-copyright-input',
            array(
                'label' => __('Copyright', 'audioteria-wp'),
                'section' => 'audioteria-wp-contact-section',
                'settings' => 'audioteria-wp-copyright',
                'type' => 'text',
            )
        ));

       // Newsletter Section

         $wp_customize->add_section( 'audioteria-wp-subscribe-section' , array(
            'title'      => __( 'Newsletter Information', 'audioteria-wp' ),
            'priority'   => 60,
            'panel' => 'Audioteria_wp'

        ) );

        $wp_customize->add_setting('audioteria-wp-mailchimp-shortcode', [
            'default' => '',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-mailchimp-shortcode-control',
            array(
                'label' => __('Mailchimp Form Shortcode ', 'audioteria-wp'),
                'section' => 'audioteria-wp-subscribe-section',
                'settings' => 'audioteria-wp-mailchimp-shortcode',
                'type' => 'text',
            )
        ));

        //Download Now Footer Section
        $wp_customize->add_section( 'audioteria-wp-download-section' , array(
            'title'      => __( 'Download Now', 'audioteria-wp' ),
            'priority'   => 50,
            'panel' => 'Audioteria_wp'

        ) );

        $wp_customize->add_setting('audioteria-wp-download-bg');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'audioteria-wp-download-bg-input',
            array(
                'label' => 'Download Background',
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-download-bg',
            )
        ));


        $wp_customize->add_setting('audioteria-wp-download-content-image');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'audioteria-wp-download-content-image-input',
            array(
                'label' => 'Download Logo Image',
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-download-content-image',
            )
        ));


        $wp_customize->add_setting('audioteria-wp-download-content', [
            'default' => 'Lorem ipsum dolor sit amet, consectetur, Lorem ipsum dolor sit amet, consectetur',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-downloadcontent-input',
            array(
                'label' => __('Download Content', 'audioteria-wp'),
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-download-content',
                'type' => 'textarea',
            )
        ));


         $wp_customize->add_setting('audioteria-wp-google-play-link-img');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'audioteria-wp-google-play-link-img-input',
            array(
                'label' => 'Google Play Image',
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-google-play-link-img',
            )
        ));

        

        $wp_customize->add_setting('audioteria-wp-google-play-link', [
            'default' => 'https://play.google.com/',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-google-play-link-input',
            array(
                'label' => __('Google Play Link', 'audioteria-wp'),
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-google-play-link',
                'type' => 'text',
            )
        ));


        $wp_customize->add_setting('audioteria-wp-apple-store-img');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'audioteria-wp-apple-store-img-input',
            array(
                'label' => 'Apple Store Image',
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-apple-store-img',
            )
        ));
        

        $wp_customize->add_setting('audioteria-wp-apple-store-link', [
            'default' => 'https://www.apple.com/store',
        ]);

         $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'audioteria-wp-apple-store-link-input',
            array(
                'label' => __('Apple Store Link', 'audioteria-wp'),
                'section' => 'audioteria-wp-download-section',
                'settings' => 'audioteria-wp-apple-store-link',
                'type' => 'text',
            )
        ));

    }


    public function audioteria_wp_theme_customize_partial_blogname() {
        bloginfo( 'name' );
    }

    public function audioteria_wp_theme_customize_partial_blogdescription() {
        bloginfo( 'description' );
    }

    public function audioteria_wp_theme_customize_preview_js() {
        wp_enqueue_script( 'audioteria-wp-theme-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), AUDIOTERIA_WP_THEME_VERSION, true );
    }


    public function character_limit_admin_notice__error() {
        $class = 'notice notice-error';
        $message = __( 'Description is longer than 40', 'audioteria-wp' );
     
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
 
    public function check_product_character_limit( $post_id, $post, $update) {

        $post_id = absint( $post_id );

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

        $product_post = Product::get_instance()->post_type;

        // Only set for post_type = product!
        if ( $product_post !== $post->post_type ) {
            return;
        }

        // Check the nonce.
		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) { 
			return;
		}

        // Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
        $post_excerpt = $post->post_excerpt;

        $post_content = $post->post_content;

        //set this to the maximium number of words
        if (isset($product_excerpt) && str_word_count($product_excerpt) > 40 ){
            
            $_POST['post_status'] = 'draft';
            add_action( 'admin_notices', 'character_limit_admin_notice__error' );
        }
    }

    /**
     * Singleton poop.
     *
     * @return Customizer|null
     */
    public static function get_instance() {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }


}