(function ($) {
    "use strict";

    var productAjax = {};

    productAjax.audioteria_user_wishlist_action = function (event) {
        event.preventDefault();

        $('.preloader').show();

        let wishlist_elem = $(this);
        const product_id = $(this).data('item-id');
        const user_id = $(this).data('user-id');
        const action = $(this).data('action');
        const nonce = $(this).parents('.others').data('nonce');
        const data = {
            user_id,
            product_id,
            action,
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: {
                data,
                nonce,
                action: 'audioteria_wp_user_wishlist_action'
            },
            url: audioteria_product_ajax_js.ajaxurl,
            success: function (response) {
                console.log(response);
                if (response.status === true) {
                    wishlist_elem.html(response.data.wish_button);
                }
                $('.preloader').hide();
            },
            error: function (qXhr, textStatus, errorMessage) {
                alert('An error occurred');
                console.log(qXhr);
                console.log(textStatus);
                console.log(errorMessage);
                $('.preloader').hide();
            }
        });
    }


    productAjax.audioteria_rate_product = function (event) {
        event.preventDefault();

        $('.preloader').show();

        let wishlist_elem = $(this);
        const product_id = $(this).data('item-id');
        const user_id = $(this).data('user-id');
        const action = $(this).data('action');
        const nonce = $(this).parents('.others').data('nonce');
        const data = {
            user_id,
            product_id,
            action,
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: {
                data,
                nonce,
                action: 'audioteria_wp_user_wishlist_action'
            },
            url: audioteria_product_ajax_js.ajaxurl,
            success: function (response) {
                console.log(response);
                if (response.status === true) {
                    wishlist_elem.html(response.data.wish_button);
                }
                $('.preloader').hide();
            },
            error: function (qXhr, textStatus, errorMessage) {
                alert('An error occurred');
                console.log(qXhr);
                console.log(textStatus);
                console.log(errorMessage);
                $('.preloader').hide();
            }
        });
    }

    productAjax.init = function () {


        //wishlist action
        $(document).on('click', '#wishlist-action', productAjax.audioteria_user_wishlist_action);

        //rating action
        // $(document).on('click', '#search_directory', productAjax.audioteria_rate_product);


    }

    $(window).on('load', productAjax.init);

})(jQuery);
