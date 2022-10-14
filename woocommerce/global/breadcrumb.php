<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 // Settings
 $separator          = '&vert;';
 $breadcrums_id      = '';
 $breadcrums_class   = '';
 if(is_page(['about-us', 'about'])){
		 $breadcrums_class   = 'about-nav';
 }elseif (is_page(['my-account', 'account'])) {
		 $breadcrums_class   = 'account-nav';
 }elseif(is_product()){
		$breadcrums_class   = 'book-nav';
 }else {
		 $breadcrums_class   = 'page-nav';
 }
 $home_title         = 'Home';
	 
 // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
 $custom_taxonomy    = 'genre_cat';
		
 // Get the query & post information
 global $post,$wp_query;
		
 // Do not display on the homepage
 if ( !is_front_page() ) {
		
		 // Build the breadcrums
		 echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
				
		 // Home page
		 echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . 'Home' . '</a></li>';
		 echo '<li class="separator separator-home"> ' . $separator . ' </li>';
				
		 if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
					 
				 echo '<li class="item-current item-archive active"><span class="bread-current active bread-archive">' . post_type_archive_title($prefix = '', false) . '</span></li>';
					 
		 } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
					 
				 // If post is a custom post type
				 $post_type = get_post_type();
					 
				 // If it is a custom post type display name and link
				 if($post_type != 'post') {
					if($post_type == 'product') {
							 
						$post_type_object = get_post_type_object($post_type);
						$post_type_archive = get_post_type_archive_link($post_type);
					
						echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . home_url('/?s') . '" title="' . $post_type_object->labels->name . '">Search Results</a></li>';
						echo '<li class="separator"> ' . $separator . ' </li>';
					
					} else {
						 $post_type_object = get_post_type_object($post_type);
						 $post_type_archive = get_post_type_archive_link($post_type);
					 
						 echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
						 echo '<li class="separator"> ' . $separator . ' </li>';
					}
				 }
					 
				 $custom_tax_name = get_queried_object()->name;
				 echo '<li class="active item-current item-archive"><span class="bread-current active bread-archive">' . $custom_tax_name . '</span></li>';
					 
		 } else if ( is_single() ) {
					 
				 // If post is a custom post type
				 $post_type = get_post_type();
					 
				 // If it is a custom post type display name and link
				 if($post_type != 'post') {
					if($post_type == 'product') {
							 
						$post_type_object = get_post_type_object($post_type);
						$post_type_archive = get_post_type_archive_link($post_type);
					
						echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . home_url('/?s') . '" title="' . $post_type_object->labels->name . '">Search Results</a></li>';
						echo '<li class="separator"> ' . $separator . ' </li>';
					
					} else {
						 $post_type_object = get_post_type_object($post_type);
						 $post_type_archive = get_post_type_archive_link($post_type);
					 
						 echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
						 echo '<li class="separator"> ' . $separator . ' </li>';
					}
				 }	
					 
				 // Get post category info
				 $category = get_the_category();
					
				 if(!empty($category)) {
					 
						 // Get last category post is in
						 $last_category = end(array_values($category));
							 
						 // Get parent any categories and create array
						 $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
						 $cat_parents = explode(',',$get_cat_parents);
							 
						 // Loop through parent categories and store in variable $cat_display
						 $cat_display = '';
						 foreach($cat_parents as $parents) {
								 $cat_display .= '<li class="item-cat">'.$parents.'</li>';
								 $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
						 }
					
				 }
					 
				 // If it's a custom post type within a custom taxonomy
				 $taxonomy_exists = taxonomy_exists($custom_taxonomy);
				 if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
								
						$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
						if(!empty($taxonomy_terms)){
							$cat_id         = $taxonomy_terms[0]->term_id;
							$cat_nicename   = $taxonomy_terms[0]->slug;
							$cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
							$cat_name       = $taxonomy_terms[0]->name;
						}
				 }
					 
				 // Check if the post is in a category
				 if(!empty($last_category)) {
						 echo $cat_display;
						 echo '<li class="active item-current item-' . $post->ID . '"><span class="bread-current active bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';
							 
				 // Else if post is in a custom taxonomy
				 } else if(!empty($cat_id)) {
							 
						 echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
						 echo '<li class="separator"> ' . $separator . ' </li>';
						 echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current active bread-' . $post->ID . '" title="' . get_the_title() . '">' . strip_tags(get_the_title()) . '</span></li>';
					 
				 } else {
							 
						 echo '<li class="active item-current item-' . $post->ID . '"><span class="bread-current active bread-' . $post->ID . '" title="' . get_the_title() . '">' . strip_tags(get_the_title()) . '</span></li>';
							 
				 }
					 
		 } else if ( is_category() ) {
						
				 // Category page
				 echo '<li class="active item-current item-cat"><span class="bread-current active bread-cat">' . single_cat_title('', false) . '</span></li>';
						
		 } else if ( is_page() ) {
						
				 // Standard page
				 if( $post->post_parent ){
								
						 // If child page, get parents 
						 $anc = get_post_ancestors( $post->ID );
								
						 // Get parents in the right order
						 $anc = array_reverse($anc);
								
						 // Parent page loop
						 if ( !isset( $parents ) ) $parents = null;
						 foreach ( $anc as $ancestor ) {
								 $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
								 $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
						 }
								
						 // Display parent pages
						 echo $parents;
								
						 // Current page
						 echo '<li class="active item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></li>';
								
				 } else {
								
						 // Just display current page if not parents
						 echo '<li class="active item-current item-' . $post->ID . '"><span class="bread-current active bread-' . $post->ID . '"> ' . get_the_title() . '</span></li>';
								
				 }
						
		 } else if ( is_tag() ) {
						
				 // Tag page
						
				 // Get tag information
				 $term_id        = get_query_var('tag_id');
				 $taxonomy       = 'post_tag';
				 $args           = 'include=' . $term_id;
				 $terms          = get_terms( $taxonomy, $args );
				 $get_term_id    = $terms[0]->term_id;
				 $get_term_slug  = $terms[0]->slug;
				 $get_term_name  = $terms[0]->name;
						
				 // Display the tag name
				 echo '<li class="active item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><span class="bread-current active bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</span></li>';
				
		 } elseif ( is_day() ) {
						
				 // Day archive
						
				 // Year link
				 echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
				 echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
						
				 // Month link
				 echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
				 echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
						
				 // Day display
				 echo '<li class="active item-current item-' . get_the_time('j') . '"><span class="bread-current active bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></li>';
						
		 } else if ( is_month() ) {
						
				 // Month Archive
						
				 // Year link
				 echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
				 echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
						
				 // Month display
				 echo '<li class="item-month item-month-' . get_the_time('m') . '"><span class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</span></li>';
						
		 } else if ( is_year() ) {
						
				 // Display year archive
				 echo '<li class="active item-current item-current-' . get_the_time('Y') . '"><span class="bread-current active bread-current active-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</span></li>';
						
		 } else if ( is_author() ) {
						
				 // Auhor archive
						
				 // Get the author information
				 global $author;
				 $userdata = get_userdata( $author );
						
				 // Display author name
				 echo '<li class="active item-current item-current-' . $userdata->user_nicename . '"><span class="bread-current active bread-current active-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</span></li>';
				
		 } else if ( get_query_var('paged') ) {
						
				 // Paginated archives
				 echo '<li class="active item-current item-current-' . get_query_var('paged') . '"><span class="bread-current active bread-current active-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</span></li>';
						
		 } else if ( is_search() ) {
				
				 // Search results page
				 echo '<li class="active item-current item-current-' . get_search_query() . '"><span class="bread-current active bread-current active-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</span></li>';
				
		 } elseif ( is_404() ) {
						
				 // 404 page
				 echo '<li>' . 'Error 404' . '</li>';
		 }
		
		 echo '</ul>';
				
 }
