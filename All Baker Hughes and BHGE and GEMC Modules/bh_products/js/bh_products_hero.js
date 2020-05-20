/*globals Drupal:ture ,jQuery:false */
(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhProductsHeroBehavior = {
      attach: function (context, settings) {
        if($('body.node-type--product-category').length > 0 || $('body.node-type--product').length > 0)
        {
          if($('div.bh-hero__container span.field_eyebrow').length > 0)
          {
            $('div.bh-hero__container span.field_eyebrow').css('display','none');
          }else{
              if($('div.bh-hero-product__container span.field_eyebrow').length > 0)
              {
                $('div.bh-hero-product__container span.field_eyebrow').css('display','none');
              }
          }
        }
    }
}
})(jQuery, Drupal, drupalSettings);