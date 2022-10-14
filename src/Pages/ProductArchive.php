<?php

namespace AudioteriaWP\Pages;



class ProductArchive
{

    public function __construct()
    {
        //temp comment breadcrumb for later
        remove_action("woocommerce_before_main_content", 'woocommerce_breadcrumb', 20);

        add_action("woocommerce_before_shop_loop", [$this, 'audioteria_wp_before_shop_wrapper'], 10);
        add_action("woocommerce_after_shop_loop", [$this, 'audioteria_wp_after_shop_wrapper'], 10);
        add_action("woocommerce_after_shop_loop_item", [$this, "audioteria_wp_after_title"], 10);
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
        add_action('woocommerce_shop_loop_item_title', [$this, 'audioteria_template_loop_product_title'], 10);

        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'audioteria_wp_product_desc'], 10);

        add_action("woocommerce_before_shop_loop", [$this, 'audioteria_wp_breadcrumb'], 10);
        add_filter('loop_shop_per_page', [$this, 'items_per_page_in_product_category_archives'], 900);

        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'custom_loop_product_thumbnail'], 10);
    
        remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
        add_action('woocommerce_archive_description', [$this, 'show_term_description'], 10);
    }

    public function custom_loop_product_thumbnail()
    {
        echo woocommerce_get_product_thumbnail('272x256', ['class' => 'book-card-image']);
    }

    public function audioteria_wp_before_shop_wrapper()
    {
        echo "<div class='wrapper'>";
    }

    public function audioteria_wp_after_shop_wrapper()
    {
        echo "</div>";
    }

    public function audioteria_template_loop_product_title()
    {
        echo '<article class="book-card-content"><h3 class="title ' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">' . get_the_title() . '</h3>';
    }

    public function audioteria_wp_after_title()
    {
        echo "</div></article>";
    }

    public function audioteria_wp_product_desc()
    {
        global $product;
        $short_description = limit_character($product->get_short_description(), 90, '');
        echo "</a><div class='content'>" . $short_description . " </div>";
        echo '<div class="price-buy-section">';
        wc_get_template('loop/price.php');
    }

    public function audioteria_wp_breadcrumb()
    {
        ob_start();
        ?>
        <div class="tax-head-sort">
            <ul class="book-nav">
                <li>
                    <a title="Home" href="<?= home_url('/')  ?>"><?= __('Home', 'audioteria-wp') ?></a>
                </li>
                <li class="separator separator-home"> | </li>
                <li class="active">
                    <a title="Action category" href="<?= get_permalink(); ?>"><?= woocommerce_page_title(); ?> </a>
                </li>
            </ul>
        <?php
        echo ob_get_clean();
    }
    
    public function items_per_page_in_product_category_archives($limit)
    {
        if (is_product_category() || taxonomy_exists('enterprise_cat')) {$limit = get_option('posts_per_page');}; // by default according to the html design

        return $limit;
    }

    public function show_term_description() {
        if (is_product_taxonomy()) {
            $term = get_queried_object();

            if (has_term($term, 'enterprise_cat')) {
                $term_name = $term->name;
                $term_description = $term->description;

                $enterprise_logo     = get_field('logo', 'enterprise_cat_' . $term->term_id);
                $enterprise_website  = get_field('website', 'enterprise_cat_' . $term->term_id);
                ob_start();
                ?>
                  <div class="sub-nav sub-nav-inner">
                    <div>
                      <h2 class="title"><?= $term_name; ?></h2>
                      <p class="subtitle"><?= $term_description ?></p>
                    </div>
                    <div class="big-finish">
                      <img src="<?= $enterprise_logo; ?>" />
                      <p><?= $enterprise_website; ?></p>
                    </div>
                  </div>
                <?php
                echo ob_get_clean();
            }
        }
    }

    /**
     * Singleton poop.
     *
     * @return ProductArchive
     */
    public static function get_instance(){
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

}