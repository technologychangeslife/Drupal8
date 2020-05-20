/*globals Drupal:false ,jQuery:false */
(function ($, window, Drupal) {
    "use strict";
    $(document).ready(function () {

        // PART-1: Mobile view (logo+ search + hamburger) + Desktop view(logo+ meganav + search)
        var menu = $('#block-mainmeganavigation');
        var firstStage = $('ul.menu-level-0', menu);
        var firstLink = $('> li > a', firstStage);
        var secondStage = $('ul.menu-level-1', menu);
        var secondLink = $('> li > a', secondStage);
        var thirdStage = $('ul.menu-level-2', menu);
        var thirdLink = $('> li > a', thirdStage);

        var parentLayout = $('.layout-mega-navigation');
        var check_admin = $('body').hasClass('adminimal-admin-toolbar');

        var winScreenHeight = screen.height;
        var globalNavMobHeight = winScreenHeight - 65;

        // Fireofox - Check if browser is firefox
        var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

        // IE - Check if browser is IE -- Start
        var ua = window.navigator.userAgent;
        var isIE = /MSIE|Trident/.test(ua);
        // IE - Check if browser is IE -- End

        // Default menu hover state(black logo + white background) on all the pages except the page using HERO Banner
        if (!($(".bh-hero").length > 0)) {
            parentLayout.addClass('default-hover-state');
        }
        // Default menu hover state(black logo + white background) for pages using image right align hero
        if (($(".bh-hero-scrolled").length > 0) || ($(".layout-builder-form").length > 0)) {
            parentLayout.addClass('default-hover-state');

        }

        $(window).scroll(function (e) {
            // Get the position of the location where the scroller starts.
            var scroller_anchor = $(".meganav-scroller-anchor").offset().top;

            // Check if the user has scrolled and the current position is after the scroller start location and if its not already fixed at the top
            if ($(this).scrollTop() > scroller_anchor && parentLayout.css('position') != 'fixed')
            {    // Change the CSS of the scroller to highlight it and fix it at the top of the screen.

                parentLayout.css("position", "fixed");

                if(check_admin && window.outerWidth >= 1024){
                    parentLayout.css("top","74px");
                }else{
                    parentLayout.css("top","unset");
                }
                parentLayout.addClass('mega-nav-hover-scroll');
                // Changing the height of the scroller anchor to that of scroller so that there is no change in the overall height of the page.
                $('.meganav-scroller-anchor').css('height', '90px');
            }
            else if ($(this).scrollTop() <= scroller_anchor && parentLayout.css('position') != 'relative')
            {    // If the user has scrolled back to the location above the scroller anchor place it back into the content.

                parentLayout.removeClass('mega-nav-hover-scroll');

                // Change the height of the scroller anchor to 0 and now we will be adding the scroller back to the content.
                $('.meganav-scroller-anchor').css('height', '0px');

                // Change the CSS and put it back to its original position.
                parentLayout.css({
                    'position': 'relative',
                    'top': 'unset'
                });
            }
            // IE Code on Scroll -- Start
            if(isIE){
                if ($(this).scrollTop() > scroller_anchor){
                    if(check_admin && window.outerWidth >= 1024){

                        if($('#block-announcementbanner').length > 0){
                                if((window.outerWidth >= 1024) && (window.outerWidth < 1148) ){
                                    var layoutTabsHeight = $('.layout-tabs').css('height');
                                    parentLayout.css("top",layoutTabsHeight);
                                }else{
                                    parentLayout.css("top",'80px');
                                }
                        }
                        if($('#block-announcementbanner').length == 0){
                            parentLayout.css("top","74px");
                        }

                    }
                    else{
                        parentLayout.css("top","0px");
                    }
                    parentLayout.addClass('mega-nav-hover-scroll');
                }
                else if($(this).scrollTop() <= scroller_anchor){
                    parentLayout.removeClass('mega-nav-hover-scroll');
                    $('.meganav-scroller-anchor').css('height', '0px');
                    if(check_admin){
                        if($('#block-announcementbanner').length > 0){
                            var bannerHeight = $('#block-announcementbanner').height();
                            if(bannerHeight <= 50){
                                parentLayout.css({
                                    'position': 'fixed',
                                    'top': '210px'
                                });
                            }else{
                                if((window.outerWidth >= 1024) && (window.outerWidth < 1148) ){
                                    parentLayout.css("top",'0px');
                                }else{
                                    var newTopVal = ( 210 + (bannerHeight -50));
                                    parentLayout.css({
                                        'position': 'fixed',
                                        'top': newTopVal+'px'
                                    });
                                }
                            }

                        }
                        if($('#block-announcementbanner').length == 0){
                            parentLayout.css({
                                'position': 'fixed',
                                'top': '160px'
                            });
                        }
                    }

                }
            }
            // IE Code on Scroll -- End

        });

        firstStage.find('> li:first').addClass('main-featured-capabilities');
        firstStage.find('> li:nth-child(2)').addClass('main-product-services');
        //firstStage.find('> li:last').addClass('main-company');
        firstStage.find('> li:nth-child(3)').addClass('main-company');
        firstStage.find('> li > .menu-dropdown').wrapInner('<div class="mega-container"></div>');

        parentLayout.hover(function () {
            //parentLayout.toggleClass('mega-nav-hover');
            parentLayout.addClass('mega-nav-hover');

        }, function(){
            parentLayout.removeClass('mega-nav-hover');


        });

        //Featured-capabilities: Add the icon within menu-level-1 link element
        if (firstStage.find('> li').hasClass('main-featured-capabilities')) {
            menu.find('.main-featured-capabilities').find('.menu-level-1').find('> .menu-item').each(function(key, item) {
                var selfItem = $(item);
                //var icon = $('.field_mega_featured_icon', selfItem).clone();
                var icon = $('.field_mega_featured_icon', selfItem).detach();
                var link = selfItem.find('> a');

                //if (icon.length > 0) link.prepend('<div class="menu-icon">' + icon[0].innerHTML + '</div>');
                icon.prependTo(link);
            });
        }


        //Increase Product & Services min-height dynamically as soon as no. of links bypass threshold value 11
        if (firstStage.find('> li').hasClass('main-product-services')) {

            firstStage.find('.main-product-services').find(secondLink).click(function (e) {
                e.preventDefault();
            });


            var maxProductDefault = 11;
            var productMinHeight = 506;
            var productCardMinHeight = 453;


            var productCol1Size = firstStage.find('.main-product-services').find(secondStage).find('> li:first').find(thirdStage).find('> li').length;
            var productCol2Size = firstStage.find('.main-product-services').find(secondStage).find('> li:nth-child(2)').find(thirdStage).find('> li').length;
            var productCol3Size = firstStage.find('.main-product-services').find(secondStage).find('> li:nth-child(3)').find(thirdStage).find('> li').length;
            var productCol4Size = firstStage.find('.main-product-services').find(secondStage).find('> li:last').find(thirdStage).find('> li').length;

            var productMaxColSize = Math.max(productCol1Size, productCol2Size, productCol3Size, productCol4Size);

            if (productMaxColSize > maxProductDefault) {

                var additionalProductLinks = productMaxColSize - maxProductDefault;

                productMinHeight = productMinHeight + (34 * (additionalProductLinks));
                productCardMinHeight = productCardMinHeight + (34 * (additionalProductLinks));

                firstStage.find('.main-product-services').find(secondStage).css("min-height", productMinHeight + "px");
                firstStage.find('.main-product-services').find(secondStage).find('> li').find(thirdStage).find('> li').find('> .menu-dropdown-2 .content-navigation').css("min-height", productCardMinHeight + "px");
            }

            firstStage.find('.main-product-services').find(secondStage).find('> li').find(thirdStage).css("max-height", (productMinHeight - 129) + "px");
            //console.log((productMinHeight - 128));

            //var prodFirstLevelChilds = firstStage.find('.main-product-services').find(secondStage).find('> li').length;
            //console.log(prodFirstLevelChilds);
            //var prodFirstLevelWidth = calc(100% * (1/prodFirstLevelChilds));
            //firstStage.find('.main-product-services').find(secondStage).find('> li').css("width","calc(100% * (1/" + prodFirstLevelChilds + "))");

        }

        //Increase Company min-height dynamically as soon as no. of links bypass threshold value 7
        if (firstStage.find('> li').hasClass('main-company')) {


            firstStage.find('.main-company').find(secondLink).click(function (e) {
                e.preventDefault();
            });

            var maxCompanyDefault = 7;
            var companyMinHeight = 370;
            var companyCardMinHeight = 317;


            var companyCol1Size = firstStage.find('.main-company').find(secondStage).find('> li:first').find(thirdStage).find('> li').length;
            var companyCol2Size = firstStage.find('.main-company').find(secondStage).find('> li:nth-child(2)').find(thirdStage).find('> li').length;
            var companyCol3Size = firstStage.find('.main-company').find(secondStage).find('> li:nth-child(3)').find(thirdStage).find('> li').length;
            var companyCol4Size = firstStage.find('.main-company').find(secondStage).find('> li:last').find(thirdStage).find('> li').length;

            var companyMaxColSize = Math.max(companyCol1Size, companyCol2Size, companyCol3Size, companyCol4Size);

            if (companyMaxColSize > maxCompanyDefault) {

                var additionalCompanyLinks = companyMaxColSize - maxCompanyDefault;

                companyMinHeight = companyMinHeight + (34 * (additionalCompanyLinks));
                companyCardMinHeight = companyCardMinHeight + (34 * (additionalCompanyLinks));

                firstStage.find('.main-company').find(secondStage).css("min-height", companyMinHeight + "px");
                firstStage.find('.main-company').find(secondStage).find('> li').find(thirdStage).find('> li').find('> .menu-dropdown-2 .content-navigation').css("min-height", companyCardMinHeight + "px");
            }

            firstStage.find('.main-company').find(secondStage).find('> li').find(thirdStage).css("max-height", (companyMinHeight - 129) + "px");

        }


        // Menu level-2 link click.

        thirdLink.click(function (e) {
            var parent = $(this).parent();

            if (parent.hasClass('menu-item--expanded')) {
                e.preventDefault();

                parent.find('.menu-dropdown-2').show();
                parent.find('.menu-dropdown-2').css("top", "-128px");
                parent.find('.menu-dropdown-2').css("display", "block");

                if(isFirefox){
                    parent.find('.menu-dropdown-2').css("top", "-129px");
                }

                parent.find('.content-navigation').show();
                parent.find('.content-navigation').css("z-index", "400");

                //menu link color update for selected/clicked link and its siblings
                $(this).css("color", "#949494");
                parent.siblings().find('> a').css("color", "#02a783", "important");

                if (parent.find('> a').hasClass('is-sibling')) {
                    parent.find('> a').removeClass('is-sibling');
                    parent.find('> a').addClass("is-selected");
                    parent.siblings().find('> a').addClass("is-sibling");

                } else {
                    parent.find('> a').addClass("is-selected");
                    parent.siblings().find('> a').addClass("is-sibling");
                }


                parent.siblings().find('.content-navigation').css("z-index", "200");

                //parent.parents('.menu-item--expanded').hide();
                parent.parent().closest('.menu-item--expanded').siblings().hide();

                //look for menu-level-1
                parent.parent().closest('.menu-item--expanded').parent().find('.back-button').show();
                parent.parent().closest('.menu-item--expanded').parent().find('.back-button').css("color", "#02a783");

                //Add background color to selected link's column taking menu-level-1 as base
                parent.parent().closest('.menu-item--expanded').parent().css("background", "linear-gradient(90deg, #27272c 50%, #fff 0)");

                parent.parent().closest('.menu-item--expanded').find('> .menu-dropdown-1').css("background", "#27272c");
                parent.parent().closest('.menu-item--expanded').find('> .menu-dropdown-1').find('> .content-navigation').css("background", "#27272c");
                parent.parent().closest('.menu-item--expanded').find('> a').css("color", "#fff");

                firstStage.find('> li').find('> .menu-dropdown').css("background", "linear-gradient(90deg, #27272c 50%, #fff 0)");
                secondStage.find('> li').css("padding-top", "37px");


                parent.parent().closest('.menu-item--expanded').parent().find('.back-button').click(function (e) {
                    e.preventDefault();
                    parent.find('.menu-dropdown-2').hide();
                    parent.find('.content-navigation').hide();

                    //revert background color changes
                    parent.parent().closest('.menu-item--expanded').parent().css("background", "#fff");
                    parent.parent().closest('.menu-item--expanded').find('> .menu-dropdown-1').css("background", "#fff");
                    parent.parent().closest('.menu-item--expanded').find('> .menu-dropdown-1').find('> .content-navigation').css("background", "#fff");
                    parent.parent().closest('.menu-item--expanded').find('> a').css("color", "#013025");
                    firstStage.find('> li').find('> .menu-dropdown').css("background", "#fff");
                    parent.siblings().find('> a').css("color", "#949494");
                    //parent.css("width","unset");
                    parent.find('> a').removeClass('is-sibling');
                    parent.find('> a').removeClass('is-selected');
                    parent.siblings().find('> a').removeClass("is-sibling");

                    secondStage.find('> li').css("padding-top", "0px");


                    parent.parent().closest('.menu-item--expanded').siblings().show();

                    $(this).hide();


                });

            }


        });

        firstLink.hover(function (e) {

            e.preventDefault();
            thirdStage.find('> li').find('> .menu-dropdown-2').hide();
            thirdStage.find('> li').find('> .menu-dropdown-2').find('> .content-navigation').hide();
            thirdStage.find('> li').find('> a').css("color", "#949494");
            thirdStage.find('> li').find('> a').removeClass("is-sibling");
            thirdStage.siblings().find('> li').find('> a').removeClass("is-sibling");
            thirdStage.find('> li').find('> a').removeClass("is-selected");


            secondStage.css("background", "#fff");
            secondStage.find('> li').find('> .menu-dropdown-1').css("background", "#fff");
            secondStage.find('> li').find('> .menu-dropdown-1').find('> .content-navigation').css("background", "#fff");
            secondStage.find('> li').find('> a').css("color", "#013025");
            firstStage.find('> li').find('> .menu-dropdown').css("background", "#fff");
            secondStage.find('> li').css("padding-top", "0px");

            secondStage.find('.back-button').hide();

            secondStage.find('> li').show();


        });

        firstLink.click(function (e) {
            e.preventDefault();

        });

        menu.click(function (event) {
            event.stopPropagation();
        });


        // PART-2: Mobile view on hamburger click (meganav + utlity)

        var menuMobile = $('#block-mobile-mainmeganavigation');
        var firstStageMobile = $('ul.menu-level-0', menuMobile);
        var firstLinkMobile = $('> li > a', firstStageMobile);
        var secondStageMobile = $('ul.menu-level-1', menuMobile);
        var secondLinkMobile = $('> li > a', secondStageMobile);
        var thirdStageMobile = $('ul.menu-level-2', menuMobile);
        var thirdLinkMobile = $('> li > a', thirdStageMobile);

        var hamburgerMobile = $('.hamburger', parentLayout);

        firstStageMobile.find('> li:first').addClass('main-featured-capabilities');
        firstStageMobile.find('> li:nth-child(2)').addClass('main-product-services');
        //firstStage.find('> li:last').addClass('main-company');
        firstStageMobile.find('> li:nth-child(3)').addClass('main-company');


        firstLinkMobile.click(function (e) {
            var parent = $(this).parent();

            if (parent.hasClass('menu-item--expanded')) {
                e.preventDefault();

                parentLayout.toggleClass("menu-color-animation-flip");

                parent.toggleClass("is-parent-expanded");
                parent.find('> a').toggleClass("is-selected");
                parent.find('> .menu-dropdown-0').toggle();
                parent.siblings().toggle();

                parent.parent().siblings().toggle();

            }


        });

        secondLinkMobile.click(function (e) {
            var parent = $(this).parent();

            if (parent.hasClass('menu-item--expanded')) {
                e.preventDefault();

                firstLinkMobile.toggle();
                firstLinkMobile.parent().toggleClass("is-parent-expanded");
                firstLinkMobile.parent().parent().toggleClass("is-grand-parent-expanded");

                parent.toggleClass("is-parent-expanded");
                parent.find('> a').toggleClass("is-selected");
                parent.find('.menu-level-2').toggle();
                parent.siblings().toggle();


            }


        });


        thirdLinkMobile.click(function (e) {
            var parent = $(this).parent();

            if (parent.hasClass('menu-item--expanded')) {
                e.preventDefault();

                secondLinkMobile.toggle();

                parent.toggleClass("is-parent-expanded");
                parent.parent().toggleClass("is-grand-parent-expanded");
                parent.find('> a').toggleClass("is-selected");
                parent.find('.menu-level-3').toggle();

                parent.siblings().toggle();

            }


        });


        hamburgerMobile.click(function (e) {
            //e.preventDefault();

            $("body").toggleClass('hide-page-scroll');

            $(this).toggleClass('is-active');
            parentLayout.find('.global-nav-mobile').css("height", globalNavMobHeight + "px");
            parentLayout.find('.global-nav-mobile').css("overflow-y", "scroll");
            parentLayout.find('.global-nav-mobile').css("overflow-x", "hidden");
            parentLayout.find('.global-nav-mobile').toggle();

            parentLayout.toggleClass("menu-postion");
            parentLayout.toggleClass("menu-color-animation");

            if (parentLayout.hasClass("menu-color-animation-flip")) {
                parentLayout.removeClass("menu-color-animation-flip");
            } else if (firstStageMobile.find('> li').hasClass('is-parent-expanded')) {
                parentLayout.addClass("menu-color-animation-flip");
            }

            // Close all the open levels before the hamburger is re-opened.
            if(thirdStageMobile.find('> li').hasClass("is-parent-expanded")){
                thirdStageMobile.find('> .is-parent-expanded > .is-selected').click();
                secondStageMobile.find('> .is-parent-expanded > .is-selected').click();
                firstStageMobile.find('> .is-parent-expanded > .is-selected').click();
                parentLayout.removeClass("menu-color-animation-flip");
            }
            if(secondStageMobile.find('> li').hasClass("is-parent-expanded")){
                secondStageMobile.find('> .is-parent-expanded > .is-selected').click();
                firstStageMobile.find('> .is-parent-expanded > .is-selected').click();
                parentLayout.removeClass("menu-color-animation-flip");
            }
            if(firstStageMobile.find('> li').hasClass("is-parent-expanded")){
                firstStageMobile.find('> .is-parent-expanded > .is-selected').click();
                parentLayout.removeClass("menu-color-animation-flip");

            }

        });


    });

})(jQuery, window, Drupal);