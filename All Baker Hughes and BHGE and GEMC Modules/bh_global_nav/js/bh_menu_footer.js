/*globals Drupal:false ,jQuery:false */
(function ($, window, Drupal) {
    "use strict";
    $(document).ready(function () {

        // Mobile view code
        var menu = $('#block-megafooter');
        var firstStage = $('ul.bh-menu', menu);
        var firstLink = $('> li > a', firstStage);
        var secondStage = $('> li > ul', firstStage);
        var secondLink = $('> li > a', secondStage);

        var check_admin = $('body').hasClass('adminimal-admin-toolbar');

        // Fireofox - Check if browser is firefox
        var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

        // IE - Check if browser is IE -- Start
        var ua = window.navigator.userAgent;
        var isIE = /MSIE|Trident/.test(ua);
        // IE - Check if browser is IE -- End


        firstLink.click(function (e) {
            e.preventDefault();

            if (window.outerWidth < 768) {

                var parent = $(this).parent();

                parent.find('> ul').toggle();
                parent.find('> a').toggleClass('color-animation');

            }
        });

        /*firstLink.parent().click(function (e) {
           console.log("parent ashshgdd sf g");
           if(window.outerWidth < 768){
              //var sublinks = $(this).children("ul");
              $(this).closest('.menu__item--expanded').find('> ul').toggle();
               //firstLink.siblings().toggle();
           }
       });*/

        //Increase mega footer's min-height dynamically as soon as no. of links bypass threshold value 6
        if (window.outerWidth >= 768) {
            var maxFooterDefault = 6; // Default threshold for max links within secondStage
            var megaFooterMinHeight = 268;

            var footerCol1Size = firstStage.find('> li:first').find('> ul > li').length;
            var footerCol2Size = firstStage.find('> li:nth-child(2)').find('> ul > li').length;
            var footerCol3Size = firstStage.find('> li:nth-child(3)').find('> ul > li').length;
            var footerCol4Size = firstStage.find('> li:last').find('> ul > li').length;

            var footerMaxColSize = Math.max(footerCol1Size, footerCol2Size, footerCol3Size, footerCol4Size);

            //console.log(footerMaxColSize);

            if (footerMaxColSize > maxFooterDefault) {

                var additionalLinks = footerMaxColSize - maxFooterDefault;

                megaFooterMinHeight = megaFooterMinHeight + (30 * (additionalLinks));

                menu.css("min-height", megaFooterMinHeight + "px");
            }
        }

    });

})(jQuery, window, Drupal);