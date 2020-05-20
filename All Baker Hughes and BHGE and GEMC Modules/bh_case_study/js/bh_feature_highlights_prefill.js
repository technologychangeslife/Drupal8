(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhFeatureHighlightsPreFillBehavior = {
      attach: function (context, settings) {
        var pre_fill_titles = [
          Drupal.t('Challenges'), 
          Drupal.t('Results'),
        ];
        $('.field--name-field-heading .form-textarea').val('Overview');
        $('.field--name-field-subheading .form-item input').each(function(index){
            if( !this.value ) {
              $(this).val(pre_fill_titles[index]);
            }
        });

    }
  };
})(jQuery, Drupal, drupalSettings);