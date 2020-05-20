(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhProductsHighlightsPreFillBehavior = {
      attach: function (context, settings) {
        var pre_fill_titles = [
          Drupal.t('Benefits'), 
          Drupal.t('Features'), 
          Drupal.t('Applications')
        ];

        $('.field--name-field-subheading .form-item input').each(function(index){
            if( !this.value ) {
              $(this).val(pre_fill_titles[index]);
            }
        });

    }
  };
})(jQuery, Drupal, drupalSettings);