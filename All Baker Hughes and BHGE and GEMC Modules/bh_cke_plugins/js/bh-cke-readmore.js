/**
 * @file
 * CKEditor Read more functionality.
 */

(function ($) {
  'use strict';
  Drupal.behaviors.ckeditorReadmore = {
    attach: function (context, settings) {
      var $ckeditorReadmore = $('.ckeditor-readmore');
      if ($ckeditorReadmore.length > 0) {
        $ckeditorReadmore
          .once()
          .wrap('<div class="ckeditor-readmore-wrapper"></div>')
          .parent()
          .prepend('<div id="bh-full-html-show-more-id" class="bh-full-html__pager-holder bh-full-html-show-more">' +
            '<ul class="js-pager__items pager">' +
            '<li class="pager__item field_cmp_cta">' +
            ' <button class="ckeditor-readmore-btn button button--secondary">Show More</button>' +
            ' </li>' +
            '</ul>' +
            '</div>')
          .append('<div class="bh-full-html__pager-holder bh-full-html-show-less">' +
            '<ul class="js-pager__items pager">' +
            '<li class="pager__item field_cmp_cta">' +
            ' <button class="readless button button--secondary">Show Less</button>' +
            ' </li>' +
            '</ul>' +
            '</div>');
        var check_admin = $('body').hasClass('adminimal-admin-toolbar');
        $(document).ready(function () {
          /* $(".ckeditor-readmore").html(function (i, html) {
            return html.replace(/&nbsp;/, '');
          }); */

          $(".ckeditor-readmore").each(function () {
            var arr = $(this).text();
            if (arr == '') {
              $(this).parent().find('.ckeditor-readmore-btn').hide();
            }
          });
        });

        $('body').once('ckeditorReadmoreToggleEvent').on('click', '.ckeditor-readmore-btn', function (ev) {
          $(this).closest('div.bh-full-html-show-more').siblings('div.ckeditor-readmore').slideToggle();
          $(this).closest('div.bh-full-html-show-more').siblings('div.bh-full-html-show-less').css("display", "flex");
          $(this).closest('div.bh-full-html-show-more').css("display", "none");
        });


        $("body .ckeditor-readmore-wrapper").off('click').on('click', '.readless', function (ev) {

          $(this).closest('div.bh-full-html-show-less').siblings('div.ckeditor-readmore').slideToggle('slow');

          $(this).closest('div.bh-full-html-show-less').siblings('div.bh-full-html-show-more').css("display", "flex");
          if (check_admin) {
            $('html, body').animate({
              scrollTop: $(".ckeditor-readmore-wrapper #bh-full-html-show-more-id").offset().top - 200
            }, 'slow');
          } else {
            $('html, body').animate({
              scrollTop: $(".ckeditor-readmore-wrapper #bh-full-html-show-more-id").offset().top - 180
            }, 'slow');
          }
          $(this).closest('div.bh-full-html-show-less').css("display", "none");

        });
      }
    }
  };
})(jQuery);