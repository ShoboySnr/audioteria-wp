<?php

namespace AudioteriaWP\Api;

class Init {
    
    public function __construct()
    {
        add_action( 'rest_api_init', [$this, 'init_endpoint']);
        add_action('init', [$this, 'autologin_user_with_token']);
    }
    
    public function init_endpoint() {
        Authentication::get_instance();
        Product::get_instance();
        Pages::get_instance();
        Search::get_instance();
        User::get_instance();
    }
    
    public function autologin_user_with_token() {
        if(isset($_GET['tok'])) {
            $token = encrypt_decrypt_password($_GET['tok'], 'd');
            $response = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token/validate');
            $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Bearer '.$token;
            add_filter('jwt_auth_valid_token_response',function( $response, $user, $token, $payload ){
                if($response['success']) {
                    wp_set_auth_cookie( $payload->data->user->id, true, is_ssl() );
                    wp_redirect(remove_query_arg('tok',false));
                    die();
                }
                return $response;
            },10,4);
            rest_do_request( $response );
        }
    }
    
    
    /**
     * @return Init
     */
    public static function get_instance() {
        static $instance = null;
    
        if (is_null($instance)) {
            $instance = new self();
        }
    
        return $instance;
    }
}