<?php

namespace AudioteriaWP\Api;

use AudioteriaWP\Data\AbstractProducts;

class Search {

	public function __construct()
	{
		register_rest_route( 'audioteria-wp/v1', '/search/', array(
			'methods' => 'POST',
			'callback' => [$this, 'search_results'],
			'permission_callback' => '__return_true'
		));
        
        register_rest_route( 'audioteria-wp/v1', '/search/', array(
            'methods' => 'GET',
            'callback' => [$this, 'search_products'],
            'permission_callback' => '__return_true'
        ));
	}
	
	public function search_products(\WP_REST_Request $request) {
        $data_results = [];
	    
	    $posts_per_page = $request['posts_per_page'] ?? 5;
        
        $product_terms = get_terms(['taxonomy' => AbstractProducts::get_instance()->taxonomy]);
        
        
        $message = __( 'No Products found', 'audioteria-wp' );
        
        if(!empty($product_terms)) {
            foreach ($product_terms as $term) {
                $args = [
                    'post_type' => Product::get_instance()->post_type,
                    'numberposts'   => $posts_per_page,
                    'tax_query'           => [
                        [
                            'taxonomy' => Product::get_instance()->taxonomy,
                            'terms'    => $term->term_id,
                        ]
                    ],
                ];
                
                $get_products = get_posts($args);
                if(!empty($get_products)) {
                    $return_products = [
                        'id'                => $term->term_id,
                        'name'              => $term->name,
                        'slug'              => $term->slug,
                        'description'       => $term->description,
                        'show_in_homepage'  => (bool) get_field('show_in_homepage', 'term_'.$term->term_id)
                    ];
                    
                    $message = __( 'Products found', 'audioteria-wp' );
                    
                    
                    foreach($get_products as $get_product) {
                        $product = wc_get_product($get_product->ID);
                        $return_products['products'][] =  Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre);
                    }
                    
                    array_push($data_results, $return_products);
                }
                
            }
        }
        
        return api_rest_response( $message, 'audioteria_returned_search_products', $data_results );
    }

	public function search_results(\WP_REST_Request $request) {
		$request = $request->get_body();
		
		$decoded_request = json_decode($request);
		
		$search = isset($decoded_request->s) ? sanitize_text_field($decoded_request->s) : '';
		$genres = $decoded_request->genres ?? [];
		$categories = $decoded_request->categories ?? [];
		$ratings = $decoded_request->ratings ?? [];
		$per_page = $decoded_request->per_page ?? get_option( 'posts_per_page' );
		$page = $decoded_request->page ?? 1;
        $order = $decoded_request->order_by ?? 'default'; // a-z, z-a, latest, popular


		$args = [
			'post_type'         => [Product::get_instance()->post_type],
			'posts_per_page'    => (int) $per_page,
			'paged'             => (int) $page,
			'tax_query'	        =>	[],
			'meta_query'        => [],
			's' 		            => $search,
		];
		
		if(!empty($genres)) {
				array_push($args['tax_query'], [
						'taxonomy'  => Product::get_instance()->genre,
						'field'     => 'slug',
						'terms'     => (array) $genres
				]);
		}
		
		if(!empty($categories)) {
				array_push($args['tax_query'], [
						'taxonomy'  => Product::get_instance()->taxonomy,
						'field'     => 'slug',
						'terms'     => (array) $categories
				]);
		}
		
		if(count($args['tax_query']) > 1) $args['tax_query']['relation'] = 'AND';
		
		if(!empty($ratings)) {
				$temp_results = new \WP_Query($args);

				$reviews = get_comments( array(
						'status'      => 'approve',
						'post_type'   => Product::get_instance()->post_type,
						'meta_query'  => [
								[
										'key'     => 'rating',
										'value'   => $ratings,
								]
						],
				) );
				$post_ids = wp_list_pluck($reviews, 'comment_post_ID');
				if($temp_results->have_posts()) {
						$temp_post_ids = wp_list_pluck($temp_results->posts, 'ID');
						$post_ids = array_merge($post_ids, $temp_post_ids);
				}
				$args['post__in'] = array_unique($post_ids);
		}
        
        switch ($order) {
            case 'a-z':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'z-a':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'popular':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            default:
                $args['orderby'] = 'menu_order title';
                $args['order'] = 'DESC';
        }
		
		$results = new \WP_Query($args);

		if( empty($results->posts) ){
		    $message = __('No search result found', 'audioteria-wp');
		    return api_rest_response($message, 'audioteria_no_search_results', null);
		}

		$max_pages = $results->max_num_pages;
		$total = $results->found_posts;

		$data_results['data'] = [];

		$results = $results->posts;

		foreach ($results as $result) {
				$id = $result->ID;
				$product = wc_get_product($id);
				$buy_url = get_permalink($id);

				$data_results['data'][] = Product::product_returned_schema($product, Product::get_instance()->taxonomy, Product::get_instance()->genre, ['buy_url' =>  $buy_url]);
		}
		
		$data_results['total_items'] = $total;
		$data_results['number_of_pages'] = $max_pages;
		
		$message = __('Search results found', 'audioteria-wp');
		return api_rest_response( $message, 'audioteria_search_results_found', $data_results );
	}
	
	/**
	 * @return Search
	 */
	public static function get_instance() {
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}
}