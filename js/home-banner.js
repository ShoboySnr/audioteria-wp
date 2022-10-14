(function ($) {

  "use strict";

  let audioteria_admin = {};

  const maxlength = 90;

  audioteria_admin.check_banner_details_limit = (event) => {
    const which_key = event.which;
    const text = event.target.value;

    if(which_key >= 33 || which_key == 13 || which_key == 32) {
      if(text.length >= maxlength) {
        event.preventDefault();
      };
    }
  }

  audioteria_admin.add_max_length_to_textarea = () => {
    $('.audioteria-custom-product-description textarea').attr('maxlength', maxlength);
    $('.audioteria-custom-product-description textarea').val($('.audioteria-custom-product-description textarea').val().substring(0, maxlength));

  }


  audioteria_admin.init = function () {
    audioteria_admin.add_max_length_to_textarea();
    $(document).on('keypress', '.audioteria-custom-product-description textarea', audioteria_admin.check_banner_details_limit);
  }


 $(window).on('load', audioteria_admin.init);

}) (jQuery);