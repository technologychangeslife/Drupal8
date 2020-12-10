;(function ($) {
  'use strict';

  $(function () {
    $.ajax('/cubic-nyse-quote.json').done(function (data) {
      if (!data.formatted_amount) {
        return;
      }

      var $ticker = $('.block-cubic-nyseticker-block .ticker'),
        $amount = $('.amount strong', $ticker),
        $change = $('.change', $ticker),
        $change_amount = $('strong', $change);

      $amount.text(data.formatted_amount);
      $change_amount.text(data.change);
      $change.attr('class', 'change change-' + data.change_direction);

      if ($ticker.is(':hidden')) {
        $ticker.removeClass('hide');
      }
    });
  });
})(jQuery);
