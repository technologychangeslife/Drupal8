/*globals Drupal:ture ,jQuery:false */
(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.bhProductHeroBehavior = {
    attach: function (context, settings) {
      var node_path = getUrlParameter('original_path');

      if ((node_path.length > 0 && node_path !== 'undefined') || drupalSettings.content_type !== 'undefined') {   
        if (node_path === '/node/add/product_category' || drupalSettings.content_type === 'product_category') {
          
          var height_modifier = 'select[data-drupal-selector="edit-field-height-modifier"]';
          if ($(height_modifier).length) {
            $(height_modifier + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
            $(height_modifier + ' option[value="_none"]').remove();
          }

          var text_align = 'select[data-drupal-selector="edit-field-text-alignment"]';
          if ($(text_align).length) {
            $(text_align + ' option[value="--align-image-right"]').unwrap('div').wrap('<div style="display: none;" />');
            $(text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
          }

          var field_eyebrow = 'div[data-drupal-selector="edit-field-eyebrow-wrapper"]';
          if ($(field_eyebrow).length) {
            $(field_eyebrow).unwrap('div').wrap('<div style="display: none;" />');
          }

          /* if hero already added then hide from details in block */
          var field_eyebrow_label = 'div.field--name-field-eyebrow';
          if ($(field_eyebrow_label).length) {
            $(field_eyebrow_label).unwrap('div').wrap('<div style="display: none;" />');
          }

          var cta_style = '.field--name-field-cta-btn-style';
          if ($(cta_style).length) {
            $(cta_style).children().hide();
          }

          var cta_size = '.field--name-field-cta-btn-size';
          if ($(cta_size).length) {
            $(cta_size).children().hide();
          }

          var cta_icon = '.field--name-field-cta-btn-icon';
          if ($(cta_icon).length) {
            $(cta_icon).children().hide();
          }

          var cta_type_deprecate = '.field--name-field-button-type';
          if ($(cta_type_deprecate).length) {
            $(cta_type_deprecate).children().hide();
          }

          var existCondition = setInterval(function () {
            if ($('select[data-drupal-selector="edit-inline-entity-form-field-height-modifier"]').length) {
              var browser_form = '[data-drupal-selector="entity-browser-hero-entity-browser-form"]';
              var select_height = 'select[data-drupal-selector="edit-inline-entity-form-field-height-modifier"]';
              var select_text_align = 'select[data-drupal-selector="edit-inline-entity-form-field-text-alignment"]';
              var field_eyebrow = 'div[data-drupal-selector="edit-inline-entity-form-field-eyebrow-wrapper"]';
              $(browser_form).find(select_height + ' option[value="_none"]').remove();
              $(browser_form).find(select_height + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
              $(browser_form).find(select_text_align + ' option[value="--align-image-right"]').unwrap('div').wrap('<div style="display: none;" />');
              $(browser_form).find(select_text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
              $(field_eyebrow).unwrap('div').wrap('<div style="display: none;"/>');
              clearInterval(existCondition);
            }
          }, 100); // check every 100ms
        }
        
        if (node_path === '/node/add/product' || drupalSettings.content_type === 'product') {
          var text_align = 'select[data-drupal-selector="edit-field-text-alignment"]';
          if ($(text_align).length) {
            $(text_align + ' option[value="--align-right"]').unwrap('div').wrap('<div style="display: none;" />');
            $(text_align + ' option[value="--align-center"]').unwrap('div').wrap('<div style="display: none;" />');
            $(text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
          }

          var height_modifier = 'select[data-drupal-selector="edit-field-height-modifier"]';
          if ($(height_modifier).length) {
            $(height_modifier + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
            $(height_modifier + ' option[value="_none"]').remove();
          }

          var field_eyebrow = 'div[data-drupal-selector="edit-field-eyebrow-wrapper"]';
          if ($(field_eyebrow).length) {
            $(field_eyebrow).unwrap('div').wrap('<div style="display: none;" />');
          }

          /* if hero already added then hide from details in block */
          var field_eyebrow_label = 'div.field--name-field-eyebrow';
          if ($(field_eyebrow_label).length) {
            $(field_eyebrow_label).unwrap('div').wrap('<div style="display: none;" />');
          }

          var cta_type = '#edit_inline_entity_form_field_cmp_cta_0_subform_field_cta_btn_type_chosen';
          if ($(cta_type).length) {
            $(cta_type).css('width', '153px');
          }

          var cta_style = '.field--name-field-cta-btn-style';
          if ($(cta_style).length) {
            $(cta_style).children().hide();
          }

          var cta_size = '.field--name-field-cta-btn-size';
          if ($(cta_size).length) {
            $(cta_size).children().hide();
          }

          var cta_icon = '.field--name-field-cta-btn-icon';
          if ($(cta_icon).length) {
            $(cta_icon).children().hide();
          }

          var cta_type_deprecate = '.field--name-field-button-type';
          if ($(cta_type_deprecate).length) {
            $(cta_type_deprecate).children().hide();
          }

          var existCondition = setInterval(function () {
            if ($('select[data-drupal-selector="edit-inline-entity-form-field-height-modifier"]').length &&
                $('select[data-drupal-selector="edit-inline-entity-form-field-text-alignment"]').length) {
              var browser_form = '[data-drupal-selector="entity-browser-hero-entity-browser-form"]';
              var select_height = 'select[data-drupal-selector="edit-inline-entity-form-field-height-modifier"]';
              var select_text_align = 'select[data-drupal-selector="edit-inline-entity-form-field-text-alignment"]';
              var field_eyebrow = 'div[data-drupal-selector="edit-inline-entity-form-field-eyebrow-wrapper"]';
              
              if (node_path === '/node/add/product') {
                $(select_text_align + ' option[value="--align-image-right"]').attr("selected", true);
                if (($(select_text_align).length > 0) && (jQuery(context).find(select_text_align).once('name-of-my-behavior').length > 0)) {
                    $(select_text_align).parent("div").append('<div id="edit-inline-entity-form-field-text-alignment-desc" class="description">Use \'Image Right\' for standard Product pages and use \'Left\' for Product Campaign pages.</div>');
                }
              }
              
              $(browser_form).find(select_height + ' option[value="_none"]').remove();
              $(browser_form).find(select_height + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
              $(browser_form).find(select_text_align + ' option[value="--align-right"]').unwrap('div').wrap('<div style="display: none;" />');
              $(browser_form).find(select_text_align + ' option[value="--align-center"]').unwrap('div').wrap('<div style="display: none;" />');
              $(browser_form).find(select_text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
              $(field_eyebrow).unwrap('div').wrap('<div style="display: none;"/>');
              $('#edit_inline_entity_form_field_cmp_cta_0_subform_field_cta_btn_type_chosen').css('width', '153px');
              clearInterval(existCondition);
            }
          }, 100); // check every 100ms
        }
      }
      
    // When editing the hero node from inside product
    if($('form.node-product-form').length > 0 || $('form.node-product-edit-form').length > 0){
       /* if hero already added then hide from details in block */
       var field_eyebrow_label = 'div.field--name-field-eyebrow';
       if ($(field_eyebrow_label).length) {
         $(field_eyebrow_label).unwrap('div').wrap('<div style="display: none;" />');
       }
        var text_align = 'select[name="field_text_alignment"]';
        if (($(text_align).length > 0) && (jQuery(context).find(text_align).once('name-of-my-behavior').length > 0)) {
            $(text_align).parent("div").append('<div id="field_text_alignment_desc" class="description">Use \'Image Right\' for standard Product pages and use \'Left\' for Product Campaign pages.</div>');
        }


        var heroProduct = setInterval(function () {
        var ui_dialog = '.ui-dialog form.node-hero-edit-form';
        var select_height = 'select[name="field_height_modifier"]';
        var text_align = 'select[name="field_text_alignment"]';
        var field_eyebrow = 'div[data-drupal-selector="edit-field-eyebrow-wrapper"]';

        if ($(ui_dialog).length) {
          $(ui_dialog).find(select_height + ' option[value="_none"]').remove();
          $(ui_dialog).find(select_height + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find(text_align + ' option[value="--align-right"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find(text_align + ' option[value="--align-center"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find(text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find('.field--name-field-cta-btn-style').hide();
          $(ui_dialog).find('.field--name-field-cta-btn-size').hide();
          $(ui_dialog).find('.field--name-field-cta-btn-icon').hide();
          $(ui_dialog).find('.field--name-field-button-type').hide();
          $(ui_dialog).find(field_eyebrow).unwrap('div').wrap('<div style="display: none;" />');
          clearInterval(heroProduct);
        }
      }, 100); // check every 100ms
    }

     // When editing the hero node from inside product category
     if($('form.node-product-category-form').length > 0 || $('form.node-product-category-edit-form').length > 0){
      /* if hero already added then hide from details in block */
      var field_eyebrow_label = 'div.field--name-field-eyebrow';
      if ($(field_eyebrow_label).length) {
        $(field_eyebrow_label).unwrap('div').wrap('<div style="display: none;" />');
      }
      
      var heroProductCategory = setInterval(function () {
        var ui_dialog = '.ui-dialog form.node-hero-edit-form';
        var select_height = 'select[name="field_height_modifier"]';
        var text_align = 'select[name="field_text_alignment"]';
        var field_eyebrow = 'div[data-drupal-selector="edit-field-eyebrow-wrapper"]';

        if ($(ui_dialog).length) {
          $(ui_dialog).find(select_height + ' option[value="_none"]').remove();
          $(ui_dialog).find(select_height + ' option[value="--height-tall"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find(text_align + ' option[value="--align-image-right"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find(text_align + ' option[value="--align-image-right-large"]').unwrap('div').wrap('<div style="display: none;" />');
          $(ui_dialog).find('.field--name-field-cta-btn-style').hide();
          $(ui_dialog).find('.field--name-field-cta-btn-size').hide();
          $(ui_dialog).find('.field--name-field-cta-btn-icon').hide();
          $(ui_dialog).find('.field--name-field-button-type').hide();
          $(ui_dialog).find(field_eyebrow).unwrap('div').wrap('<div style="display: none;" />');
          clearInterval(heroProductCategory);
        }
      }, 100); // check every 100ms
    }
      function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(window.location.href);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
      };
      
      if (node_path !== 'undefined' || drupalSettings.content_type !== 'undefined') {
        if (node_path === '/node/add/product_category' || node_path === '/node/add/product' || drupalSettings.content_type === 'product' || drupalSettings.content_type === 'product_category' ) {
              var cta_link = '#edit-inline-entity-form-field-cmp-cta-0-subform-field-link-0-uri';
              if ($(cta_link).length) {
                $(cta_link).val('<front>');
              }
              var link_text = '#edit-inline-entity-form-field-cmp-cta-0-subform-field-link-0-title';
              if ($(link_text).length) {
                $(link_text).val('Talk To An Expert');
              }
              $("#edit-inline-entity-form-field-cmp-cta-0-subform-field-cta-btn-type option:contains(Talk to An Expert)").attr('selected', 'selected');
              var hero_entity_form = "#entity-browser-hero-entity-browser-form";
              $('#entity-browser-hero-entity-browser-form').find('#edit-submit').click(function(){
              $(hero_entity_form).submit(function(e){
                if($('#edit-inline-entity-form-field-cmp-image-entity-browser-entity-browser').length > 0){
                  if((document.querySelector('.image-form-submit-error')) == null ){
                    $('#edit-inline-entity-form-field-height-modifier-wrapper').after('<div class="form-item--error-message  image-form-submit-error"><strong>Please submit the image form first</strong></div>');
                  }
                  e.preventDefault();
                }
              });
              });
            }
            
          }
    }
  };
})(jQuery, Drupal, drupalSettings);
