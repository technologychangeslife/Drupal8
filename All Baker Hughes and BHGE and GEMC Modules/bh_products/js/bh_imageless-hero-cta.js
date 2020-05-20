/*globals Drupal:ture ,jQuery:false */
(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.bhProductHeroBehavior = {
    attach: function (context, settings) {
      if($('#edit-field-cmp-cta-0-subform-field-cta-btn-type').length > 0){
        var sel = $('#edit-field-cmp-cta-0-subform-field-cta-btn-type',context).find("option:selected").text();
        if (sel.length) {
          imageLesscta(sel,"default");
        }
        $('#edit-field-cmp-cta-0-subform-field-cta-btn-type',context).on('change',function(){
          sel = $(this).find("option:selected").text();
          imageLesscta(sel,"default");
        });
      }
      if($('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-type').length >0){
        var sel = $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-type',context).find("option:selected").text();
        if (sel.length) {
          imageLesscta(sel,"entity_browser");
        }
        $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-type',context).on('change',function(){
          sel = $(this).find("option:selected").text();
          imageLesscta(sel,"entity_browser");
        });
      }

      function imageLesscta(sel,option){
        if(sel == 'Case Study Library'){
          // Default => node_edit
          if(option == "default"){
            $('#edit-field-cmp-cta-0-subform-field-cta-btn-style').find("option:contains('Text with Icon')").prop('selected', true).trigger('chosen:updated');
            $('#edit-field-cmp-cta-0-subform-field-cta-btn-style').find(":not(option:contains('Text with Icon'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-field-cmp-cta-0-subform-field-cta-btn-size').find("option:contains('Default')").prop('selected', true).trigger('chosen:updated');
            $('#edit-field-cmp-cta-0-subform-field-cta-btn-size').find(":not(option:contains('Default'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-field-cmp-cta-0-subform-field-cta-btn-icon').find("option:contains('Arrow')").prop('selected', true).trigger('chosen:updated');
            $('#edit-field-cmp-cta-0-subform-field-cta-btn-icon').find(":not(option:contains('Arrow'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-field-cmp-cta-0-subform-field-button-type').find("option:contains('- None -')").prop('selected', true).trigger('chosen:updated');
            $('#edit-field-cmp-cta-0-subform-field-button-type').find(":not(option:contains('- None -'))").prop('disabled', true).trigger('chosen:updated');
          }
          // Entity Browser
          if(option == "entity_browser"){
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-style').find("option:contains('Text with Icon')").prop('selected', true).trigger('chosen:updated');
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-style').find(":not(option:contains('Text with Icon'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-size').find("option:contains('Default')").prop('selected', true).trigger('chosen:updated');
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-size').find(":not(option:contains('Default'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-icon').find("option:contains('Arrow')").prop('selected', true).trigger('chosen:updated');
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-icon').find(":not(option:contains('Arrow'))").prop('disabled', true).trigger('chosen:updated');

            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-button-type').find("option:contains('- None -')").prop('selected', true).trigger('chosen:updated');
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-button-type').find(":not(option:contains('- None -'))").prop('disabled', true).trigger('chosen:updated');
          }
        }
        else{
          if(option == "default"){
            $('#edit-field-cmp-cta-0-subform-field-button-type option,#edit-field-cmp-cta-0-subform-field-cta-btn-icon option,#edit-field-cmp-cta-0-subform-field-cta-btn-size option,#edit-field-cmp-cta-0-subform-field-cta-btn-style option').prop('disabled', false).trigger('chosen:updated');
          }
          if(option == "entity_browser"){
            $('#edit-inline-entity-form-field-cmp-cta-0-subform-field-button-type option,#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-icon option,#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-size option,#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-style option').prop('disabled', false).trigger('chosen:updated');
          }
        }
      }
    }
  };
})(jQuery, Drupal, drupalSettings);
