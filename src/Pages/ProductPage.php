<?php

namespace AudioteriaWP\Pages;

use AudioteriaWP\Data\AbstractProducts;

class ProductPage
{

  public function __construct()
  {
    add_action( 'wp_enqueue_scripts', [$this, 'frontend_scripts']);

    remove_action( 'woocommerce_before_single_product_summary',  'woocommerce_template_single_title', 5 );

    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 30 );

    remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 10);
    
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 20 );

    add_action('woocommerce_single_product_summary', [$this, 'audioteria_add_writer_to_meta'], 30);

    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 40 );

    add_filter( 'woocommerce_get_price_html', [$this, 'audioteria_price_return'], 11, 2 );

    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 50);

    add_filter( 'woocommerce_is_sold_individually',[$this, 'audioteria_remove_all_quantity_fields'], 10, 2 );

    add_filter( 'manage_edit-product_cat_columns', [$this, 'audioteria_remove_category_image'], 99 );

    add_action('woocommerce_single_product_summary', [$this, 'audioteria_product_is_in_wishlist'], 60);

    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 70);
    
    add_action('audioteria_custom_product_tabs', [$this, 'audioteria_custom_product_tabs']);
    
    add_action('woocommerce_after_single_product', [$this, 'audioteria_product_trailer']);

    add_action( 'wp_enqueue_scripts', [$this, 'product_ajax_scripts'] );

    add_action('wp_ajax_audioteria_wp_user_wishlist_action', [$this, 'audioteria_wp_user_wishlist_action']);

    add_action('wp_ajax_nopriv_audioteria_wp_user_wishlist_action', [$this, 'audioteria_wp_user_wishlist_action']);
  }

  public function frontend_scripts()
  {
    if(is_product()) {
      wp_enqueue_script('audioteria-wp-book-card', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/components/book_card.js', array(), AUDIOTERIA_WP_THEME_VERSION, false);
      wp_enqueue_script('audioteria-wp-product', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/scripts/product.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
      wp_enqueue_script('audioteria-wp-product-trailer', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/scripts/trailer_modal.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
      wp_enqueue_script('audioteria-wp-product-trailer-player', get_template_directory_uri() . '/js/play-audio.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
    }
  }

  public function product_ajax_scripts() {
    wp_enqueue_script( 'audioteria-product-ajax-js', get_template_directory_uri() . '/js/product-ajax-calls.js', array('jquery', 'wp-util', 'wp-hooks') );
    wp_localize_script( 'audioteria-product-ajax-js', 'audioteria_product_ajax_js', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('audioteria-product-ajax-js');
  }

  public function rate_product($product_id, $rating_value, $comment) {
    $current_user = wp_get_current_user();
    
    $product = wc_get_product($product_id);
    
    $data_results = [];
    

    if ('product' !== get_post_type($product_id)) {
        $message = __( 'Invalid product', 'audioteria-wp' );

        wc_add_notice( $message );
    }
    
    if(!AbstractProducts::get_instance()->has_bought_product($product_id)) {
        $message = __( 'You can\'t rate this product because you have not purchased it.', 'audioteria-wp' );

        wc_add_notice( $message );
    }
    
    if(empty($rating_value)) {
        $message = __( 'Enter a rate value', 'audioteria-wp' );

        wc_add_notice( $message );
    }

    $ratings = sanitize_text_field($rating_value);
    $content = $comment ? $comment : '';
    
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

        wc_add_notice( $message );
    }
    
    if ( ! is_wp_error( update_comment_meta( $product_review_id, 'rating', $ratings) )){
    
      $message = __( 'Products rating successful.', 'audioteria-wp' );
      
      $data_results = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy);

      wc_add_notice( $message );
    }
    
    return $data_results;
    
  }

  public function audioteria_price_return($price_html, $product){
    global $product;
    
    $product_price = $product->get_price();
    $price = get_woocommerce_currency_symbol();
    $price .= $product_price;

    return $price;
  }

  public function audioteria_add_writer_to_meta() {
    global $product;

    $products_data = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy, AbstractProducts::get_instance()->genre);

    if(!empty($products_data)){
      $genres = $products_data['product_genre'];
      $writers = $products_data['main_written_by'];
      $narrators = $products_data['main_narrated_by'];
      $studios = $products_data['main_studio'];
      $features = $products_data['main_featuring'];
      $directors = $products_data['main_directed_by'];

      ob_start();
        if(!empty($genres)){
           get_custom_product_meta_html( $genres, 'Genre: ', 'genre');
        }
        if (in_array('audio-books', array_column($genres, 'slug'))){
            if(!empty($writers)){
               get_custom_product_meta_html( $writers, 'Written by: ', 'writer');
            }
            if(!empty($narrators)){
               get_custom_product_meta_html( $narrators, 'Narrated by: ', 'narrator');
            }
            if(!empty($studios)){
               get_custom_product_meta_html( $studios, 'Studio: ', 'studio');
            }
      } else {
        if(!empty($features)){
           get_custom_product_meta_html( $features, 'Featuring: ', 'featured');
        }
        if(!empty($writers)){
           get_custom_product_meta_html( $writers, 'Written by: ', 'writer');
        }
        if(!empty($directors)){
            get_custom_product_meta_html( $directors, 'Directed by: ', 'director');
        }
        if(!empty($studios)){
           get_custom_product_meta_html( $studios, 'Studio: ', 'studio');
        }
      } ?>
     </div>
      <?php echo ob_get_clean();
    }
  }

  public function audioteria_remove_all_quantity_fields( $return, $product ) {
    return true;
  }

  public function audioteria_check_if_product_is_in_wishlist( $product) {
    global $product;

    $wishlist_data = AbstractProducts::get_instance()->get_customer_wishlist();
    $product_id = $product->get_id();

    if(!empty($wishlist_data)){
      foreach($wishlist_data as $wishlist_item){
        if($wishlist_item['id'] === $product_id){
          return true;
        }
      }
    }
    return false;
  }

  public function audioteria_product_is_in_wishlist(){
    global $product;
    $current_user = wp_get_current_user();
    $user_id      = $current_user->ID;
    $product_id  = $product->get_id();
    $check_wishlist = $this->audioteria_check_if_product_is_in_wishlist($product);

    ob_start(); ?>

      <div class="others"  data-nonce="<?= wp_create_nonce("audioteria_wp_wishlist_action"); ?>">

    <?php if($check_wishlist) { ?>
      <button id="wishlist-action" data-user-id="<?= $user_id ?>" data-item-id="<?= $product_id; ?>" data-action="remove">
        <span>
          <?= __('Wishlist', 'aundioteria-wp') ?>
        </span>
        <?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/singles-wishlist-icon-checked.svg'); ?>
      </button>
    <?php }else{ ?>
      <button id="wishlist-action" data-userid="<?= $user_id ?>" data-item-id="<?= $product_id; ?>" data-action="add">
        <span>
          <?= __('Wishlist', 'aundioteria-wp') ?>
        </span>
        <?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/singles-wishlist-icon.svg'); ?>
      </button>
    <?php }

    echo ob_get_clean();
  }

  public function audioteria_wp_user_wishlist_action(){

      $response = [];

      if (!wp_verify_nonce($_POST['nonce'], "audioteria_wp_wishlist_action")) {
            $response['status'] = false;
            $response['message'] = __('There was an error. Please refresh and try again.', 'audioteria-wp');

            echo json_encode($response);
            wp_die();
      }
      
      
      // check if a user is logged in
      if(!is_user_logged_in()) {
          $response['status']       = false;
          $response['message']      = __('Sorry, please login before you can add to wish lists.', 'audioteria-wp');
          $response['redirect_url'] = home_url('my-account');
  
          echo json_encode($response);
          wp_die();
      }

      $user_id = get_current_user_id();
      $product_id = $_POST['data']['product_id'];
      $action = $_POST['data']['action'];

      if(isset($user_id) && $action === 'remove' && !empty($product_id)) {
            $wishlist_action = AbstractProducts::get_instance()->remove_from_customer_wishlist($user_id, $product_id);

            if($wishlist_action['status']) {
                wc_add_notice( __('Product successfully removed from your wishlist', 'audioteria-wp'));
                $response['status'] = true;
                $response['data'] = [
                    'wish_button' => $this->wp_ajax_audioteria_wp_wishlist_button_html($user_id, $product_id, $action),
                ];
            }else {
                $response['status'] = false;
                $response['message'] = __('There was an error. Please refresh and try again.', 'audioteria-wp');
            }
      }

      if(isset($user_id) && $action === 'add' && !empty($product_id)) {

            $wishlist_action = AbstractProducts::get_instance()->add_to_customer_wishlist($user_id, $product_id);

            if($wishlist_action['status']) {
                wc_add_notice( __('Product successfully added from your wishlist', 'audioteria-wp'));
                $response['status'] = true;
                $response['data'] = [
                    'wish_button' => $this->wp_ajax_audioteria_wp_wishlist_button_html($user_id, $product_id, $action),
                ];
            }else {
                $response['status'] = false;
                $response['message'] = __('There was an error. Please refresh and try again.', 'audioteria-wp');
            }
      }

      echo json_encode($response);
      wp_die();
  }

  public function wp_ajax_audioteria_wp_wishlist_button_html($user_id, $product_id, $action) {
    $user_button_html = '';
    $icon = '';
    $new_action = '';
    if(empty($user_id) || empty($product_id)) return $user_button_html;
    if($action === "remove") {
        $icon = AUDIOTERIA_ASSETS_ICONS_DIR . '/singles-wishlist-icon.svg';
        $new_action = 'add';
    }else {
        $icon = AUDIOTERIA_ASSETS_ICONS_DIR . '/singles-wishlist-icon-checked.svg';
        $new_action = "remove";
    }

    $user_button_html = sprintf('<button id="wishlist-action" data-user-id="%1s" data-item-id="%2s" data-action="%3s"><span>%4s</span> %5s</button>',
    $user_id,
    $product_id,
    $new_action,
    __('Wishlist', 'audioteria-wp'),
    include( $icon ),
    );

    return $user_button_html;
}

  public function audioteria_custom_product_tabs(){
    global $product;

    $products_data = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy, AbstractProducts::get_instance()->genre, []);

    if(!empty($products_data)){
      $product_id = $products_data['id'];
      $product_duration = AbstractProducts::get_instance()->get_total_product_duration($product_id);
      $product_size = AbstractProducts::get_instance()->get_total_product_filesize($product_id);
      $casts = get_field( 'main_cast', $product_id );
      $main_production_credit = !empty(get_field( 'main_production_credit', $product_id )) ? get_field( 'main_production_credit', $product_id ) : '';
      $main_extras = !empty(get_field( 'main_extras', $product_id )) ? get_field( 'main_extras', $product_id ) : '';
      $main_cast = AbstractProducts::get_instance()->handle_cast_array($casts);
      ob_start(); ?>

      <div class="content-section-tab">
        <button class="active"><?= __('About', 'audioteria-wp') ?></button>
        <button><?= __('Cast', 'audioteria-wp') ?></button>
        <button><?= __('Production Credits', 'audioteria-wp') ?></button>
        <button><?= __('Extras', 'audioteria-wp') ?></button>
      </div>
      <div class="content main-tab active">
        <div class="info">
          <p><?= __('Total Duration: ', 'audioteria-wp') ?> <span><?= $product_duration ?></span></p>
          <p><?= __('Total Size: ', 'audioteria-wp') ?> <span><?= $product_size ?></span></p>
          <p><?= __('Release Date: ', 'audioteria-wp') ?> <span><?= $products_data['main_release_date'] ?></span></p>
        </div>
        <div class="article">
          <?= the_content(); ?>
          <?php if (!empty($products_data['product_copyright'])) { ?>
            <p class="small_copyright">© <?= $products_data['product_copyright']; ?> </p>
          <?php } ?>
        </div>
      </div>
      <div class="content cast-crew">
          <?php if (!empty($main_cast)) { ?>
            <?php foreach($main_cast as $cast_member) {  ?>
              <div class="info">
                <p><?= $cast_member['main_cast_character_name'] ?>
                  <?php if(!empty($cast_member['main_cast_name']->name)){ ?>
                    <span>( <a href="<?= get_term_link($cast_member['main_cast_name']->term_id ); ?>" rel="actor"> <?= $cast_member['main_cast_name']->name ?> </a>)</span>
                  <?php } ?>
                </p>
              </div>
          <?php } ?>
         <?php } ?>
        <div class="article">
          <?php if (!empty($products_data['product_copyright'])) { ?>
            <p class="small_copyright">© <?= $products_data['product_copyright']; ?> </p>
          <?php } ?>
        </div>
      </div>
      <div class="content">
        <div class="article">
          <?= $main_production_credit; ?>
          <?php if (!empty($products_data['product_copyright'])) { ?>
            <p class="small_copyright">© <?= $products_data['product_copyright']; ?> </p>
          <?php } ?>
        </div>
      </div>
      <div class="content">
        <div class="article">
          <?= $main_extras; ?>
          <?php if (!empty($products_data['product_copyright'])) { ?>
            <p class="small_copyright">© <?= $products_data['product_copyright']; ?> </p>
          <?php } ?>
        </div>
      </div>
    <?php
      echo ob_get_clean();
    }
  }

  public function audioteria_product_trailer(){
    global $product;

    $products_data = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy, AbstractProducts::get_instance()->genre, []);

    if(!empty($products_data)){
    
      $check_wishlist = $this->audioteria_check_if_product_is_in_wishlist($product);
      $product_id = $products_data['id'];
      $casts = get_field( 'main_cast', $product_id );
      $product_trailer =  $products_data['main_trailer'];
      $categories = $products_data['product_category'];
      $product_img = !empty($products_data['thumbnails']) ? $products_data['thumbnails']['medium'] : AUDIOTERIA_ASSETS_IMAGES_URI .'/placeholder.png';
      ob_start(); ?>
        <div id="trailer-modal" class="">

          <div class="trailer-modal-background">

            <div class="trailer-modal-content">
              <button id="close" class="close">
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M25.0003 4.16663C13.4795 4.16663 4.16699 13.4791 4.16699 25C4.16699 36.5208 13.4795 45.8333 25.0003 45.8333C36.5212 45.8333 45.8337 36.5208 45.8337 25C45.8337 13.4791 36.5212 4.16663 25.0003 4.16663ZM35.417 32.4791L32.4795 35.4166L25.0003 27.9375L17.5212 35.4166L14.5837 32.4791L22.0628 25L14.5837 17.5208L17.5212 14.5833L25.0003 22.0625L32.4795 14.5833L35.417 17.5208L27.9378 25L35.417 32.4791Z"
                    fill="black" />
                </svg>
              </button>
              <div class="modal-info-wrapper">
                <div class="modal-info">
                  <div class="modal-image">
                    <img src="<?= $product_img ?>" alt="<?= $products_data['name'] ?>" >
                  </div>
                  <div class="modal-title">
                    <?= $products_data['name'] ?>
                    <div class="modal-categories">
                      <span>
                        <?php foreach ($categories as $category) {
                          echo $category['name'].',';
                        } ?>
                      </span>
                    </div>
                  </div>
                  <div class="modal-audio">
                    <?= do_shortcode('[audio src="'. $product_trailer .'" autoplay="off"preload="auto"][/audio]') ?>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
    <?php
      echo ob_get_clean();
    }
  }

  public function audioteria_remove_category_image($columns){
      unset( $columns['thumb'] );
      return $columns;
  }


  /**
   * @return ProductPage
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
