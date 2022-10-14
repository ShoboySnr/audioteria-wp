<?php

namespace AudioteriaWP\Api;

use  AudioteriaWP\Data\AbstractProducts;

class Product extends AbstractProducts {

	public function __construct() {
		register_rest_route( 'audioteria-wp/v1', '/products/', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_products' ],
			'permission_callback' => '__return_true',
			'args'                => $this->get_collection_params(),
		) );

		register_rest_route( 'audioteria-wp/v1', '/home', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_home' ],
			'permission_callback' => '__return_true',
			'args'                => $this->get_collection_params(),
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/(?P<id>\d+)', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_product_by_id' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/categories/', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_all_categories' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/all-categories/', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_all_custom_categories' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/genres', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_all_genres' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/categories/(?P<id>\d+)', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_category_by_id' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/categories/(?P<slug>[a-zA-Z0-9-]+)', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_category_by_slug' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/(?P<id>\d+)/reviews', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_product_reviews' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/(?P<id>\d+)/episodes', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_episodes_by_id' ],
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'audioteria-wp/v1', '/products/featured/', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_featured_products' ],
			'permission_callback' => '__return_true',
			'args'                => $this->get_collection_params(),

		) );

		add_filter( 'audioteria_wp_modify_episodes_fields', [ $this, 'modify_episodes_fields' ], 10, 1 );

	}

	public function get_collection_params() {
		return array(
			'page'     => array(
				'description'       => 'Current page of the collection.',
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
			),
			'per_page' => array(
				'description'       => 'Maximum number of items to be returned in result set.',
				'type'              => 'integer',
				'default'           => get_option( 'posts_per_page' ),
				'sanitize_callback' => 'absint',
			),
		);
	}

	public function get_home() {
		$top_banner = $this->get_home_banner_mobile();

		$premier_release = $this->get_premier_release();

		$categories = $this->get_homepage_category();

		$results = [
			'main_banners'    => $top_banner,
			'premier_release' => $premier_release,
			'categories'      => $categories,
		];

		$message = __( 'Main Page information retrieved', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_main_page_retrieved', $results );
	}

	public function get_products( \WP_REST_Request $request ) {
		$data_results = [];

		$order = $request['order_by']; // a-z, z-a, latest, popular

		$args = array(
			'post_type'      => $this->post_type,
			'posts_per_page' => $request['per_page'],
			'paged'          => $request['page'],
		);

		switch ( $order ) {
			case 'a-z':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'z-a':
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
				break;
			case 'latest':
				$args['orderby'] = 'menu_order title';
				$args['order']   = 'DESC';
				break;
			case 'popular':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			default:
				$args['orderby'] = 'menu_order';
				$args['order']   = 'ASC';
		}

		$results = new \WP_Query( $args );

		if ( empty( $results->posts ) ) {
			$message = __( 'No product found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_product', null );
		}

		$max_pages            = $results->max_num_pages;
		$total                = $results->found_posts;
		$data_results['data'] = [];

		$results = $results->posts;
		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {
				$id        = $result->ID;
				$product   = wc_get_product( $id );
				$main_cast = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $id ) );

				$data_results['data'][] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}

			$data_results['total_items']     = $total;
			$data_results['number_of_pages'] = $max_pages;

			$message = __( 'Products found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_products_found', $data_results );
		}
	}

	public function get_product_by_id( $data ) {
		$data_results = [];
		$params       = sanitize_key( $data->get_param( 'id' ) );
		$product      = wc_get_product( $params );

		if ( empty( $product ) ) {
			$message = __( 'This product can not be found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_product_does_not_exist', null, 404, false );
		}

		$id = $product->get_id();


		$main_cast = $this->product_cast_name_type(get_field( 'main_cast', $id ));

		$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $id ) );

		$data_results['data'] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
			'main_cast' => $main_cast,
			'episodes'  => $episodes
		] );

		$message = __( 'Products found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_product_found', $data_results );
	}

	public function get_all_genres() {
		$data_results = [];

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		);

		$product_tags = get_terms( $this->genre, $cat_args );

		if ( ! is_wp_error( $product_tags ) ) {
			foreach ( $product_tags as $category ) {
				$data_results[] = [
					'id'               => $category->term_id,
					'name'             => $category->name,
					'slug'             => $category->slug,
					'description'      => $category->description,
					'show_in_homepage' => (bool) get_field( 'show_in_homepage', 'term_' . $category->term_id )
				];
			}
		} else {
			$message = __( 'A problem occurred', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_product_genres_found', $data_results, 401, false );
		}

		$message = __( 'Product Genres found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_product_genres_found', $data_results );
	}

	public function get_all_categories() {
		$data_results = [];

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		);

		$product_categories = get_terms( $this->taxonomy, $cat_args );

		if ( ! is_wp_error( $product_categories ) ) {
			foreach ( $product_categories as $category ) {
				$data_results[] = [
					'id'               => $category->term_id,
					'name'             => $category->name,
					'slug'             => $category->slug,
					'description'      => $category->description,
					'show_in_homepage' => (bool) get_field( 'show_in_homepage', 'term_' . $category->term_id )
				];
			}
		} else {
			$message = __( 'A problem occurred', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_product_categories_found', $data_results, 401, false );
		}

		$message = __( 'Product Categories found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_product_categories_found', $data_results );
	}

	public function get_all_custom_categories() {
		$data_results = $this->get_custom_categories();
		$message      = __( 'Product Categories and Genres found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_product_categories_and_genres_found', $data_results );
	}

	public function get_products_by_categories( $category_id, $posts_per_page = 5 ) {
		$data_results = [];

		$args = array(
			'post_type'           => $this->post_type,
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'tax_query'           => array(
				array(
					'taxonomy' => $this->taxonomy,
					'field'    => 'term_id',
					'terms'    => array( $category_id ),
					'operator' => 'IN',
				)
			)
		);

		$loop = new \WP_Query( $args );

		$results = $loop->get_posts();

		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$product        = wc_get_product( $result->ID );
				$id             = $product->get_id();
				$main_cast      = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$episodes       = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $result->ID ) );
				$data_results[] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}
		}

		return $data_results;
	}

	public function get_category_by_slug( $data ) {
		$data_results = [];
		$params       = sanitize_text_field( $data->get_param( 'slug' ) );


		$args = array(
			'post_type'           => $this->post_type,
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => - 1,
			'tax_query'           => array(
				array(
					'taxonomy' => $this->taxonomy,
					'field'    => 'slug',
					'terms'    => array( $params ),
					'operator' => 'IN',
				)
			)
		);

		$loop = new \WP_Query( $args );

		$results = $loop->get_posts();

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {
				$product   = wc_get_product( $result->ID );
				$id        = $product->get_id();
				$main_cast = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $result->ID ) );

				$data_results[] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}

			return $data_results;
		}
	}

	public function get_category_by_id( $data ) {
		$data_results = [];
		$params       = sanitize_key( $data->get_param( 'id' ) );

		$args = array(
			'post_type'           => $this->post_type,
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => - 1,
			'tax_query'           => array(
				array(
					'taxonomy' => $this->taxonomy,
					'field'    => 'term_id',
					'terms'    => array( $params ),
					'operator' => 'IN',
				)
			)
		);

		$loop = new \WP_Query( $args );

		$max_pages            = $loop->max_num_pages;
		$total                = $loop->found_posts;
		$data_results['data'] = [];

		$results = $loop->get_posts();

		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$product   = wc_get_product( $result->ID );
				$id        = $product->get_id();
				$main_cast = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $result->ID ) );

				$data_results['data'][] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}

			$data_results['total_items']     = $total;
			$data_results['number_of_pages'] = $max_pages;

			$message = __( 'Products found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_products_found', $data_results );
		}
	}

	public function get_product_reviews( $params ) {
		$id = absint( sanitize_key( $params->get_param( 'id' ) ) );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		$args = array(
			'post_id' => $id,
			'approve' => 'approve',
		);

		$comments = get_comments( $args );

		if ( empty( $comments ) ) {
			$message = __( 'No Reviews or Ratings for this product at the moment', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_product_reviews', null );
		}

		$reviews = array();

		if ( ! empty( $comments ) ) {

			foreach ( $comments as $comment ) {

				$reviews[] = array(
					'id'             => $comment->comment_ID,
					'created_at'     => $comment->comment_date_gmt,
					'review'         => $comment->comment_content,
					'rating'         => get_comment_meta( $comment->comment_ID, 'rating', true ),
					'reviewer_name'  => $comment->comment_author,
					'reviewer_email' => $comment->comment_author_email,
					'verified'       => wc_review_is_from_verified_owner( $comment->comment_ID ),
				);
			}
		}

		$message = __( 'Reviews and Ratings for this product found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_product_reviews_found', $reviews );
	}

	public function modify_episodes_fields( $episodes ) {
		$episodes_array = [];
		if ( ! empty( $episodes ) ) {
			foreach ( $episodes as $episode ) {
				if ( isset( $episode['episode_number'] ) ) {

					$unformatted_duration = ! empty( $episode['episode_audio_file'] ) ? AbstractProducts::get_instance()->get_episode_audio_duration( $episode['episode_audio_file']['url'] ) : '';
					$unformatted_size     = ! empty( $episode['episode_audio_file'] ) ? (int)  $episode['episode_audio_file']['filesize'] : 0;

					$fields = [
						'number'             => (int) $episode['episode_number'],
						'title'              => $episode['episode_title'],
						'thumbnails'         => [
							'small'  => ! empty( $episode['episode_image']['sizes']['thumbnail'] ) ? $episode['episode_image']['sizes']['thumbnail'] : '',
							'medium' => ! empty( $episode['episode_image']['sizes']['medium'] ) ? $episode['episode_image']['sizes']['medium'] : '',
							'large'  => ! empty( $episode['episode_image']['sizes']['large'] ) ? $episode['episode_image']['sizes']['large'] : '',
						],
						'about'              => strip_tags($episode['episode_about']),
						'episode_audio_file' => !empty( $episode['episode_audio_file'] ) ? $episode['episode_audio_file']['url'] : '',
						'extras'             => strip_tags($episode['episode_extras']),
						'production_credit'  => strip_tags($episode['episode_production_credit']),
						'cast'               => AbstractProducts::get_instance()->episode_cast_image_type( $episode['episode_cast'], 'cast_image' ),
						'duration'           => !empty( $episode['episode_audio_file'] ) ?  AbstractProducts::format_duration( $unformatted_duration ) : '0',
						'duration_secs'     => !empty( $episode['episode_audio_file'] ) ? $unformatted_duration : 0,
						'size'               => !empty( $episode['episode_audio_file'] ) ? AbstractProducts::format_size( $unformatted_size )['text'] : '',
						'size_bytes'         => !empty( $episode['episode_audio_file']['url'] ) ? (int) AbstractProducts::format_size( $unformatted_size )['bytes'] : 0,
						'release_date'       => $episode['episode_release_date'],
					];

					array_push( $episodes_array, $fields );
				}
			}
		}

		return $episodes_array;
	}

	public function single_category_info( $id ) {
		$product_info = [];
		$terms        = get_the_terms( $id, $this->taxonomy );
		foreach ( $terms as $term ) {

			$product_info['name']             = $term->name;
			$product_info['slug']             = $term->slug;
			$product_info['id']               = $term->term_id;
			$product_info['show_in_homepage'] = get_field( 'show_in_homepage', 'term_' . $term->term_id );

			return $product_info;

		}
	}

	public function get_episodes_by_id( $product_id ) {
		$data_results = [];
		$id           = absint( sanitize_key( $product_id->get_param( 'id' ) ) );

		$args = array(
			'post_type'     => $this->post_type,
			'post__in'      => array( $id ),
			'post_per_page' => - 1,
		);

		$results = new \WP_Query( $args );
		$posts   = $results->posts;

		if ( empty( $posts ) ) {
			$message = __( 'No Episodes found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_episode', null );
		}

		$max_pages = $results->max_num_pages;
		$total     = $results->found_posts;

		$data_results['data'] = [];

		foreach ( $posts as $result ) {
			$id       = $result->ID;
			$product  = wc_get_product( $id );
			$episodes = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $id ) );


			$data_results['data'][] = self::espisodes_returned_schema( $product, [
				'episodes' => $episodes
			] );
		}

		$data_results['total_items']     = $total;
		$data_results['number_of_pages'] = $max_pages;

		$message = __( 'Episodes found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_episodes_found', $data_results );
	}

	public function get_featured_products( $request ) {
		$posts_per_page = $request['per_page'] ?? get_option( 'posts_per_page' );
		$paged          = $request['page'];

		$tax_query[] = array(
			'taxonomy' => $this->tax_featured,
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'IN',
		);

		$args = array(
			'post_type'           => $this->post_type,
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'paged'               => $paged,
			'tax_query'           => $tax_query,
		);


		$results = new \WP_Query( $args );

		$max_pages = $results->max_num_pages;
		$total     = $results->found_posts;

		$data_results = [];
		$results      = $results->posts;


		if ( empty( $results ) ) {
			$message = __( 'No product found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_product', null );
		}

		foreach ( $results as $result ) {
			$id        = $result->ID;
			$product   = wc_get_product( $id );
			$main_cast = $this->product_cast_name_type(get_field( 'main_cast', $id ));

			$data_results['data'][] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
				'main_cast' => $main_cast
			] );
		}

		$data_results['total_items']     = $total;
		$data_results['number_of_pages'] = $max_pages;

		$message = __( 'Featured Products found', 'audioteria-wp' );

		return api_rest_response( $message, 'audioteria_products_found', $data_results );
	}

	/**
	 * @return Product
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}