(function ($) {
    "use strict";

    Drupal.behaviors.bhExploreCategoryExpandCollapse = {
        attach: function (context, settings) {

            $("input[name='expand_collapse_check']:checkbox").change(function() {
                if($(this).is(':checked')) {
                    $('.bh-category-explorer--expand').show();
                }
                else{
                    $('.bh-category-explorer--expand').hide();
                }
            });
        }
    }

})(jQuery);