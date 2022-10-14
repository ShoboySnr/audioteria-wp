(function ($) {

  "use strict";

  let audioteria_admin = {};

  const maxlength = 90;

  audioteria_admin.check_product_description_limit = (event) => {
    const which_key = event.which;
    const text = event.target.value;

    if(which_key >= 33 || which_key == 13 || which_key == 32) {
      if(text.length >= maxlength) {
        event.preventDefault();
      };
    }
  }

  audioteria_admin.add_max_length_to_textarea = () => {
    $('textarea.audioteria-custom-product-description').attr('maxlength', maxlength);
    $('textarea.audioteria-custom-product-description').val($('textarea.audioteria-custom-product-description').val().substring(0, maxlength));

  }


  audioteria_admin.init = function () {
    audioteria_admin.add_max_length_to_textarea();
    $(document).on('keypress', 'textarea.audioteria-custom-product-description', audioteria_admin.check_product_description_limit);
  }


 $(window).on('load', audioteria_admin.init);

}) (jQuery);