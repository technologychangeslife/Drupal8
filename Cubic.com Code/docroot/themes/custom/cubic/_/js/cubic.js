'use strict';
/* globals _, document, Drupal, Ellipsis, Foundation, history, jQuery, navigator, Rellax, window */
function hasTouch() {
  return ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0);
}

var cubic = {};



(function ($) {
  // var $window = $(window);
  // var $body = $('html, body');

  cubic = {
    bxSliders5050: [], // keep track of the sliders in 5050 areas so we can redraw them as needed
    slickSliders: [],

    init: function () {
      var $body = $('body');

      $(document).foundation();
      cubic.annualReportsSlider();
      cubic.downloadsDropdown();
      cubic.eventRequestMeetingForm();
      cubic.eventRecapSlider();
      cubic.testimonialsSlider();
      cubic.statsSlider();
      cubic.eventSchedules();
      cubic.exposedFormAutoSubmit();
      cubic.foundationEvents();
      cubic.gridImageSliders();
      cubic.hamburger();
      cubic.homeNewsSlider();
      // cubic.homepageVideos();
      // $(window).on('load resize', function () {
      //   cubic.homepageVideos();
      // });
      cubic.homepageVideoSliders();
      cubic.imageTextSliders();
      cubic.milestoneCarousel();
      cubic.milestoneSlider();
      cubic.milestoneTimelineParallax();
      cubic.imagesToBgs();
      cubic.mobileMenu();
      cubic.progressBarScroll();
      cubic.stickyTabsTimeline();// sticky
      cubic.searchToggle();
      cubic.slider5050();
      cubic.solutionsMenus();
      cubic.tables();
      cubic.tabsMightCollapse();

      if (!$body.hasClass('user-logged-in')) {
        cubic.smoothScrollJumpLinks();
        cubic.stickyEventJumpLinks();
        cubic.stickyTabs();
      //  cubic.stickyTabsTimeline();

        var parallaxedHeaders = {
          // '.page-node-type-milestone-decade article.node--type-milestone-decade': {},
          '.context-case-study-header .page-header-background': {},
          '.page-node-type-event .page-header-background': {},
          '.page-node-type-post .page-header-background': {},
          '.page-node-type-timeline .page-header-background': {},
          '.page-node-type-insight .page-header-background': {},
          '.page-node-type-solution .page-header-background': {},
          '.page-node-type-success-story .page-header-background': {}
        };

        for (var header in parallaxedHeaders) {
          console.log($(header + ' img'));
          if (parallaxedHeaders.hasOwnProperty(header)) {
            if ($(header + ' img').length) {
              var defaults = {
                round: false,
                speed: -4
              };
              new Rellax(header + ' img', Object.assign({}, defaults, parallaxedHeaders[header]));
            }
          }
        }
      }
    },


    /**************************************************************************************************************************/
    //Custom Progress indicator
    progressBarScroll : function () {

      //to check if it's in view port..
      $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
      };

      var $element;
      var $win = $(window);

      $(window).on('load resize scroll', function() {
        /***  test getting the percentage of viewed area.    ***/
        function percentageSeen ($element, currentTab, activeDecade) {
          var viewportHeight = $(window).height(),
            scrollTop = $win.scrollTop(),
            elementOffsetTop = $element.offset().top,
            elementHeight = $element.height();

          console.log('this is scrollTop: ' + elementOffsetTop);
          console.log('this is percentage: ' + elementHeight);

          var percentage;


          if (elementOffsetTop > (scrollTop + viewportHeight)) {
            console.log(0);

            return 0;
          } else if ((elementOffsetTop + elementHeight - 120 ) < scrollTop) {
            console.log(100);

          //trying to deactivate from here..

            currentTab = $('#tab-' + activeDecade);
            currentTab.removeClass(activeDecade + '-active active');
            currentTab.removeClass(activeDecade + '-active active');

            return 100;
          } else {
            var distance = (scrollTop + viewportHeight) - elementOffsetTop;

            percentage = distance / ((viewportHeight + elementHeight - 120) / 100);
            percentage = Math.round(percentage);
            console.log('this is scrollTop: ' + scrollTop);
            console.log('this is percentage: ' + percentage);

            return percentage;
          }
        }

        /**********************************************************************************************/

        // var activeDecade;

        //this is to check which decade is in the viewport
        //adds active state to the tab bar
        $('.node--type-milestone-decade').each(function() {

          var activeDecade = $(this).attr('id');
          var currentTab;

          if ($(this).isInViewport()) {
            currentTab = $('#tab-' + activeDecade);
            console.log('this is in currentTab ' + currentTab);
            console.log(activeDecade + ' is in viewPort');
            currentTab.addClass(activeDecade + '-active active');
            var activeDecadeID = '#' + activeDecade;
            $element = $('' + activeDecadeID);


            /***** this maybe moved to a function latter - it was already but needed to try current div only ***/
              //content height..

            // var headerHeight = $('header').height();
            // var menuHeight = $('.menu').height();
            //
            // console.log('this is headerHeight: ' + headerHeight);
            // console.log('this is menuHeight: ' + menuHeight);

            var activeDecadeProgressBar = '.' + activeDecade + '.progress-bar';

            console.log($element);

            $('' + activeDecadeProgressBar).css('width', percentageSeen($element, currentTab, activeDecade) +'%');

          } else {
            currentTab = $('#tab-' + activeDecade);
            currentTab.removeClass(activeDecade + '-active active');
          }
        });
      });
    },
    /**************************************************************************************************************************/

    //Custom Sticky Tabs for Timeline..
    stickyTabsTimeline: function () {
      //here goes the solution...
      $(window).on('load resize scroll', function() {
        var $tabsContainer = $('.paragraph--type--tabs-time-line-tabs');
        // var isPositionFixed = ($el.css('position') === 'fixed');
        console.log('this is scroll top: ');
        console.log($(this).scrollTop());


        var headerHeight = $('header').height();
        var menuHeight = $('.menu').height();
        var tabsHeight = $('.paragraph--type--tabs-time-line-tabs').height();

        console.log('this is headerHeight: ' + headerHeight);
        console.log('this is menuHeight: ' + menuHeight);
        console.log('this is tabsHeight: ' + tabsHeight);

        var reachHeight = headerHeight + 20;
        console.log('this is reachHeight: ' + reachHeight);


        if ($(this).scrollTop() >= reachHeight){
          $tabsContainer.addClass('fixed');
        }
        if ($(this).scrollTop() < reachHeight){
          $tabsContainer.removeClass('fixed');
        }
      });

    },

    //Milestone Timeline Parallax clear - not doing anything..
    milestoneTimelineParallax: function () {
      // not used
    },

    annualReportsSlider: function () {
      var $slider = $('.annual-reports-slider');
      if ($slider.length > 0) {
        $slider.slick({
          infinite: false,
          mobileFirst: true,
          nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
          prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
          slidesToShow: 1,
          responsive: [
            {
              breakpoint: 640,
              settings: {
                slidesToShow: 2
              }
            },
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3
              }
            },
            {
              breakpoint: 1440,
              settings: {
                slidesToShow: 4
              }
            }
          ]
        });
      }
    },

    downloadsDropdown: function () {
      $(':not(div.embedded-entity.entity--file) + div.embedded-entity.entity--file, * > div.embedded-entity.entity--file:first-of-type').each(function () {
        var $this = $(this);
        if ($this.nextUntil(':not(div.embedded-entity.entity--file)').length >= 1) {
          $this.nextUntil(':not(div.embedded-entity.entity--file)').addBack().wrapAll('<div class="downloads-wrapper"><div class="downloads">');
        }
      });


      var $downloadsWrapper  = $('.downloads-wrapper');
      $downloadsWrapper.on('click', function (event) {
          event.stopPropagation();
          event.stopImmediatePropagation();

          if($(this).hasClass('active'))
          {
            $(this).removeClass('active').addClass('hidden');
          } else {

            if($(this).hasClass('hidden')){
              $(this).removeClass('hidden').addClass('active');
            } else {
              $(this).addClass('active');
            }
          }
        });
    },

    eventRequestMeetingForm: function () {
      var $paragraph = $('.paragraph--type--people-grid');
      var $cta = $('> a.request-link', $paragraph);
      var $wrapper = $('.meeting-form', $paragraph);
      var $innerWrapper = $('> .meeting-form__wrapper', $wrapper);
      var $close = $('.icon--close', $wrapper);

      $cta.on('click', function (event) {
        event.preventDefault();
        $wrapper.addClass('active');
        $cta.addClass('active');
        $wrapper.removeClass('active').animate({'height': $innerWrapper.outerHeight()}, {
          'complete': function () {
            $wrapper.css({'height': 'auto'});
          }
        });
      });

      $close.on('click', function (event) {
        event.preventDefault();
        $wrapper.removeClass('active').animate({'height': 0});
        $cta.removeClass('active');
      });
    },

    eventRecapSlider: function () {
      var $paragraph = $('.paragraph--type--event-recap');
      var $sliders = $('.field--name-field-event-recap-slider-content', $paragraph);

      $sliders.each(function () {
        var $this = $(this);

        $this.slick({
          infinite: false,
          mobileFirst: true,
          nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
          prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
          slidesToShow: 1,
          responsive: [
            {
              breakpoint: 640,
              settings: {
                slidesToShow: 2
              }
            },
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3
              }
            }
          ]
        });
      });

      var ellipsis = new Ellipsis({debounce: 500});
      var elements = document.querySelectorAll('.paragraph--type--event-recap-slider-item .field--name-field-title');
      ellipsis.add(elements);
    },

    testimonialsSlider: function () {
      var $paragraph = $('.paragraph--type--testimonial-slider');
      var $sliders = $('.field--name-field-testimonial-slider-items', $paragraph);

      $sliders.each(function () {
        var $this = $(this);

        if($this.parents('.one-column-testimonial-slider').length) {
          $this.slick({
            infinite: false,
            mobileFirst: true,
            speed: 1000,
            nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
            prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
            slidesToShow: 1,
            responsive: [
              {
                breakpoint: 640,
                settings: {
                  slidesToShow: 1
                }
              }
            ]
          });
        } else {
          $this.slick({
            infinite: false,
            mobileFirst: true,
            //variableWidth: true,
            nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
            prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
            slidesToShow: 1,
            responsive: [
              {
                breakpoint: 790,
                settings: {
                  slidesToShow: 2
                }
              },
              {
                breakpoint: 1360,
                settings: {
                  slidesToShow: 3
                }
              }
            ]
          });
        }
      });

      var ellipsis = new Ellipsis({debounce: 500});
      var elements = document.querySelectorAll('.field--name-field-testimonial-slider-items .field--name-field-title');
      ellipsis.add(elements);
    },

    statsSlider: function () {
      var $paragraph = $('.paragraph--type--statistic-slider');
      var $sliders = $('.field--name-field-statistic-slider-items', $paragraph);

      $sliders.each(function () {
        var $this = $(this);

        $this.slick({
          infinite: false,
          mobileFirst: true,
          //variableWidth: true,
          speed: 500,
          nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
          prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
          slidesToShow: 1,
          responsive: [
            {
              breakpoint: 790,
              settings: {
                slidesToShow: 2
              }
            },
            {
              breakpoint: 1360,
              settings: {
                slidesToShow: 3
              }
            }
          ]
        });
      });

      var ellipsis = new Ellipsis({debounce: 500});
      var elements = document.querySelectorAll('.field--name-field-testimonial-slider-items .field--name-field-title');
      ellipsis.add(elements);
    },

    eventSchedules: function () {
      var $paragraphs = $('.paragraph--type--event-schedule');

      $paragraphs.each(function () {
        var dates = [];
        var $this = $(this);
        var $wrapper = $('.field--name-field-event-schedule-content', $this);
        var $rows = $('> .field__item', $wrapper);

        $rows.each(function () {
          var $this = $(this);
          dates.push($this.data('dates').split(' '));
        });

        if (_) {
          dates = _.unique(_.flatten(dates));
        }

        var $tabs = $('<ul class="schedule-tabs tabs">');

        for (var date in dates) {
          if (dates.hasOwnProperty(date)) {
            $tabs.append($('<li data-date="' + dates[date] + '">' + dates[date].replace('-', ' ') + '</li>'));
          }
        }

        $tabs.insertBefore($wrapper);
        $tabs.on('click', function (event) {
          event.preventDefault();
          var $tab = $(event.target);
          var date = $tab.data('date');
          $rows.hide();
          $rows.filter('[data-dates*="' + date + '"]').show();
          $tabs.find('li').removeClass('active');
          $tab.addClass('active');
        });

        $tabs.find('li:first').click();
      });
    },

    // Exposed filters that should auto-submit will have their buttons hidden,
    // but can be triggered with this function
    exposedFormAutoSubmit: function () {
      if ($('.views-exposed-form.auto-submit').length === 0) {
        return;
      }

      // most of this code borrowed from auto_submit.js in Better Exposed Filters module
      var ignoredKeyCodes = [
        16, // shift
        17, // ctrl
        18, // alt
        20, // caps lock
        33, // page up
        34, // page down
        35, // end
        36, // home
        37, // left arrow
        38, // up arrow
        39, // right arrow
        40, // down arrow
        9, // tab
        13, // enter
        27  // esc
      ];

      function triggerSubmit($target) {
        $target.closest('form').find('.js-form-submit').click();
      }

      // The change event bubbles so we only need to bind it to the outer form
      // in case of a full form, or a single element when specified explicitly.
      $(document).on('change keyup keypress', '.views-exposed-form.auto-submit', function (e) {
        var $target = $(e.target);

        // Don't submit on changes to excluded elements or a submit element.
        if ($target.is(':submit')) {
          return true;
        }
        // Use debounce to prevent excessive submits on text field changes.
        // Navigation key presses are ignored.
        else if ($target.is(':text:not(.hasDatepicker), textarea') && $.inArray(e.keyCode, ignoredKeyCodes) === -1) {
          Drupal.debounce(triggerSubmit, 500)($target);
        }
        // Only trigger submit if a change was the trigger (no keyup).
        else if (e.type === 'change') {
          triggerSubmit($target);
        }
      });
    },

    // Hook into Foundation events
    foundationEvents: function () {
      // keep columns equalized when used with tabs and accordions
      var $accordions = $('[data-accordion]');
      var $tabs = $('[data-tabs]');

      $accordions.has('[data-equalizer]').on('down.zf.accordion', function () {
        Foundation.reInit('equalizer');
      });

      $accordions.on('down.zf.accordion', function () {
        cubic.redrawBXSliders();
        cubic.redrawSlickSliders();
      });

      $tabs.has('[data-equalizer]').on('change.zf.tabs', function () {
        Foundation.reInit('equalizer');
      });

      $tabs.on('change.zf.tabs', cubic.redrawBXSliders);
    },

    // turn grids of images into sliders if there are too many
    gridImageSliders: function () {
      var $sliders = $('.field--name-field-grid-image.is-slider');
      if ($sliders.length > 0) {
        $sliders.bxSlider({
          minSlides: 2,
          maxSlides: 4,
          slideWidth: 250,
          slideMargin: 10,
          pager: false
        });
      }
    },

    hamburger: function () {
      var $menuContainer = $('#menus nav.menu--main');

      $('.hamburger').on('click', function (event) {
        event.preventDefault();

        var $this = $(this);

        // opening
        if (!$this.hasClass('open')) {
          $('body').addClass('menu-open');
          $this.removeClass('closed').addClass('open');
          $menuContainer.addClass('open');
        }
        // closing
        else {
          $('body').removeClass('menu-open');
          $this.removeClass('open').addClass('closed');
          $menuContainer.removeClass('open');
        }
      });
    },

    // makes the home page news area into a bxslider
    homeNewsSlider: function () {
      var $homeNews = $('#main-wrapper').find('.view-news.view-display-id-block_1 .view-content');
      if ($homeNews.length > 0) {
        $homeNews.bxSlider({
          minSlides: 1,
          maxSlides: 12,
          slideWidth: 300,
          slideMargin: 10,
          pager: false,
          infiniteLoop: false
        });
      }
    },

    homepageVideos: function () {
      var $videos = $('.node--type-slider > video');
      $videos.each(function () {
        var $this = $(this);
        if (window.outerWidth >= 640) {
          var video_ratio = $this.get(0).videoWidth / $this.get(0).videoHeight;
          var $parent = $this.parent();
          var max_width = $parent.width();
          var max_height = $parent.height();
          var parent_ratio = max_width / max_height;
          if (parent_ratio <= video_ratio) {
            $this.css({width: 'auto', height: max_height});
          } else {
            $this.css({width: max_width, height: 'auto'});
          }
        } else {
          $this.removeAttr('style');
        }
      });
    },

    homepageVideoSliders: function () {
      var prevSlide = 0;
      var $sliders = $('.view-homepage-slider');

      $sliders.each(function (event, item) {
        var $this = $(item);
        var $items = $('.view-content', $this);

        if ($('.views-row', $items).length > 1 && !$items.hasClass('slick-initialized')) {
          $items.slick({
            adaptiveHeight: true,
            arrows: false,
            dots: true,
            draggable: false,
            fade: true,
            infinite: false,
            pauseOnFocus: false,
            pauseOnHover: false,
            mobileFirst: true,
            slide: '.views-row',
            swipe: false,
            touchMove: false,
            responsive: [
              {
                breakpoint: 640,
                settings: {
                  adaptiveHeight: false
                }
              }
            ]
          });
        }

        $items.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
          prevSlide = currentSlide;
          var $beforeVideo = $('.views-row', $this).eq(nextSlide).find('video');
          if ($beforeVideo.length) {
            $beforeVideo.get(0).play();
          }
        });

        $items.on('afterChange', function () {
          var $afterVideo = $('.views-row', $this).eq(prevSlide).find('video');
          if ($afterVideo.length) {
            $afterVideo.get(0).pause();
          }
        });

        if ($('.views-row:first video', $this).length) {
          $('.views-row:first video', $this).get(0).play();
        }
      });
    },

    imageTextSliders: function () {
      var $sliders = $('.paragraph--type--image-and-text-slider');

      $sliders.each(function (event, item) {
        var $this = $(item);
        var $items = $('.field--name-field-image-text-slider-items', $this);

        $items.slick({
          adaptiveHeight: true,
          dots: true,
          infinite: false,
          nextArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-next"><use xlink:href="#icon-arrow-slider"></use></svg>',
          prevArrow: '<svg class="icon icon--arrow-slider slick-arrow slick-prev"><use xlink:href="#icon-arrow-slider"></use></svg>',
        });

        cubic.slickSliders.push($items);
      });
    },

    // Milestone carousel

    milestoneCarousel: function () {
      var $paragraph = $('.paragraph--type--milestone-carousel');
      var $sliders = $('.field--name-field-milestone-carousel-item', $paragraph);

      $sliders.each(function () {
        var $this = $(this);

        $this.slick({
          infinite: false,
          mobileFirst: true,
          nextArrow: '<div class="slider-arrow slider-next"></div>',
          prevArrow: '<div class="slider-arrow slider-prev"></div>',
          slidesToShow: 1,
          responsive: [
            {
              breakpoint: 640,
              settings: {
                slidesToShow: 2
              }
            },
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 4
              }
            }
          ]
        });
      });

      var ellipsis = new Ellipsis({debounce: 500});
      var elements = document.querySelectorAll('.paragraph--type--event-recap-slider-item .field--name-field-title');
      ellipsis.add(elements);
    },

    // Milestone Slider
    milestoneSlider: function () {
      var $sliders = $('.paragraph--type--milestone-slider');

      $sliders.each(function (event, item) {
        var $this = $(item);
        var $items = $('.field--name-field-milestone-slider-item.field__items', $this);

        $items.slick({
          adaptiveHeight: true,
          dots: true,
          infinite: false,
          nextArrow: '<div class="slider-arrow slider-next"></div>',
          prevArrow: '<div class="slider-arrow slider-prev"></div>',
        });

        cubic.slickSliders.push($items);
      });
    },

    mobileMenu: function () {
      var $menuTopLinks = $('#menus nav.menu--main .menu-container > ul > li > a, #menus nav.menu--main .menu-container > ul > li > span');

      $menuTopLinks.each(function () {
        var $this = $(this),
          $viewLink = $('<a>');

        if ($this.attr('href') && $this.attr('href') !== '#') {
          $viewLink
            .text('View ' + $this.text())
            .addClass('top-view-link')
            .attr({'href': $this.attr('href')})
            .prependTo($this.next('ul'));
        }
      });

      $menuTopLinks.on('click', function (event) {
        var $this = $(this),
          $parent = $this.parent('li');

        if (window.outerWidth < 1024 && $parent.hasClass('menu-item--expanded')) {
          event.preventDefault();
          if (!$parent.hasClass('open')) {
            $menuTopLinks.parent('li').removeClass('open');
            $parent.addClass('open');
          } else {
            $menuTopLinks.parent('li').removeClass('open');
          }
        }
      });

      $(window).on('scroll', Drupal.debounce(function () {
        var scrollTop = $(document).scrollTop();

        if (scrollTop > 0) {
          $('body').addClass('menu-fixed');
        } else {
          $('body').removeClass('menu-fixed');
        }
      }));
    },

    // images in paragraphs that should be pulled out into background images
    imagesToBgs: function () {
      var containers = [
        '.paragraph--type--milestone-50-50.subtype--video .video-embed-5050',
        '.paragraph--type--milestone-50-50:not(.subtype--product) .image-gallery.not-slider',
        '.paragraph--type--milestone-50-50.subtype--video .video-embed-5050',
        '.paragraph--type--_0-50.subtype--video .video-embed-5050',
        '.paragraph--type--image-and-menu .field--name-field-image',
        '.paragraph--type--_0-50:not(.subtype--product) .image-gallery.not-slider'
      ];

      $.each(containers, function (i, selector) {
        var $items = $(selector);

        $items.each(function (i, selector) {
          var $item = $(selector),
            $img = $item.find('img'),
            imgUrl = $img.attr('src');

          $img.css('visibility', 'hidden');
          $item.css('background-image', 'url(' + imgUrl + ')');
          if ($item.hasClass('video-embed-5050') && !$item.parents('.subtype--product').length) {
            $('.field--name-field-image-video', $item).hide();
          }
          $('.image-slider', $item).hide();
        });
      });
    },

    redrawBXSliders: function () {
      for (var i in cubic.bxSliders5050) {
        if (cubic.bxSliders5050.hasOwnProperty(i)) {
          cubic.bxSliders5050[i].redrawSlider();
        }
      }
    },

    redrawSlickSliders: function () {
      for (var j in cubic.slickSliders) {
        if (cubic.slickSliders.hasOwnProperty(j)) {
          cubic.slickSliders[j].slick('refresh');
        }
      }
    },

    // show and hide the search box
    searchToggle: function () {
      var $menus = $('#menus'),
        $toggle = $menus.find('.search-toggle'),
        $form = $menus.find('.views-exposed-form.block');

      $toggle.click(function (e) {
        e.preventDefault();
        $(this).toggleClass('open');
        $form.toggleClass('open');

        if ($form.hasClass('open')) {
          $form.find('.form-text').trigger('focus');
        }
      });
    },

    // activate the bxslider for 50/50 paragraphs with image galleries
    slider5050: function () {
      // redundant is-slider class because we're allowing just one image in this field
      var $sliders = $('.paragraph--type--_0-50.subtype--image--gallery .is-slider .image-slider');

      if ($sliders.length > 0) {
        $sliders.each(function () {
          var $this = $(this);
          $this.bxSlider({
            mode: 'fade',
            adaptiveHeight: true,
            minSlides: 1,
            maxSlides: 1,
            pager: false
          });
          cubic.bxSliders5050.push($this);
        });
      }
    },

    // instead of just jumping down the page, make regular jump links smooth scroll
    // via https://css-tricks.com/snippets/jquery/smooth-scrolling/
    smoothScrollJumpLinks: function () {
      // Select all links with hashes
      $('a[href*="#"]')
      // Remove links that don't actually link to anything, or are parts of Foundation
        .not('.accordion-title')
        .not('[role="tab"]')
        .not('[href="#0"]')
        .not('[href="#"]')
        .not('[href*="#paragraph-tab"]')
        .click(function (event) {
          // On-page links
          if (window.location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && window.location.hostname === this.hostname) {
            // Figure out element to scroll to
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            // Does a scroll target exist?
            if (target.length) {
              // Only prevent default if animation is actually gonna happen
              event.preventDefault();
              var menuOffset = $('body:not(.user-logged-in) #menus').outerHeight();
              $('html, body').animate({
                scrollTop: target.offset().top - menuOffset
              }, 1000, function () {
                // Callback after animation
                // Must change focus!
                var $target = $(target);
                $target.focus();
                if ($target.is(':focus')) { // Checking if the target was focused
                  return false;
                } else {
                  $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                  $target.focus(); // Set focus again
                }
              });
            }
          }
        });
    },

    solutionsMenus: function () {
      var $imageAndMenuLinks = $('.paragraph--type--image-and-menu .menu-item--expanded a');

      $imageAndMenuLinks.on('click', function (event) {
        var $this = $(this);
        if (hasTouch() && $this.parents('.menu').length === 3 && event.offsetX > $this.outerWidth()) {
          event.preventDefault();
          //$this.siblings().addClass('active');
          if( $this.siblings().hasClass('active')) {
            $this.siblings().toggleClass('active');

          } else {
            $('.paragraph--type--image-and-menu .menu-item--expanded a').siblings().removeClass('active');
            $this.siblings().addClass('active');
          }
        }
      });
    },

    stickyEventJumpLinks: function () {
      var $container = $('.field--name-field-paragraphs');
      var $tabSets = $('.paragraphs-jump-links', $container);
      /*var hash = window.location.hash;*/
      var windowWidth = 0;

      if ($tabSets.length === 0) {
        return;
      }

      function setWindowWidth() {
        windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
      }

      setWindowWidth();

      // Scroll to tab.
      /*if (hash && hash.match(/#paragraph-tab-.+/i)) {
        var $tab = $('a[href="' + hash + '"]', $tabSets);

        if ($tab.length) {
          var $paragraph = $tab.closest('.paragraph--type--tabs');

          if ($paragraph.length) {
            history.scrollRestoration = 'manual';
            var _scrollTop = $paragraph.offset().top - $tabSets.outerHeight();
            $('html, body').animate({scrollTop: _scrollTop});
          }
        }
      }*/

      $(window).on('scroll', function () {
        // On scroll, find out if we're in a tab range - if so, fix it
        var scrollTop = $(document).scrollTop();

        if (scrollTop >= $container.offset().top - 66) {
          $tabSets.addClass('fixed').css({
            'top': 66
          });
          if (windowWidth >= 1024) {
            $container.css({'padding-top': $tabSets.outerHeight()});
          }
        } else {
          $tabSets.removeClass('fixed').css({
            'top': 'auto'
          });
          $container.removeAttr('style');
        }
      });

      $(window).on('resize', Drupal.debounce(setWindowWidth));
    },

    // Custom sticky tabs function because Foundation.sticky doesn't work well on tabs
    stickyTabs: function () {
      var $tabSets = $('.paragraph--type--tabs .tabs-container'),
        hash = window.location.hash;

      if ($tabSets.length === 0) {
        return;
      }

      // Scroll to tab.
      if (hash && hash.match(/#paragraph-tab-.+/i)) {
        var $tab = $('a[href="' + hash + '"]', $tabSets);

        if ($tab.length) {
          var $paragraph = $tab.closest('.paragraph--type--tabs');

          if ($paragraph.length) {
            history.scrollRestoration = 'manual';
            var _scrollTop = $paragraph.offset().top - $('#menus').outerHeight();
            $('html, body').animate({scrollTop: _scrollTop});
          }
        }
      }

      // save the tab positioning info
      var width = '',
        left = '',
        tabRanges = [];

      // helper that updates the top-bottom range for each tab group
      function updateStickyTabRanges() {
        width = $tabSets.width();
        left = $tabSets.offset().left;

        $tabSets.each(function (i, el) {
          tabRanges[i] = {
            top: $(el).offset().top - 66,
            bottom: $(el).offset().top + $(el).outerHeight() - 66 - 65
          };
        });
      }

      updateStickyTabRanges();

      $(window)
        .on('scroll', Drupal.debounce(function () {
          // On scroll, find out if we're in a tab range - if so, fix it
          var scrollTop = $(document).scrollTop();

          for (var i = 0; i < tabRanges.length; i++) {
            var $tabs = $tabSets.eq(i).find('.tabs');

            if (scrollTop >= tabRanges[i].top && scrollTop <= tabRanges[i].bottom) {
              $tabs.addClass('fixed').css({
                'top': 66,
                'margin-top': 0,
                'left': left,
                'width': width
              });
              $tabs.parents('.paragraph--type--tabs').addClass('fixed');
              updateStickyTabRanges();
            } else if (scrollTop > tabRanges[i].bottom) {
              $tabs.css({
                'margin-top': -65
              });
            } else {
              $tabs.removeClass('fixed').css({
                'top': 'auto',
                'margin-top': 0,
                'left': 'auto',
                'width': 'auto'
              });
              $tabs.parents('.paragraph--type--tabs').removeClass('fixed');
            }
          }
        }))
        .on('resize', Drupal.debounce(function () {
          // on resize, update positioning settings and ranges
          updateStickyTabRanges();
        }));

      $(document).on('change.zf.tabs', function () {
        updateStickyTabRanges();
      });
    },

    /**************************************************************************************************************************/

    //Custom sticky tabs function for Timeline Tabs base on stickyTabs function
    // stickyTabsTimeline: function () {
    //   var $tabSets = $('.paragraph--type--tabs-time-line-tabs .tabs-container'),
    //     hash = window.location.hash;
    //
    //   console.log('here in stickyTabsTimeline');
    //   console.log('this is hash: ');
    //   console.log(hash);
    //
    //   if ($tabSets.length === 0) {
    //     return;
    //   }
    //
    //   // Scroll to tab.
    //   if (hash && hash.match(/#decade-.+/i)) {
    //     var $tab = $('a[href="' + hash + '"]', $tabSets);
    //
    //     console.log('this is $tab');
    //     console.log($tab);
    //
    //
    //     if ($tab.length) {
    //       var $paragraph = $tab.closest('.paragraph--type--tabs-time-line-tabs');
    //
    //       if ($paragraph.length) {
    //         history.scrollRestoration = 'manual';
    //         var _scrollTop = $paragraph.offset().top - $('#menus').outerHeight();
    //         $('html, body').animate({scrollTop: _scrollTop});
    //       }
    //     }
    //   }
    //
    //   // save the tab positioning info
    //   var width = '',
    //     left = '',
    //     tabRanges = [];
    //
    //   // helper that updates the top-bottom range for each tab group
    //   function updateStickyTabRanges() {
    //     console.log('here in updateStickyTabRanges');
    //
    //     width = $tabSets.width();
    //     left = $tabSets.offset().left;
    //
    //     $tabSets.each(function (i, el) {
    //       tabRanges[i] = {
    //         top: $(el).offset().top - 66,
    //         bottom: $(el).offset().top + $(el).outerHeight() - 66 - 65
    //       };
    //     });
    //   }
    //
    //   updateStickyTabRanges();
    //
    //   $(window)
    //     .on('scroll', Drupal.debounce(function () {
    //       // On scroll, find out if we're in a tab range - if so, fix it
    //
    //       console.log('scrolling');
    //       var scrollTop = $(document).scrollTop();
    //
    //       for (var i = 0; i < tabRanges.length; i++) {
    //         var $tabs = $tabSets.eq(i).find('.tabs');
    //
    //         console.log('here in the first loop for $(window) and this is $tabs');
    //         console.log($tabs);
    //
    //         if (scrollTop >= tabRanges[i].top && scrollTop <= tabRanges[i].bottom) {
    //           console.log('here in the top bottom validation for $(window) and this is $tabs');
    //           console.log($tabs);
    //
    //           $tabs.addClass('fixed').css({
    //             'top': 66,
    //             'margin-top': 0,
    //             'left': left,
    //             'width': width
    //           });
    //
    //           $tabs.parents('.paragraph--type--tabs-time-line-tabs').addClass('fixed');
    //           updateStickyTabRanges();
    //         } else if (scrollTop > tabRanges[i].bottom) {
    //           $tabs.css({
    //             //'margin-top': -65
    //           });
    //         } else {
    //
    //           $tabs.removeClass('fixed').css({
    //             'top': 'auto',
    //             'margin-top': 0,
    //             'left': 'auto',
    //             'width': 'auto'
    //           });
    //           $tabs.parents('.paragraph--type--tabs-time-line-tabs').removeClass('fixed');
    //         }
    //       }
    //     }))
    //     .on('resize', Drupal.debounce(function () {
    //       // on resize, update positioning settings and ranges
    //       updateStickyTabRanges();
    //     }));
    //
    //   $(document).on('change.zf.tabs', function () {
    //     updateStickyTabRanges();
    //   });
    // },
    /**************************************************************************************************************************/

    tables: function () {
      $('table', '.main-wrapper').each(function () {
        var $this = $(this);
        var $wrapper = $('<div class="table-scroll">');
        $wrapper.insertBefore($this).append($this);
      });
    },

    // Tabs should collapse on small screens, and when the tabs are too long to fit.
    // This function initializes the behavior, calling tabsCollapser on resize
    tabsMightCollapse: function () {
      var $tabs = $('.paragraph--type--tabs .tabs');

      $tabs.on('click.tabCollapser', '.tabs-title', function (e) {
        var $p = $(e.target).parents('.tabs');

        $p.toggleClass('active-only');
      });

      // debounce the tabs collapser
      var tabsTimer;
      $(window).on('resize', function () {
        clearTimeout(tabsTimer);
        tabsTimer = setTimeout(function () {
          cubic.tabsCollapser($tabs);
        }, 250);
      });

      cubic.tabsCollapser($tabs);
    },

    // Helper function for tabsMightCollapse
    tabsCollapser: function ($tabs) {
      // possible tab collapsing happens at medium or higher
      // if (Foundation.MediaQuery.atLeast('medium')) {
      if (window.outerWidth > 640) {
        $tabs.removeClass('vertical active-only stretch')
          .each(function (i, el) {
            // jshint unused:false
            var $tabSet = $(el),
              titlesLength = 0;

            $tabSet.find('.tabs-title')
              .css('width', 'auto')
              .each(function (i, el) {
                titlesLength += $(el).outerWidth();
              });
            titlesLength += 40; // give it a little extra for safety

            if (titlesLength >= window.outerWidth) {
              $tabSet.addClass('vertical active-only');
            } else {
              // the tabs are not too wide, so let them flex
              $tabSet.addClass('stretch');
            }
          });
      } else {
        // otherwise make the tabs vertical
        $tabs.addClass('vertical active-only').removeClass('stretch');
      }
    }
  };

  // add a body class so we know JS is available and can play with display properties
  $('body').addClass('has-js');

  $(window).on('load', function () {
    cubic.redrawBXSliders();
  });

  $(function () {
    cubic.init();
  });
})(jQuery);

'use strict';
/* globals _, jQuery */

var cubicComponents = {};

(function ($) {
  var $body = $('body');
  var parallaxSpeed = 0.7;                                                                                                                              //here's the one for divider
  var paragraphsFields = '.page-node-type-post .region-above-footer > .field__item.related-posts, .field--name-field-aside-paragraphs > .field__item, .field--name-field-milestones > .field__item, .field--name-field-paragraphs > .field__item, .field--name-field-lower-paragraphs > .field__item';
  cubicComponents = {
    init: function () {
      var $paragraphs = $('.paragraph', paragraphsFields);

      $paragraphs.each(function () {
        var $this = $(this);
        var $marker = $this.siblings('.inview-marker');

        $marker.one('inview.cubicComponents', function (event, isInView) {

          if (isInView) {
            $this.addClass('in');
            $marker.off('inview.cubicComponents');
          }
        });
      });

      if ($('.paragraph--type--callouts', paragraphsFields).length) {
        this.callouts();
      }

      //callouts_view_rows
      //views-element-container basic-animation paragraph paragraph--type--callouts paragraph--view-mode--default block block-views block-views-blocklist-related-posts
      if ($('.paragraph.block-views-blocklist-related-posts', paragraphsFields).length) {
        var $relatedPosts = $('.paragraph.block-views-blocklist-related-posts');

        $relatedPosts.one('inview.cubicComponents', function (event, isInView) {

          if (isInView) {
            $relatedPosts.addClass('in');
            $relatedPosts.off('inview.cubicComponents');
          }
        });

        this.callouts_view_rows();
      }

      if ($('.paragraph--type--case-study-row', paragraphsFields).length) {
        this.caseStudyRows();
      }

      if ($('.paragraph--type--divider-bar', paragraphsFields).length) {
        console.log('divider');
        console.log($('.paragraph--type--divider-bar').length);

        this.dividerBars();
      }

      if ($('.node--type-milestone-decade')) {
        this.decade();
      }

      // this is for when is in the timeline..
      //has-background node node--type-milestone-decade node--promoted node--view-mode-full
      if ($('.node--type-timeline .node--type-milestone-decade')) {
        this.decade();
      }

      // to call decadeParagraphs
      if ($('.paragraph', paragraphsFields).length) {
        this.decadeParagraphs();
      }

      if ($('.paragraph--type--_0-50', paragraphsFields).length) {
        this.fiftyFifties();
      }
    },

    caseStudyRows: function () {
      var $paragraphs = $('.paragraph--type--case-study-row', paragraphsFields);

      $paragraphs.each(function () {
        var $this = $(this);
        var $marker = $this.siblings('.inview-marker');
        var delayCounter = 0;

        $marker.one('inview', function (event, isInView) {
          if (isInView) {
            $('> .field--name-field-title, aside > *', $this).each(function () {
              var $this = $(this);
              var leftPosition = _.random(-5, 5);
              delayCounter += 100;
              $this.css({position: 'relative', left: leftPosition + 'vw'}).delay(delayCounter).animate({
                opacity: 1,
                left: 0
              }, {duration: 1000, easing: 'easeOutCubic'});
            });
            $marker.off('inview');
          }
        });

        var $img = $('> img', $this);

        if ($img.length) {
          var img = $img.get(0);
          $this.jarallax({
            automaticResize: true,
            imgElement: img,
            speed: parallaxSpeed
          });
          $this.addClass('parallax-processed');
        }
      });
    },

    dividerBars: function () {
      var $paragraphs = $('.paragraph--type--divider-bar', paragraphsFields);

      $paragraphs.each(function () {
        var $this = $(this);
        var $img = $('img', $this);

        if ($img.length) {
          var img = $img.get(0);
          $this.jarallax({
            automaticResize: true,
            imgElement: img,
            speed: parallaxSpeed
          });
          $this.addClass('parallax-processed');
        }
      });
    },

    //decade starts
    decade: function () {
      var $decadeContent = $('.node--type-milestone-decade .node__content');
      $decadeContent.each(function () {
        var $this = $(this);
        var $img = $('img', $this);


        if ($img.length) {
          var img = $img.get(0);
          $this.jarallax({
            automaticResize: true,
            imgElement: img,
            speed: parallaxSpeed
          });
          $this.addClass('parallax-processed');
        }
      });
    },
    //decade ends

    //decade paragraphs starts to add in
    decadeParagraphs: function () {
      console.log('here in the basic animation for .paragraph--type--milestone-group function..');
      var $paragraphs = $('.paragraph', paragraphsFields);
      console.log($paragraphs);

      $paragraphs.each(function () {
        var $this = $(this);
        var $marker = $this.siblings('.inview-marker');
        var delayCounter = 500;

        $marker.one('inview', function (event, isInView) {

          if (isInView) {
            $('.field--name-field-milestones .field__item', $this).each(function () {
              var $this = $(this);
              delayCounter += 100;
              $this.delay(delayCounter).queue(function () {
                $this.addClass('in').dequeue();
              });
            });
            $marker.off('inview');
          }
        });
      });
    },

    //decade paragraphs ends

    callouts: function () {
      var $paragraphs = $('.paragraph--type--callouts', paragraphsFields);

      $paragraphs.each(function () {
        var $this = $(this);
        var $marker = $this.siblings('.inview-marker');
        var delayCounter = 500;

        $marker.one('inview', function (event, isInView) {

          if (isInView) {
            $('.field--name-field-callout .field__item', $this).each(function () {
              var $this = $(this);
              delayCounter += 100;
              $this.delay(delayCounter).queue(function () {
                $this.addClass('in').dequeue();
              });
            });
            $marker.off('inview');
          }
        });
      });
    },

    callouts_view_rows: function () {


      var $paragraphs = $('.paragraph.block-views-blocklist-related-posts', paragraphsFields);
      $paragraphs.each(function () {

        var $this = $(this);
        var $marker = $this.siblings('.inview-marker');
        var delayCounter = 500;

        $this.one('inview', function (event, isInView) {

          if (isInView) {

            $('.view-content .views-row', $this).each(function () {

              var $this = $(this);
              delayCounter += 100;
              $this.delay(delayCounter).queue(function () {
                $this.addClass('in').dequeue();
              });
            });
            $marker.off('inview');
          }
        });
      });
    },

    fiftyFifties: function () {
      var $paragraphs = $('.paragraph--type--_0-50', paragraphsFields);

      $paragraphs.each(function () {
        var $this = $(this);
        var bodyOrder = $('.field--body--wrapper', $this).css('order');
        var extrasOrder = $('.extra-content--wrapper', $this).css('order');

        if (extrasOrder && extrasOrder < bodyOrder) {
          $this.addClass('body-last');
        }
      });
    }
  };

  $(function () {
   // if ($body.hasClass('advanced-animations')) {
    if (!$body.hasClass('user-logged-in') && $body.hasClass('advanced-animations') || (!$body.hasClass('user-logged-in') && $body.hasClass('page-node-type-milestone-decade')) || (!$body.hasClass('user-logged-in') && $body.hasClass('page-node-type-timeline'))) {
      cubicComponents.init();
    }
  });
})(jQuery);
