<?php

/**
 * Check if the current username can manage_options
 */
function check_user_capability() {
    return current_user_can('manage_options');
}

function get_top_page_id(){
    global $post;
    
    if($post->post_parent){
        $top_pages = array_reverse(get_post_ancestors($post->ID));
        return $top_pages[0];
        
    }
    
    return $post->ID;
}

function api_rest_response($message = 'Nothing found', $code = 'audioteria_code', $data = [], $status_code = 200, $is_success = true) {
    return new \WP_REST_Response(
        [
            'success'    => (bool) $is_success,
            'statusCode' => (int) $status_code,
            'code'       => $code,
            'message'    => $message,
            'data'       => $data,
        ],
        (int) $status_code
    );
}

function add_icon_according_to_label($endpoint = '') {
    switch ($endpoint) {
        case 'dashboard':
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/purchase-icon.svg');
            break;
        case 'customer-wishlist':
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/wishlist-icon.svg');
            break;
        case 'customer-logout':
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/logout-icon.svg');
            break;
        case 'update-password':
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/lostpassword-icon.svg');
            break;
        case 'edit-address':
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/address-book.svg');
            break;
        default:
            return include(AUDIOTERIA_ASSETS_ICONS_DIR. '/default-icon.svg');
    }
}

function find_audioteria_categories($product_id, $taxonomy, $first_result = true) {
    $return_terms = [];
    $terms = wp_get_object_terms($product_id, $taxonomy);
    
    if(!is_wp_error($terms)) {
        foreach ($terms as $term) {
    
            $return_terms[] = [
                'id'                => $term->term_id,
                'name'              => $term->name,
                'slug'              => $term->slug,
                'description'       => $term->description,
                'show_in_homepage'  => (bool) get_field('show_in_homepage', 'term_'.$term->term_id)
            ];
        }
    }
    
    return $return_terms;
}

function get_all_days_months_years() {
    $days = [];
    $days_count = 31;
    for($count = 1; $count <= $days_count; $count++) {
        array_push($days, $count);
    }
    
    $months = [
        '1' => __('January', 'guardian-epaper'),
        '2' => __('February', 'guardian-epaper'),
        '3' => __('March', 'guardian-epaper'),
        '4' => __('April', 'guardian-epaper'),
        '5' => __('May', 'guardian-epaper'),
        '6' => __('June', 'guardian-epaper'),
        '7' => __('July', 'guardian-epaper'),
        '8' => __('August', 'guardian-epaper'),
        '9' => __('September', 'guardian-epaper'),
        '10' => __('October', 'guardian-epaper'),
        '11' => __('November', 'guardian-epaper'),
        '12' => __('December', 'guardian-epaper'),
    ];
    
    $years = [];
    $period = new \DatePeriod(
        new \DateTime('1910-01-01'),
        \DateInterval::createFromDateString('1 year'),
        new \DateTime('2010-01-01')
    );
    foreach ($period as $value) {
        $year = $value->format('Y');
        $years[$year] = $year;
    }
    
    return [
        'days'    => $days,
        'months'    => $months,
        'years'     => $years
    ];
}

function limit_character($text, $length = 100, $more = '...') {
    if(strlen($text) < $length) {
        return $text;
    } else {
        $text=substr($text,0,$length) . $more;
        return $text;
    }
}


function encrypt_decrypt_password($stringToHandle = '', $encryptDecrypt = 'e'){
    $output = null;
    $secret_key = 'A8$9DKJB@cq-J|uFN&?!*Jc++=}>h<oDldx[r8+>Fn]FO]IiBh6){nhFXWbzkTHr';
    $secret_iv = 'Lg=#bS]zAn!;l[ep o*IJ{lhV7_2/8 i^I$n^zS;:e60VsX1I(l|Me>s+!qi=j?&';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
 
    if($encryptDecrypt == 'e') {
        $output = base64_encode(openssl_encrypt($stringToHandle, "AES-256-CBC", $key,0, $iv));
    } else if($encryptDecrypt == 'd') {
        $output = openssl_decrypt(base64_decode($stringToHandle), "AES-256-CBC", $key,0, $iv);
    }

    return $output;
}

add_filter('audioteria_modify_body_class', function() {
    $body_class = '';
    if(is_page(['my-account', 'account'])) {
        $body_class = 'account';
    } else if(is_product() || is_page('cart') || is_cart()) {
        $body_class = 'bg-light100 lg:bg-gray600';
    } else if (is_page_template('page-contact-faq.php')) {
        $body_class = 'contact';
    } else if(is_search()) {
        $body_class = 'search';
    } else if(is_checkout()) {
        $body_class = 'bg-light100 lg:bg-gray600';
    }
    
    return $body_class;
}, 10, 1);


function get_custom_product_meta_html(array $term_array, string $description_text = 'Written by ', string $permalink_rel = 'writer', string $paragraph_class = 'category')  {

    $html = '';

    if(empty($term_array)) {
        echo $html;
    }

    $item_count = count($term_array);
    $count = 0;

    $html .= '<p class="'.$paragraph_class.'">';
    $html .= $description_text;

    foreach ($term_array as $term_item) {

        $term_link = get_term_link($term_item['id']);
        $term_name = $term_item['name'];
        $html .= '<a href="' . $term_link . '" rel="';
        $html .= $permalink_rel;
        $html .= '">' . $term_name . '</a>';
        if ($item_count > 1 ) {
            if ( $count < $item_count && $count + 1 !== $item_count) {
                $html .= ', ';
                $count++;
            }
        }
    }


    $html .= '</p>';

    echo $html;
}
