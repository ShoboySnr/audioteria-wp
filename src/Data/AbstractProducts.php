<?php

namespace AudioteriaWP\Data;

use AudioteriaWP\Api\Product;

class AbstractProducts {

	public string $post_type = "product";
	public string $genre = "genre_cat";
	public string $taxonomy = "product_cat";
	public string $tax_featured = "product_visibility";
	public string $product_tag = "product_tag";
	public string $rating_key = "rating";
	public string $favourite_key = "_custom_favoritelist";
	public string $wishlist_key = "_custom_wishlist";
	public string $customer_user = '_customer_user';
	public string $premiere_releases = 'premier-release';
    public string $banner_post_type = 'banners';
    public string $features_tax = 'features';
    public string $actors_tax = 'actors';
    public string $writers_tax = 'writers';
    public string $narrators_tax = 'narrators';
    public string $directors_tax = 'directors';
    public string $studio_tax = 'studio';

	public static function espisodes_returned_schema( $product, $extras = [] ) {
		$id            = $product->get_id();
		$returned_data = [
			'id'                => $id,
			'name'              => $product->get_name(),
			'slug'              => $product->get_slug(),
			'description'       => $product->get_description(),
			'short_description' => $product->get_short_description(),
			'thumbnails'        => [
				'small'  => get_the_post_thumbnail_url( $id, 'thumbnail' ),
				'medium' => get_the_post_thumbnail_url( $id, 'medium' ),
				'large'  => get_the_post_thumbnail_url( $id, 'large' ),
			],
		];

		return array_merge( $returned_data, $extras );
	}

	public function get_all_products( $limit = '', $paged = 1 ) {
		$data_results = [];

		$args = array(
			'post_type'      => $this->post_type,
			'posts_per_page' => $limit,
			'paged'          => $paged,
		);

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
				$main_cast = get_field( 'main_cast', $id );
				$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $id ) );

				$data_results['data'][] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}

			$data_results['total_items']     = $total;
			$data_results['number_of_pages'] = $max_pages;

			$data_results['message'] = __( 'Products found', 'audioteria-wp' );

			return $data_results;
		}

		$data_results['message'] = __( 'Products Not found', 'audioteria-wp' );

		return $data_results;
	}

	/**
	 * @param $product
	 * @param $taxonomy
	 * @param $genre
	 * @param array $extras
	 *
	 * @return array
	 */
	public static function product_returned_schema( $product, $taxonomy, $genre = 'genre', array $extras = [] ) {
		$id            = $product->get_id();
		$returned_data = [
			'id'                     => (int) $id,
			'name'                   => (string) $product->get_name(),
			'slug'                   => (string) $product->get_slug(),
			'product_genre'          => find_audioteria_categories( $id, $genre ),
			'product_category'       => find_audioteria_categories( $id, $taxonomy ),
			'description'            => strip_tags($product->get_description()),
			'short_description'      => strip_tags($product->get_short_description()),
			'thumbnails'             => [
				'small'  => (string) get_the_post_thumbnail_url( $id, 'thumbnail' ),
				'medium' => (string) get_the_post_thumbnail_url( $id, 'medium' ),
				'large'  => (string) get_the_post_thumbnail_url( $id, 'large' ),
				'full'  =>  (string) get_the_post_thumbnail_url( $id, 'full' ),
			],
			'price'                  => [
				'regular_price' => (string) $product->get_regular_price(),
				'sale_price'    => (string) $product->get_sale_price(),
			],
			'rating'                 => (int) $product->get_average_rating(),
			'rating_count'           => (int) $product->get_review_count(),
			'ratings'                => self::get_instance()->get_ratings( $id ),
			'user_rating'            => (int) self::get_instance()->get_user_rating( $id ),
			'main_extras'            => strip_tags( get_field( 'main_extras', $id ) ),
			'main_trailer'           => (string) get_field( 'trailer', $id ),
			'main_size'              => (string) self::get_instance()->get_total_product_filesize( $id ),
			'main_duration'          => (string) self::get_instance()->get_total_product_duration( $id ),
			'main_featuring'         => self::get_instance()->product_tax_cast_type(get_field( 'main_featuring', $id )),
			'main_written_by'        => self::get_instance()->product_tax_cast_type(get_field( 'main_written_by', $id )),
			'main_directed_by'       => self::get_instance()->product_tax_cast_type(get_field( 'main_directed_by', $id )),
			'main_narrated_by'       => self::get_instance()->product_tax_cast_type(get_field( 'main_narrated_by', $id )),
			'main_studio'            => self::get_instance()->product_tax_cast_type(get_field( 'main_studio', $id )),
			'main_release_date'      => (string) get_field( 'main_release_date', $id ),
			'main_production_credit' => (string) strip_tags( get_field( 'main_production_credit', $id )),
			'product_copyright'      => (string) get_field( 'copyright_text', $id ),
			'view_url'               => (string) get_permalink( $id )
		];

		if ( ! empty( $extras['main_cast'] ) ) {
			$extras['main_cast'] = self::get_instance()->episode_cast_image_type( $extras['main_cast'] );
		}

		return array_merge( $returned_data, $extras );
	}

	public function get_ratings( $product_id ) {
		$reviews = get_approved_comments( $product_id );

		$data = [];

		foreach ( $reviews as $review ) {
			$review_data = array(
				'id'           => (int) $review->comment_ID,
				'comment'      => (string) $review->comment_content,
				'rating'       => (int) get_comment_meta( $review->comment_ID, $this->rating_key, true ),
				'user_id'      => $review->user_id,
				'email'        => (string) $review->comment_author_email,
				'date_created' => wc_rest_prepare_date_response( $review->comment_date_gmt ),
			);

			array_push( $data, $review_data );
		}

		return $data;
	}

	public function get_user_rating( $product_id ) {
		$current_user = wp_get_current_user();
		$user_rating  = 0;
		if ( $current_user ) {

			foreach ( $this->get_ratings( $product_id ) as $rating ) {
				if ( $rating['email'] == $current_user->user_email ) {
					$user_rating = $rating['rating'];
					break;
				}
			}
		}

		return $user_rating;

	}

	public function get_total_product_filesize( $product_id ) {

		$product_episodes = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $product_id ) );

		$total_size = (int) 0;
		if(!empty($product_episodes)){
			foreach ( $product_episodes as $key => $product_episode ) {
				if ( !empty( $product_episode['episode_audio_file']) ) {

					$file_url = $product_episode['episode_audio_file'];

					if(is_singular($this->post_type) || is_front_page() || is_account_page() || is_cart() || is_checkout() || is_archive()){
						$file_url = $product_episode['episode_audio_file']['url'];
					}

					$file_id = attachment_url_to_postid( $file_url);

					// Use the wp_get_attachment_metadata function to get data
					$metadata = wp_get_attachment_metadata( $file_id );

                    if($metadata) {
                        $total_size += $metadata['filesize'];
                    }

				}
			}
		}

		$formatted_size = self::format_size( $total_size )['text'];

		// Return product total size
		return $formatted_size;
	}

	public static function format_size( $bytes, $precision = 2 ) {
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );

		$bytes = max( $bytes, 0 );
		$pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow   = min( $pow, count( $units ) - 1 );

		// $bytes /= pow( 1024, $pow );

		$bytes /= (1 << (10 * $pow));

		return [ 'bytes' => $bytes, 'text' => round( $bytes, $precision ) . ' ' . $units[ $pow ] ];
	}

	public function get_total_product_duration( $product_id ) {

		$product_episodes = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $product_id ) );

		$total_duration = (int) 0;
		if(!empty($product_episodes)){
			foreach ( $product_episodes as $product_episode ) {
				if ( !empty( $product_episode['episode_audio_file'] ) ) {

					$file_url = $product_episode['episode_audio_file'];

					if(is_singular($this->post_type) || is_front_page() || is_account_page() || is_cart() || is_checkout() || is_archive()){
						$file_url = $product_episode['episode_audio_file']['url'];
					}

					$file_id = attachment_url_to_postid( $file_url);

					// Use the wp_get_attachment_metadata function to get data
					$metadata = wp_get_attachment_metadata( $file_id );

                    if($metadata) {
                        $total_duration += $metadata['length'];
                    }
				}
			}
		}

		$formatted_duration = self::format_duration( $total_duration );

		// Return product total length
		return $formatted_duration;
	}

	public static function format_duration( $duration_in_seconds ) {
		$init    = (int) $duration_in_seconds;
		$hours   = floor( $init / 3600 );
		$minutes = floor( ( $init / 60 ) % 60 );
		$seconds = $init % 60;

		return $hours . 'hrs ' . $minutes . 'mins ' . $seconds . 's';
	}

	/**
	 * @param $cast_arr
	 *
	 * @return array
	 */
	public function episode_cast_image_type( $cast_arr, $key_index = 'main_cast_image' ) {
		if ( ! empty( $cast_arr ) ) {
			foreach ( $cast_arr as $key => $cast_obj ) {
				if ( isset( $cast_obj[ $key_index ] ) ) {
					$cast_arr[ $key ][ $key_index ] = (string) strip_tags( $cast_obj[ $key_index ] );
				}
			}
		} else {
			return [];
		}

		return $cast_arr;
	}

    /**
	 * @param $cast_arr
	 *
	 * @return array
	 */
	public function product_cast_name_type( $cast_arr, $key_index = 'main_cast_name' ) {
		if ( ! empty( $cast_arr ) ) {
            $cast_item_arr = null;
			foreach ( $cast_arr as $key => $cast_item ) {
				if ( isset( $cast_item[ $key_index ] ) && $cast_item[ $key_index ] != (bool) false) {
                    $cast_name_obj = $cast_item[ $key_index ];
                    if(isset($cast_name_obj->term_id) && $cast_name_obj !==  (bool) false ){
                        $cast_item_arr[] = [
                            'id'   => $cast_name_obj->term_id,
                            'name' => $cast_name_obj->name,
                            'slug' => $cast_name_obj->slug,
                        ];
                    }

                    $cast_arr[ $key ][ $key_index ] = $cast_item_arr;
				}else if ( !isset( $cast_item[ $key_index ] ) || $cast_item[ $key_index ] ==  (bool) false){
					$cast_arr[ $key ][ $key_index ] = $cast_item_arr;
				}
			}
        } else {
			return [];
		}

		return $cast_arr;
	}

    /**
     * @param $tax_arr
     *
     * @return array
     */
    public function product_tax_cast_type( $tax_arr) {
        $tax_items = null;

        if(!isset( $tax_arr ) || is_string($tax_arr) || $tax_arr ==  (bool) false ){

            return $tax_items;

        }else if ( is_array($tax_arr)) {
            foreach ( $tax_arr as $tax_item ) {
                if(isset($tax_item->term_id) && $tax_item !==  (bool) false ){
                    $tax_items[] = [
                        'id'   => $tax_item->term_id,
                        'name' => $tax_item->name,
                        'slug' => $tax_item->slug,
                    ];
                }
            }
        }
//        var_dump($tax_items);
        return $tax_items;
    }

    public function get_home_banner_mobile() {

		$sticky = get_option( 'sticky_posts' );

		$args = array(
			'post_type'      => $this->banner_post_type,
			'post__in'        => $sticky,
			'order'          => 'DESC',
			'posts_per_page' => 1,
		);


		$results = new \WP_Query( $args );

		$data_results = [];
		$results      = $results->posts;

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {
				$id             = $result->ID;
				$get_url        = get_field( 'banner_url', $id );
				$data_results[] = [
					'id'                => $id,
					'name'              => $result->post_title,
					'short_description' => strip_tags( $result->post_content ),
					'view_url'          => $get_url,
					'thumbnails'        => [
						'small'  => get_the_post_thumbnail_url( $id, 'thumbnail' ),
						'medium' => get_the_post_thumbnail_url( $id, 'medium' ),
						'large'  => get_the_post_thumbnail_url( $id, 'large' ),
						'full'  => get_the_post_thumbnail_url( $id, 'full' ),
					],

				];
			}
	
		}

		return $data_results;
	}

	public function get_home_banners( $posts_per_page = '' ) {
		$posts_per_page = $posts_per_page ?? get_option( 'posts_per_page' );

		$args = array(
			'post_type'      => $this->banner_post_type,
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);


		$results = new \WP_Query( $args );


		$data_results = [];
		$results      = $results->posts;


		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$id             = $result->ID;
				$get_url        = get_field( 'banner_url', $id );
				$data_results[] = [
					'id'                => $id,
					'name'              => $result->post_title,
					'short_description' => get_field('banner_details', $id),
					'view_url'          => $get_url,
					'thumbnails'        => [
						'small'  => get_the_post_thumbnail_url( $id, 'thumbnail' ),
						'medium' => get_the_post_thumbnail_url( $id, 'medium' ),
						'large'  => get_the_post_thumbnail_url( $id, 'large' ),
						'full'  => get_the_post_thumbnail_url( $id, 'full' ),
					],
				];
			}
		}

		return $data_results;
	}

	public function get_premier_release() {
		$tag          = $this->premiere_releases;
		$data_results = [];

		$args = array(
			'post_type'      => $this->post_type,
			'posts_per_page' => - 1,
			'order'          => 'DESC',
			'tax_query'      => array(

				array(
					'taxonomy' => $this->product_tag,
					'terms'    => [ $tag ],
					'field'    => 'slug',
				)
			)
		);

		$results = get_posts( $args );

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {
				$id        = $result->ID;
				$product   = wc_get_product( $id );
				$casts     = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$main_cast = $this::handle_cast_array( $casts );
				$episodes  = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $id ) );

				$data_results[] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}
		}

		return $data_results;
	}

	public static function handle_cast_array( $casts ) {
		if ( ! empty( $casts ) ) {
			return $casts;
		}

		return [];
	}

	public function get_homepage_category() {

		$data_results = [];

		$args = array(
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => 0
		);

		$categories = get_categories( $args );

		if ( empty( $categories ) ) {
			$message = __( 'No Category found', 'audioteria-wp' );

			return api_rest_response( $message, 'audioteria_no_category', [], 404, false );
		}

		foreach ( $categories as $category ) {
			if ( get_field( 'show_in_homepage', 'term_' . $category->term_id ) ) {
				$categories     = $this->get_products_by_categories( $category->term_id );
				$data_results[] = [
					'id'       => $category->term_id,
					'name'     => $category->name,
					'slug'     => $category->slug,
					'products' => $categories,
					'view_url' => get_term_link( $category->term_id )
				];
			}
		}

		return $data_results;
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
				$casts          = $this->product_cast_name_type(get_field( 'main_cast', $id ));
				$main_cast      = $this::handle_cast_array( $casts );
				$episodes       = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $result->ID ) );
				$data_results[] = self::product_returned_schema( $product, $this->taxonomy, $this->genre, [
					'main_cast' => $main_cast,
					'episodes'  => $episodes
				] );
			}
		}

		return $data_results;
	}

	/**
	 * @return array
	 */
	public function get_custom_categories() {
		$data_results = [];

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		);

		$product_categories = get_terms( $this->taxonomy, $cat_args );

		if ( ! is_wp_error( $product_categories ) ) {
			foreach ( $product_categories as $category ) {
				$data_results['categories'][] = [
					'id'               => $category->term_id,
					'name'             => $category->name,
					'slug'             => $category->slug,
					'description'      => $category->description,
					'show_in_homepage' => (bool) get_field( 'show_in_homepage', 'term_' . $category->term_id )
				];
			}
		}

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		);

		$product_genres = get_terms( $this->genre, $cat_args );
		if ( ! is_wp_error( $product_genres ) ) {
			foreach ( $product_genres as $category ) {
				$data_results['genres'][] = [
					'id'               => $category->term_id,
					'name'             => $category->name,
					'slug'             => $category->slug,
					'description'      => $category->description,
					'show_in_homepage' => (bool) get_field( 'show_in_homepage', 'term_' . $category->term_id )
				];
			}
		}

		return $data_results;
	}

	public function get_user_purchases( $extras = [] ) {
		$data_results = [];
		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;

		$customer_orders = get_posts(
			[
				'numberposts' => - 1,
				'meta_key'    => '_customer_user',
				'meta_value'  => $user_id,
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_is_paid_statuses() ),
			]
		);

		if ( ! empty( $customer_orders ) ) {

			foreach ( $customer_orders as $customer_order ) {

				$order_id = $customer_order->ID;

				$order = wc_get_order( $order_id );

				$order_data = $order->get_data();

				$order_date = $order_data['date_created']->date( 'd/m/Y' );

				$items = $order->get_items();

				foreach ( $items as $item ) {
					$product_id = $item->get_product_id();

					$product = wc_get_product( $product_id );

					$episodes = apply_filters( 'audioteria_wp_modify_episodes_fields', get_field( 'episodes', $product_id ) );

					$download_url = $this->return_downloadable_episodes( $episodes, true );

					$extras = array_merge( $extras, [
						'order_id'      => $order_id,
						'order_date'    => $order_date,
						'downloads_url' => $download_url
					] );
					array_push( $data_results, self::product_returned_schema( $product, $this->taxonomy, $this->genre, $extras ) );
				}
			}
		}

		return $data_results;
	}

	public function return_downloadable_episodes( $episodes, $path_only = true ) {
		$episodes_array = [];
		if ( ! empty( $episodes ) ) {
			foreach ( $episodes as $episode ) {
				if ( ! empty( $episode['episode_audio_file']['id'] ) ) {
					$id         = $episode['episode_audio_file']['id'];
					$audio_file = get_attached_file( $id );
					if ( $path_only ) {
						$audio_file = $episode['episode_audio_file']['url'];
					}
					array_push( $episodes_array, $audio_file );
				}
			}
		}

		return $episodes_array;
	}

	public function has_bought_product( $prod_arr ) {
		$bought = false;

		// Get all customer orders
		$customer_orders = get_posts( array(
			'numberposts' => - 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => 'shop_order', // WC orders post type
			'post_status' => 'wc-completed' // Only orders with status "completed"
		) );
		foreach ( $customer_orders as $customer_order ) {
			$order = wc_get_order( $customer_order );

			// Iterating through each current customer products bought in the order
			foreach ( $order->get_items() as $item ) {
				$product_id = $item->get_product_id();

				// Your condition related to your 2 specific products Ids
				if ( in_array( $product_id, $prod_arr ) ) {
					$bought = true;
					break;
				}
			}
		}

		return $bought;
	}

	public function get_customer_wishlist( $extras = [] ) {
		$data_results = [];

		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;

		$wishlist_metadata = get_user_meta( $user_id, $this->wishlist_key, true );

		$wishlist_metadata = json_decode( $wishlist_metadata );

		if ( ! empty( $wishlist_metadata ) ) {
			foreach ( $wishlist_metadata as $wishlist_id ) {
				$product        = wc_get_product( $wishlist_id );
				$home_url       = home_url();
				$add_to_bag_url = add_query_arg( [ 'add-to-cart' => $wishlist_id ], $home_url );

				$extras = array_merge( $extras, [ 'add_to_bag_url' => $add_to_bag_url ] );
				array_push( $data_results, self::product_returned_schema( $product, $this->taxonomy, $this->genre, $extras ) );
			}
		}

		return $data_results;
	}

	public function add_to_customer_wishlist( $user_id, $product_id, $extras = [] ) {

		$data_results = [];

		if ( empty( $user_id ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		if ( empty( $product_id ) || empty( wc_get_product( $product_id ) ) ) {

			return $data_results;
		}

		$prev_value = get_user_meta( $user_id, $this->wishlist_key, true );
		$decode_value = json_decode( $prev_value );

		if ( empty( $decode_value )) {
			$decode_value = [];
		}

		$new_value = array_unique( array_merge( $decode_value, [ $product_id ] ) );

		$encoded_value = json_encode( $new_value );

        update_user_meta( $user_id, $this->wishlist_key, $encoded_value, $prev_value);

		if ( $encoded_value === get_user_meta( $user_id, $this->wishlist_key, true ) ) {

			$wishlist_metadata = get_user_meta( $user_id, $this->wishlist_key, true );
			$wishlist_metadata_decode = json_decode( $wishlist_metadata );

			foreach ( $wishlist_metadata_decode as $wishlist_id ) {

				$product        = wc_get_product( $wishlist_id );
				$home_url       = home_url();
				$add_to_bag_url = add_query_arg( [ 'add-to-cart' => $wishlist_id ], $home_url );

				$extras = array_merge( $extras, [ 'add_to_bag_url' => $add_to_bag_url ] );
				array_push( $data_results, self::product_returned_schema( $product, $this->taxonomy, $this->genre, $extras ) );
			}
			$data_results['message'] = 'Added product to wishlist successfully';
            $data_results['status'] = true;
		}

		return $data_results;
	}

	public function remove_from_customer_wishlist( $user_id, $product_id, $extras = [] ) {

		$data_results = [];

		if ( empty( $user_id ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		if ( empty( $product_id ) || empty( wc_get_product( $product_id ) ) ) {

			return $data_results;
		}

		$prev_value = get_user_meta( $user_id, $this->wishlist_key, true );

		$decode_value = json_decode( $prev_value, true );

		$index = array_search( $product_id, $decode_value );

		if ( $index !== false ) {
			unset( $decode_value[ $index ] );
            $new_value = json_encode( $decode_value );
			update_user_meta( $user_id, $this->wishlist_key, $new_value, $prev_value );

			$wishlist_metadata = get_user_meta( $user_id, $this->wishlist_key, true );

			$wishlist_metadata = json_decode( $wishlist_metadata );

			foreach ( $wishlist_metadata as $wishlist_id ) {

				$product        = wc_get_product( $wishlist_id );
				$home_url       = home_url();
				$add_to_bag_url = add_query_arg( [ 'add-to-cart' => $wishlist_id ], $home_url );

				$extras = array_merge( $extras, [ 'add_to_bag_url' => $add_to_bag_url ] );
				array_push( $data_results, self::product_returned_schema( $product, $this->taxonomy, $this->genre, $extras ) );
			}
            $data_results['message'] = 'Removed product from wishlist successfully';
            $data_results['status'] = true;
		}

		return $data_results;
	}

	public function get_episode_audio_duration( $audio_file ) {
        $duration = 0;

		// Get file id from url
		$file_id = attachment_url_to_postid( $audio_file );

		// Use the wp_get_attachment_metadata) function to get data
		$metadata = wp_get_attachment_metadata( $file_id );

        if($metadata) {
            $duration = $metadata['length'];
        }

		// Return the file length
		return $duration;
	}

	public function get_episode_audio_filesize( $audio_file ) {
        $size = 0;
		// Get file id from url
		$file_id = attachment_url_to_postid( $audio_file );

		// Use the wp_get_attachment_metadata() function to get data
		$metadata = wp_get_attachment_metadata( $file_id );

        if($metadata) {
            // Return the filesize
            $size = $metadata['filesize'];
        }

        // Return the filesize
        return $size;
	}

	/**
	 * @return AbstractProducts
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}
