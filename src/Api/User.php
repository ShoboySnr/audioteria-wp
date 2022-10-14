<?php

namespace AudioteriaWP\Api;


use AudioteriaWP\Data\AbstractProducts;

class User {
    
    public function __construct()
    {
        add_filter('audioteria_customize_login_error_message', [$this, 'change_error_messages'], 10, 2);
        register_rest_route( 'audioteria-wp/v1', '/login', array(
            'methods' => 'POST',
            'callback' => [$this, 'login_user'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/forget-password', array(
            'methods' => 'POST',
            'callback' => [$this, 'forget_password'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/logout', array(
            'methods' => 'GET',
            'callback' => [$this, 'logout_user'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/user', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_user_information'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'audioteria-wp/v1', '/user/purchases', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_user_purchases'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'audioteria-wp/v1', '/user/favourites', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_user_favourites'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'audioteria-wp/v1', '/user/favourites/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => [$this, 'add_to_user_favourite'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'audioteria-wp/v1', '/user/favourites/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => [$this, 'remove_from_user_favourite'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/user/wishlists', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_user_wishlist'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/user/wishlists/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => [$this, 'add_to_user_wishlist'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/user/wishlists/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => [$this, 'remove_from_user_wishlist'],
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route( 'audioteria-wp/v1', '/user/purchases', array(
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_products_purchased' ],
            'permission_callback' => '__return_true',
    
        ) );
    
        register_rest_route( 'audioteria-wp/v1', '/user/ratings/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => [$this, 'rate_product'],
            'permission_callback' => '__return_true',
        ));
    }
    
    
    public function login_user(\WP_REST_Request $requests) {
        $username = sanitize_text_field($requests['username']);
        if(is_email($username)) $username = sanitize_email($requests['username']);
        $password = $requests['password'];
        $remember_me = rest_sanitize_boolean($requests['remember_me']);
        
        if(empty($username) || empty($password)) {
            $message = __('Email and Password required', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_no_email_and_password', null, 401, false);
        }
        
        //check if the email is valid
        if(!$this->check_if_email_exist($username)) {
            $message = __('Sorry this email is not registered', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_email_does_not_exist', null, 401, false);
        }
        
        //check if the username
        if(!$this->check_if_username_exist($username)) {
            $message = __('Sorry, this username is not registered', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_username_does_not_exist', null, 401, false);
        }
        
        $credentials = [
            'user_login'      => $username,
            'user_password'      => $password,
            'remember'      => $remember_me,
        ];
    
        
        $user = wp_signon($credentials, false);
    
        if(is_wp_error($user)) {
            $message = apply_filters('audioteria_customize_login_error_message', $user->get_error_code(), $username);
            return api_rest_response($message, 'audioteria_username_and_password_does_not_exist', null, 401, false);
        }
    
        // generate the token
        $request = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token' );
        $request->add_header('Content-Type', 'application/json');
        $parameters = [
            'username'  => $username,
            'password'  => $password,
        ];
        $request->set_body_params($parameters);
        
        $token_response = rest_do_request( $request );
    
        if( $token_response->data['data'] ) {
            $token = 'Bearer '.$token_response->data['data']['token'];
            $request = new \WP_REST_Request( 'POST', '/jwt-auth/v1/token/validate');
            $request->add_header('Content-Type', 'application/json');
            $_SERVER['HTTP_AUTHORIZATION'] = $token;
            $response = rest_do_request( $request );
        }
        
        
        return $token_response;
    }
    
    public function forget_password($requests) {
        $username = sanitize_text_field($requests['username']);
        if(is_email($username)) $username = sanitize_email($requests['username']);
    
        //check if the email is valid
        if(!$this->check_if_email_exist($username)) {
            $message = __('Sorry this email is not registered', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_email_does_not_exist', [], 401, false);
        }
    
        //check if the username is valid
        if(!$this->check_if_username_exist($username)) {
            $message = __('Sorry, this username is not registered', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_username_does_not_exist', [], 401, false);
        }
        
        //if all is valid
        $user = '';
        if(is_email($username)) {
            $user = get_user_by('email', $username);
        } else {
            $user = get_user_by('username', $username);
        }
        
        if(!empty($user)) {
            $send_email = retrieve_password($user->user_login);
            
            if(is_wp_error($send_email)) {
                $message = __('There was a problem sending your email', 'audioteria-wp');
                return api_rest_response($message, 'audioteria_reset_email_successful', [], 401, false);
            }
    
            $message = __('Reset email sent successfully, please check your inbox', 'audioteria-wp');
            return api_rest_response($message, 'audioteria_reset_email_successful', [], 200, true);
        }
    }
    
    public function logout_user($requests) {
        $user_id = (int) sanitize_text_field($requests['user_id']);
        
        wp_set_current_user($user_id);
        
        //destroy session and cookies
        wp_destroy_current_session();
        wp_clear_auth_cookie();
        wp_set_current_user( 0 );
    
        $message = __('Logout was successful', 'audioteria-wp');
        return api_rest_response($message, 'audioteria_user_logout', [], 200, true);
    }
    
    public function get_user_information() {

        $current_user = wp_get_current_user();

        if(empty($current_user) ){

            $message = __( 'User not found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_user_found', [], 401, false );

        }else {
            $data_results['data'] = [
                'user_id'     =>  $current_user->ID,
                'user_name'     => $current_user->user_login,
                'user_email'     => $current_user->user_email,
                'user_firstname' => $current_user->user_firstname,
                'user_lastname'  => $current_user->user_lastname,
                'display_name'   => $current_user->display_name,
                'user_dob'       => get_user_meta($current_user->ID, '_user_dob', true),
            ];
        

            $message = __('User found', 'audioteria-wp');
            return api_rest_response( $message, 'audioteria_user_found', $data_results );
    
        }
    }

    public function get_user_purchases(\WP_REST_Request $requests) {
        
        $data_results = AbstractProducts::get_instance()->get_user_purchases();
    
        $message = __('User Purchases found', 'audioteria-wp');
        $message_key = 'audioteria_user_purchases_found';
        
        if(empty($data_results)) {
            $message = __('No Purchases found', 'audioteria-wp');
            $message_key = 'audioteria_purchases_not_found';
        }
    
        return api_rest_response( $message, $message_key, $data_results );
    }
    
    public function get_user_favourites() {
        $data_results = [];
    
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        
        $favourite_metadata = get_user_meta($user_id, Product::get_instance()->favourite_key, true);
    
        $favourite_metadata = json_decode($favourite_metadata, true);

        if(!empty($favourite_metadata)) {
            foreach($favourite_metadata as $favourite_id) {
        
                $product = wc_get_product($favourite_id);
                $home_url = home_url();
                $add_to_bag_url = $home_url.'?add-to-cart='.$favourite_id ;
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }
    
            $message = __('User Favourites found', 'audioteria-wp');
        
            return api_rest_response( $message, 'audioteria_user_favourites_found', $data_results );
    
        }else {
    
            $message = __('No products found in Favourites', 'audioteria-wp');
            return api_rest_response( $message, 'audioteria_no_favourites_found', $data_results );
    
        }
    }
    
    public function get_products_purchased() {
        $current_user = wp_get_current_user();
    
        $args = [
            'posts_per_page' => -1,
            'meta_key'    => Product::get_instance()->customer_user,
            'meta_value'  => $current_user->ID,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_is_paid_statuses() ),
        ];
        
        $product_ids = [];
    
        $results = new \WP_Query($args);
        
        $data_results = [];
    
        $results = $results->posts;
        
        if(!empty($results)) {
            foreach ( $results as $result ) {
                $order = wc_get_order($result->ID);
                $items = $order->get_items();
                foreach ($items as $item) {
                    $product_ids[]        = $item->get_product_id();
                }
            }
    
            $product_ids = array_unique($product_ids);
    
            foreach($product_ids as $product_id) {
                $product   = wc_get_product( $product_id );
        
                $data_results[] = Product::product_returned_schema( $product, Product::get_instance()->taxonomy, Product::get_instance()->genre);
            }
    
            $message = __( 'Products found', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_products_found', $data_results );
        }
        
        $message = __( 'No Products Purchased', 'audioteria-wp' );
    
        return api_rest_response( $message, 'audioteria_no_products_purchased', [] );
        
    }
    
    public function rate_product( \WP_REST_Request $requests) {
        $current_user = wp_get_current_user();
    
        $user_id = $current_user->ID;
        
        $product_id = $requests['id'];
        
        $product = wc_get_product($product_id);
        
        if ('product' !== get_post_type($product_id)) {
            $message = __( 'Invalid product', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_invalid_product', [], 401, false );
        }
        
        if(!$this->has_user_purchased($product_id)) {
            $message = __( 'You can\'t rate this product because you have not purchased it.', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_not_purchased_product', [], 401, false );
        }
        
        if(empty($requests['ratings'])) {
            $message = __( 'Enter a rate value', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_no_ratings_found', [], 401, false );
        }
    
        $ratings = sanitize_text_field($requests['ratings']);
        $content = $requests['comments'] ? $requests['comments'] : '';
        
        $name = $current_user->user_lastname . ' '. $current_user->user_firstname;
    
        $params = [
            'comment_post_ID'       => $product_id,
            'comment_author'        => $name,
            'user_id'               => $current_user->ID,
            'comment_author_email'  => $current_user->user_email,
        ];
        
        if(!empty($content)) {
            $params['comment_content'] = $content;
        }
    
        $find_comments = get_comments(['post_id' => $product_id, 'author_email' => $current_user->user_email]);
        $product_review_id = 0;
        if(!empty($find_comments[0])) {
            $params['comment_ID'] = $find_comments[0]->comment_ID;
            $update_comments = wp_update_comment($params);
            if(!is_wp_error($update_comments)) $product_review_id = $params['comment_ID'];
        } else {
            $params['comment_approved'] = 1;
            $product_review_id = wp_insert_comment( $params );
        }
        
        if ( ! $product_review_id ) {
            $message = __( 'There was a problem adding your rating to this product, please try again.', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_invalid_product', [], 401, false );
        }
        
        update_comment_meta( $product_review_id, AbstractProducts::get_instance()->rating_key, $ratings);
        
        $message = __( 'Products rating successful.', 'audioteria-wp' );
        
        $data_results = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy);
        
        return api_rest_response( $message, 'audioteria_product_rated', $data_results );
        
    }
    
    
    public function has_user_purchased($product_id, $user_id = '') {
        if(empty($user_id)) $user_id = get_current_user_id();
        
        $args = [
            'posts_per_page' => -1,
            'meta_key'    => Product::get_instance()->customer_user,
            'meta_value'  => $user_id,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_is_paid_statuses() ),
        ];
    
        $results = new \WP_Query($args);
    
        $results = $results->posts;
    
        if(!empty($results)) {
            foreach ($results as $result) {
                $order = wc_get_order($result->ID);
                $items = $order->get_items();
                foreach ($items as $item) {
                    if((int) $item->get_product_id() === (int) $product_id) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    public function add_to_user_favourite($requests) {

        $data_results = [];

        $current_user = wp_get_current_user();

        $user_id = $current_user->ID;

        $product_id = (int) $requests['id'];
        
        if(empty(wc_get_product($product_id))) {
            $message = __( 'Product does not exist', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_no_product_found', $data_results, 401, false );
        }

        $prev_value = get_user_meta($user_id, Product::get_instance()->favourite_key, true);
        
        $decode_value = json_decode($prev_value, true);
        if(is_null($decode_value)) $decode_value = [];
        
        $new_value = array_unique(array_merge($decode_value, [$product_id]));
        
        $encoded_value = json_encode($new_value);

        if(is_wp_error(update_user_meta($user_id, Product::get_instance()->favourite_key, $encoded_value, $prev_value))){

            $message = __( 'Add to favourite failed', 'audioteria-wp' );

            return api_rest_response( $message, 'audioteria_no_favourite_added', $data_results, 401, false );

        } else {
            $favourite_metadata = get_user_meta($user_id, Product::get_instance()->favourite_key, true);
    
            $favourite_metadata = json_decode($favourite_metadata, true);
    
            foreach($favourite_metadata as $favourite_id) {
        
                $product = wc_get_product($favourite_id);
                $home_url = home_url();
                $add_to_bag_url = add_query_arg(['add-to-cart' => $favourite_id], $home_url);
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }
            
            $message = __('Added to favourite successfully', 'audioteria-wp');

            return api_rest_response( $message, 'audioteria_favourite_added', $data_results );

        }

    }

    public function remove_from_user_favourite($requests) {
        $data_results = [];

        $current_user = wp_get_current_user();

        $user_id = $current_user->ID;

        $product_id = (int) $requests['id'];
    
        if(empty(wc_get_product($product_id))) {
            $message = __( 'Product does not exist', 'audioteria-wp' );
        
            return api_rest_response( $message, 'audioteria_no_product_found', $data_results, 401, false );
        }

        $prev_value = get_user_meta($user_id, Product::get_instance()->favourite_key, true);
        
        $decode_value = json_decode($prev_value, true);
        
        $index = array_search($product_id, $decode_value);
        
        if($index !== false) {
            unset($decode_value[$index]);
            
            update_user_meta($user_id, Product::get_instance()->favourite_key, json_encode($decode_value), $prev_value);
            
            $favourite_metadata = get_user_meta($user_id, Product::get_instance()->favourite_key, true);
    
            $favourite_metadata = json_decode($favourite_metadata, true);
    
            foreach($favourite_metadata as $favourite_id) {
        
                $product = wc_get_product($favourite_id);
                $home_url = home_url();
                $add_to_bag_url = add_query_arg(['add-to-cart' => $favourite_id], $home_url);
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }
    
            $message = __( 'Removed product from favourites successfully', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_favourite_removed', $data_results);
        }
    
        $message = __( 'Product not found in user\'s favouties', 'audioteria-wp' );
    
        return api_rest_response( $message, 'audioteria_product_not_in_favourites', $data_results, 401, false );
    }

    public function get_user_wishlist() {
        $data_results = [];
    
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        
        $wishlist_metadata = get_user_meta($user_id, Product::get_instance()->wishlist_key, true);
    
        $wishlist_metadata = json_decode($wishlist_metadata, true);

        if(!empty($wishlist_metadata)) {
            foreach($wishlist_metadata as $wishlist_id) {
        
                $product = wc_get_product($wishlist_id);
                $home_url = home_url();
                $add_to_bag_url = $home_url.'?add-to-cart='.$wishlist_id ;
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }
    
            $message = __('Products found in wishlists', 'audioteria-wp');
        
            return api_rest_response( $message, 'audioteria_user_wishlist_found', $data_results );
    
        }else {
    
            $message = __('No product in wishlists', 'audioteria-wp');
            return api_rest_response( $message, 'audioteria_wishlist_not_found', $data_results );
    
        }
    }
    
    public function add_to_user_wishlist($requests) {

        $data_results = [];

        $current_user = wp_get_current_user();

        $user_id = $current_user->ID;

        $product_id = (int) $requests['id'];
    
        if(empty(wc_get_product($product_id))) {
            $message = __( 'Product does not exist', 'audioteria-wp' );
        
            return api_rest_response( $message, 'audioteria_no_product_found', $data_results, 401, false );
        }

        $prev_value = get_user_meta($user_id, Product::get_instance()->wishlist_key, true);
    
        $decode_value = json_decode($prev_value, true);
        if(is_null($decode_value)) $decode_value = [];
    
        $new_value = array_unique(array_merge($decode_value, [$product_id]));
    
        $encoded_value = json_encode($new_value);


        if(is_wp_error(update_user_meta($user_id, Product::get_instance()->wishlist_key, $encoded_value, $prev_value))){

            $message = __( 'Add to Wishlist failed', 'audioteria-wp' );

            return api_rest_response( $message, 'audioteria_no_wishlist_added', $data_results, 401, false );

        }else {
            $wishlist_metadata = get_user_meta($user_id, Product::get_instance()->wishlist_key, true);
    
            $wishlist_metadata = json_decode($wishlist_metadata, true);
    
            foreach($wishlist_metadata as $wishlist_id) {
        
                $product = wc_get_product($wishlist_id);
                $home_url = home_url();
                $add_to_bag_url = add_query_arg(['add-to-cart' => $wishlist_id], $home_url);
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }

            $message = __('Added to Wishlist successfully', 'audioteria-wp');

            return api_rest_response( $message, 'audioteria_wishlist_added', $data_results );

        }

    }

    public function remove_from_user_wishlist($requests) {
        $data_results = [];

        $current_user = wp_get_current_user();

        $user_id = $current_user->ID;

        $product_id = (int) $requests['id'];
    
        if(empty(wc_get_product($product_id))) {
            $message = __( 'Product does not exist', 'audioteria-wp' );
        
            return api_rest_response( $message, 'audioteria_no_product_found', $data_results, 401, false );
        }

        $prev_value = get_user_meta($user_id, Product::get_instance()->wishlist_key, true);
    
        $decode_value = json_decode($prev_value, true);
    
        $index = array_search($product_id, $decode_value);
        
    
        if($index !== false) {
            unset($decode_value[$index]);
    
            update_user_meta($user_id, Product::get_instance()->wishlist_key, json_encode($decode_value), $prev_value);
    
            $wishlist_metadata = get_user_meta($user_id, Product::get_instance()->wishlist_key, true);
    
            $wishlist_metadata = json_decode($wishlist_metadata, true);
    
            foreach($wishlist_metadata as $wishlist_id) {
        
                $product = wc_get_product($wishlist_id);
                $home_url = home_url();
                $add_to_bag_url = add_query_arg(['add-to-cart' => $wishlist_id], $home_url);
        
                $data_results[] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['add_to_bag_url' => $add_to_bag_url]);
            }
    
            $message = __('Removed products from wishlist successfully', 'audioteria-wp');
    
            return api_rest_response( $message, 'audioteria_wishlist_item_removed', $data_results );
        } else {
            $message = __( 'Remove from Wishlist failed', 'audioteria-wp' );
    
            return api_rest_response( $message, 'audioteria_wishlist_item_not_removed', $data_results, 401, false );
        }
    }

    public function check_if_email_exist($email) {
        if(is_email($email) && !email_exists($email)) {
            return false;
        }
        
        return true;
    }
    
    public function check_if_username_exist($username) {
        if(!is_email($username) && !username_exists($username)) {
            return false;
        }
        
        return true;
    }
    
    
    public function change_error_messages($error, $username) {
        $pos = strpos($error, 'incorrect_password');
        
        if (is_int($pos)) {
            $error = sprintf('The password you entered for the username %1$s is incorrect', $username);
        }
        return $error;
    }
    
    /**
     * @return User
     */
    public static function get_instance() {
        static $instance = null;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
}