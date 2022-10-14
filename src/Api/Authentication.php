<?php

namespace AudioteriaWP\Api;

class Authentication {
    
    public function __construct()
    {
        add_filter('jwt_auth_valid_credential_response', [$this, 'modify_valid_credentials'], 10, 2);
        add_filter('jwt_auth_expire', [$this, 'modify_auth_expire'], 10, 2);
        add_filter('audioteria_wp_modify_auth_whitelist', [$this, 'modify_auth_whitelist'], 10, 1);
        
        register_rest_route( 'audioteria-wp/v1', '/token/', array(
            'methods' => 'POST',
            'callback' => [$this, 'generate_token'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/token/validate', array(
            'methods' => 'POST',
            'callback' => [$this, 'validate_token'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/token/refresh', array(
            'methods' => 'POST',
            'callback' => [$this, 'refresh_token'],
            'permission_callback' => '__return_true',
        ));
    }
    
    public function modify_valid_credentials($response, $user) {
        $response['code'] = 'audioteria_auth_valid_credentials';
        $response['data']['tok'] = encrypt_decrypt_password($response['data']['token']);
        unset($response['data']['firstName'], $response['data']['lastName'], $response['data']['displayName']);
        
        return $response;
    }
    
    public function refresh_token($request) {
        if ( isset( $_COOKIE['refresh_token'] ) ) {
            $request = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token' );
            $request->add_header('Content-Type', 'application/json');
    
            return rest_do_request( $request );
        }
        
        $message = __('Refresh token is not set', 'audioteria-wp');
        return api_rest_response($message, 'jwt_refresh_token_not_set', [], 401, false);
    }
    
    public function modify_auth_whitelist($endpoints) {
        return $endpoints;
    }
    
    
    public function modify_auth_expire($expire, $issued_at) {
        return time() + (MINUTE_IN_SECONDS * 3600000);
    }
    
    public function generate_token($request) {
        $username = sanitize_text_field($request['username']);
        $password = $request['password'];
    
        $request = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token' );
        $request->add_header('Content-Type', 'application/json');
        $request->set_body(wp_json_encode(['username' => $username, 'password' => $password]));
        return rest_do_request( $request );
    }
    
    public function validate_token() {
        $request = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token/validate' );
        $request->add_header('Content-Type', 'application/json');
        return rest_do_request( $request );
    }
    
    /**
     * @return self
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}