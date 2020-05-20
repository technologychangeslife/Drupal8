(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhMarketoAutoFill = {
      attach: function (context, settings) {
        if(typeof MktoForms2 !== 'undefined') {
          MktoForms2.whenReady(function (form) {
            setMetaValues();
          });
        }
        
        function setMetaValues() {
          $('.form-field').each(function() {
            $('input[name="' + $(this).data('key') + '"]').val($(this).data('value'));
          });
        }
        
        if ($('.node-type--product').length || $('.node-type--product-category').length) {
          $('.form-item-settings-label-display input[name="settings[label_display]"]').prop('checked', false);
          $('.form-item-settings-webform-id input').val('Product Contact Us (product_contact_us)');
        }
    }
  };
})(jQuery, Drupal, drupalSettings);