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
