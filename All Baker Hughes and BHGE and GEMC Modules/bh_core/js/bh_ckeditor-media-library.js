(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhCKEditorMediaLibrary = {
      attach: function (context, settings) {
        $(document).ready(function(){
          if($('.media-library-widget-modal').length > 0){
            // check if class " field--name-field-media-file " exists
            if($('.media-library-views-form .media-library-views-form__rows .media-library-item--grid .media-library-item__click-to-select-trigger .media-library-item__content .media-library-item__preview .field--name-field-media-file').length >0 ){
              // add custom class to distinguish modal
              $('.media-library-widget-modal').addClass('custom-media-library');
              // hide grid view and display table
              if($('.view-display-id-widget').length >0){
                $('.view-content', this).hide();
                $('.pager',this).hide();
                $('.media-library-view .view-header .views-display-link-widget').removeClass('is-active');
                $('.media-library-view .view-header').hide();
              }
              $('.media-library-view .view-header .views-display-link-widget_table').trigger('click');
            }
            // hide header options
            if($('.custom-media-library').length > 0){
                $('.view-header',this).hide();
            }
          }
        });   
    }
  };
})(jQuery, Drupal, drupalSettings);