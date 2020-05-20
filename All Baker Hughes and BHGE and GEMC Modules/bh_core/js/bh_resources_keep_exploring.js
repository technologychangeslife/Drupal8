(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.bhResourcesKeepExploring = {
      attach: function (context, settings) {
        var checkbox = $('.field--name-field-resources-keep-exploring .js-form-type-checkbox input[type="checkbox"]');
        if(checkbox.is(":checked")){
          $('.field--name-field-keep-exploring-url').show();
        }else{
          $('.field--name-field-keep-exploring-url').hide();
        }
        $('.field--name-field-resources-keep-exploring .js-form-type-checkbox input[type="checkbox"]').on('change',function(){
          if(this.checked){
            $('.field--name-field-keep-exploring-url').show();
          }else{
            $('.field--name-field-keep-exploring-url').hide();
          }
        })
        // Keep Exploring Exposed Filter
        if($('.views-exposed-form').length > 0){
          var selectedContent = $(".form-item-type select.form-select").children("option:selected").val();
          if((selectedContent == "All") || (selectedContent == "case_study")){
            $(".form-item-field-resources-keep-exploring-value select[name='field_resources_keep_exploring_value']").val("All");
            $(".form-item-field-resources-keep-exploring-value").hide();
            $(".form-item-field-article-type-target-id").hide();
          }
          if((selectedContent == "resources")){
            $(".form-item-field-resources-keep-exploring-value").show();
            $(".form-item-field-article-type-target-id").hide();
          }
          if(selectedContent == "article"){
            $(".form-item-field-article-type-target-id").show();
            $(".form-item-field-resources-keep-exploring-value").hide();
          }
          $(".form-item-type select.form-select").on('change',function(){
            var selected = $(".form-item-type select.form-select").children("option:selected").val()
            if((selected == "All") || (selected == "case_study")){
              $(".form-item-field-resources-keep-exploring-value select[name=field_resources_keep_exploring_value] option[value='All']").prop('selected',true);
              $(".form-item-field-article-type-target-id select[name=field_article_type_target_id] option[value='All']").prop('selected',true);
              $(".form-item-field-resources-keep-exploring-value").hide();
              $(".form-item-field-article-type-target-id").hide();
            }
            if((selected == "resources")){
              $(".form-item-field-article-type-target-id select[name=field_article_type_target_id] option[value='All']").prop('selected',true);
              $(".form-item-field-resources-keep-exploring-value").show();
              $(".form-item-field-article-type-target-id").hide();
            }
            if((selected == "article")){
              $(".form-item-field-resources-keep-exploring-value select[name=field_resources_keep_exploring_value] option[value='All']").prop('selected',true);
              $(".form-item-field-resources-keep-exploring-value").hide();
              $(".form-item-field-article-type-target-id").show();
            }
        });
        }
    }
  };
})(jQuery, Drupal, drupalSettings);