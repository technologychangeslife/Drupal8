/*globals Drupal:ture ,jQuery:false */
(function ($, Drupal, drupalSettings) {
  var all_cat_data = [];
  var opened_cat_data = [];
  
  Drupal.behaviors.bhProductServicesBehavior = {
    attach: function (context, settings) {
      if($('[data-drupal-selector="edit-field-parent-categories-target-id-2"]').length > 0)
      {
        /*Get all child category list data in single ajax call*/
        if(all_cat_data.length <= 0){
          getAllChildData();
        }
        if(all_cat_data.length > 0){
          /* apply carat class on category if has children */
          checkIfHasChild();

          /* after ajax call preserve already selected data */
          preserveSelectedData();
        }

        /* Trigger for category filter apply button dropdown */
        $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] #category-filter-apply',context).click(function(){
          var selected_cat = [];
          $('#field_parent_categories_child_list').val('');
          $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input:checkbox:checked').each(function(){
                selected_cat.push($(this).val());
          });
          if(selected_cat.length > 0)
          {
            var selected_cat_list = selected_cat.join(',');
            $('#field_parent_categories_child_list').val(selected_cat_list);
          }
          $('div.product-services-filters input.js-form-submit').trigger('click');
        });

        /* Trgger for category filter reset button dropdown */
        $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] #category-filter-reset',context).click(function(){
          $('#field_parent_categories_child_list').val('');
          $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input:checkbox:checked').each(function(){
                $(this).prop("checked", false);
          });
          $('div.product-services-filters input.js-form-submit').trigger('click');
        });

        /* Trgger for sort dropdown */
        $('div.product-services-filters__foot div.drop-wrapper.drop-wrapper-sort input[name="sort_by"]',context).change(function(){
          $('div.product-services-filters input.js-form-submit').trigger('click');
        });

        /* select deselect all childrens if parent is selected */
        var selector = "edit-field-parent-categories-target-id-2-";
        $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input.form-checkbox:checkbox',context).on('click',function(){
          var cat_id = $(this).val();
          var child_selector = '#product-cat-child-'+cat_id;
          if(($(child_selector).html()).length <= 0){
            replaceChildHtml(cat_id)
          }
          if($(child_selector+' input[type=checkbox]').length > 0)
          {
            if($(this).prop("checked"))
            {
              $(child_selector+' input[type=checkbox]').each(function(){
                $(this).prop('checked',true);
              });
              $(child_selector).slideDown('slow');
              $('span[data-key="'+cat_id+'"]').addClass('carat-up');
              $('span[data-key="'+cat_id+'"]').closest('.parent-checkbox-wrapper').addClass('parent-dropdown-open');
            }else{
              $(child_selector+' input[type=checkbox]').each(function(){
                $(this).prop('checked',false);
              });
              $(child_selector).slideUp('slow');
              $('span[data-key="'+cat_id+'"]').removeClass('carat-up');
              $('span[data-key="'+cat_id+'"]').closest('.parent-checkbox-wrapper').removeClass('parent-dropdown-open');
            }
          }     
        });
        /* select or deselect parent on child selection */
        $('[data-drupal-selector="edit-field-parent-categories-target-id-2"]',context).delegate('input[name^="product-cat-child-list"]','click',function() {
          var parentDiv = $(this).closest('.product-category-child-wrapper');
          if(parentDiv.length > 0)
          {
            var parentDivID = parentDiv.attr('data-cat-id');

            var cb_count = parentDiv.find('input[type=checkbox]').length;
            var cb_checked_count = parentDiv.find('input:checkbox:checked').length;

            if(cb_count === cb_checked_count)
            {
              $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input[type=checkbox][value="'+parentDivID+'"]').prop("checked",true);
            }else{
               $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input[type=checkbox][value="'+parentDivID+'"]').prop("checked",false);
            }
          }
        });
      }

      /* function with ajax call to get all child category data */
      function getAllChildData()
      {
        var ajax_url = "/ajax/get_product_cat_child_data";
        $.ajax({
          url: ajax_url, 
          method :'GET',
          dataType: "json", 
          success: function(result){
            if(result.length <= 0)
            return false;
          
            for(var key in result)
            {
              if (result.hasOwnProperty(key)) {
                all_cat_data[key] = result[key];
              }
            }
            /* apply carat class on category if has children */
            checkIfHasChild();
          }
        });
        return false;
      }

      /* function to get all childres of sigle category */
      function getChilds(){       
        var current = $(this);
        var cat_id = current.attr('data-key');
        if($("#product-cat-child-"+cat_id).length > 0){
          replaceChildHtml(cat_id);

          $("#product-cat-child-"+cat_id).slideToggle('slow');
          $(this).toggleClass('carat-up');
          $(this).closest('.parent-checkbox-wrapper').toggleClass('parent-dropdown-open');
        }
      }
      /* replace dependant dropdown structure for single category*/
      function replaceChildHtml(cat_id)
      {
          var child_html = $("#product-cat-child-"+cat_id).html();
          if(all_cat_data.length > 0 && cat_id in all_cat_data && child_html.length <= 0)
          {
              $("#product-cat-child-"+cat_id).html(all_cat_data[cat_id]);
              opened_cat_data[cat_id] = all_cat_data[cat_id]; 
          }
      }
      
      /* function to add carrot class to categories having childrens */
      function checkIfHasChild()
      {
        if(all_cat_data.length > 0) {
          
          for(var key in all_cat_data)
          {
            var checkbox_div_class = '.form-item-field-parent-categories-target-id-2-'+key;
            var checkbox_div = $('[data-drupal-selector="edit-field-parent-categories-target-id-2"]').find(checkbox_div_class);
            if(checkbox_div.length > 0)
            {
              checkbox_div.find('[data-drupal-selector="edit-field-parent-categories-target-id-2-'+key+'"]').addClass('child-carat');
              if(checkbox_div.find('span.carat').length <=0){
                var span_carat = $('<span class="carat" data-key="'+key+'"></span>');
                span_carat.bind("click", getChilds);
                checkbox_div.append(span_carat);
              }
              var checkbox_child_div_id = 'product-cat-child-'+key;
              if($('[data-drupal-selector="edit-field-parent-categories-target-id-2"]').find('#'+checkbox_child_div_id).length <= 0){
                checkbox_div.after('<div id="'+checkbox_child_div_id+'" class="product-category-child-wrapper" data-cat-id="'+key+'"></div>');
              }
            }
          }
        }

      }

      /* function to preserve selected data for ajax */
      function preserveSelectedData()
      {
        if(opened_cat_data.length > 0){
          for (var key in opened_cat_data){
            if(opened_cat_data.hasOwnProperty(key) && $("#product-cat-child-"+key).length > 0){
              $("#product-cat-child-"+key).html(opened_cat_data[key]);
            }
          }
        }
        var selected_cat = $('#field_parent_categories_child_list').val();
        if(selected_cat.length > 0)
        {
          var selected_cat_arr = selected_cat.split(',');
          for(var key in selected_cat_arr)
          {
            if (selected_cat_arr.hasOwnProperty(key)) {
              var selected_cb = $('[data-drupal-selector="edit-field-parent-categories-target-id-2"] input[type=checkbox][value="'+selected_cat_arr[key]+'"]');
              if(selected_cb.length > 0)
              {
                var childWrapper = selected_cb.closest('.product-category-child-wrapper');
                if(childWrapper.length > 0)
                {
                  childWrapper.css('display','block');
                  var parent_cat_id = childWrapper.attr('data-cat-id');
                  $('span[data-key="'+parent_cat_id+'"]').addClass('carat-up');
                  $('span[data-key="'+parent_cat_id+'"]').closest('.parent-checkbox-wrapper').addClass('parent-dropdown-open');
                }
                selected_cb.prop("checked",true);
              }
            }
          }
        }
      }
      $('.product-services-grid .bh-product-services .bh-product-teaser-card__container .bh-product-teaser-card__color-card').each(function () {
        if($(this).parent('a.bh-product-teaser-card__color-card-url').length <= 0){
          var card_url = $(this).find('.bh-product-teaser-card__url').attr('href');
          $(this).wrap("<a href='"+card_url+"' class='bh-product-teaser-card__color-card-url'></a>");
        }
      });
    }
  }
})(jQuery, Drupal, drupalSettings);

