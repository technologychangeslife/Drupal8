(function ($) {
  'use strict';
  Drupal.behaviors.ckeditorDownlaodCta = {
    attach: function (context, settings) {
      var $ckeditorDownlaodCta = $('.bh-field-media');
      if ($('.bh-field-media').length > 0) {
        $('.bh-field-media').each(function(val){
          var text = $(this).text();
          $(this).contents().filter(function () {
            return this.nodeType === Node.TEXT_NODE;
          }).remove();
          $(this)
              .once()
              .append('<div class="ckeditor-download-cta">' +
                  '<a class="cta-new cta-new--primary cta-new--text-with-icon cta-new--default cta-new-icon--download" data-module="Cta" href="' + text + '" target="" rel="" aria-label="Download">' +
                  '<span class="cta-new__icon cta-new__icon--before">' +
                  '<svg width="15" height="15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.95 2.967l.002 6.018-2.855-2.747a.5.5 0 00-.697.003l-.049.049a.5.5 0 00.007.72l3.825 3.611a.5.5 0 00.687 0 .502.502 0 00.07-.057l3.638-3.552a.5.5 0 00-.005-.72l-.035-.034a.5.5 0 00-.696.006L8 9.071l.05-6.1a.5.5 0 00-.5-.504h-.1a.5.5 0 00-.5.5zm-3.71 8.576a.5.5 0 00-.5.5v.098a.5.5 0 00.5.5h8.52a.5.5 0 00.5-.5v-.1a.5.5 0 00-.5-.5l-8.52.002z" fill="#02A783"></path></svg>' +
                  '</span>' +
                  '<span class="cta-new__text">Download</span>' +
                  '<span class="cta-new__icon cta-new__icon--after">' +
                  '<svg width="15" height="15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.95 2.967l.002 6.018-2.855-2.747a.5.5 0 00-.697.003l-.049.049a.5.5 0 00.007.72l3.825 3.611a.5.5 0 00.687 0 .502.502 0 00.07-.057l3.638-3.552a.5.5 0 00-.005-.72l-.035-.034a.5.5 0 00-.696.006L8 9.071l.05-6.1a.5.5 0 00-.5-.504h-.1a.5.5 0 00-.5.5zm-3.71 8.576a.5.5 0 00-.5.5v.098a.5.5 0 00.5.5h8.52a.5.5 0 00.5-.5v-.1a.5.5 0 00-.5-.5l-8.52.002z" fill="#02A783"></path></svg>' +
                  '</span>' +
                  '</a>' +
                  '</div>');
        })
      }
    }
  }
})(jQuery);