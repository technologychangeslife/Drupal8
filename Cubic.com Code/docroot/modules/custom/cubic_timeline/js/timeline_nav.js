jQuery(function($){
    $(document).foundation();

    if (window.location.hash) {
        var decade = window.location.hash.replace('#', '');
        timelineVue.jumpToDecade(decade);
    }

    var $timelineNav = $('.timeline-nav ul'),
        timelineSlider = null,
        timelineSliderOptions = {
            minSlides: 2,
            maxSlides: 3,
            slideWidth: 105,
            slideMargin: 0,
            pager: false,
            nextText: '˃',
            prevText: '˂',
            infiniteLoop: false
        };
    if (window.innerWidth <= 480) {
        timelineSlider = $timelineNav.bxSlider(timelineSliderOptions);
    }

    var timelineNaturalScrollTop = $('#block-cubictimelineblock').offset().top;
    var $timelineHeader = $('#timeline').find('.timeline-header');
    var $timelineContainer = $('#block-cubictimelineblock');

    // Data and functions to track and update the timeline nav while scrolling
    var timelineStops = {},
        currentDecade = 'decade-1950',
        // when we resize, stop points will need to be updated
        updateTimelineStops = function() {
            $('[id^=decade]').each(function(i, el) {
                timelineStops[$(el).attr('id')] = $(el).offset().top;
            });
        },
        getCurrentTimelineStop = function() {
            // add the height of the header for the stop test
            var stop = $(document).scrollTop() + $timelineHeader.outerHeight();

            $.each(timelineStops, function(key, value) {
                if (stop >= timelineStops[key]) {
                  currentDecade = key;
                } else {
                  return false;
                }
            });

            timelineVue.updateActiveDecade(currentDecade.replace('decade-', ''));
        };

    // Fire this when we first load
    updateTimelineStops();

    $(window)
        .on('scroll', Drupal.debounce(function(){
            // Fix the nav to the top of the screen when it's about to go away
            if ($(document).scrollTop() >= timelineNaturalScrollTop) {
                $timelineHeader.addClass('top-fixed');
            } else {
                $timelineHeader.removeClass('top-fixed');
            }

            getCurrentTimelineStop();
        }))
        .on('resize, load', Drupal.debounce(function(){
            // reset the natural scrollTop position on resize
            timelineNaturalScrollTop = $timelineContainer.offset().top;

            updateTimelineStops();
            getCurrentTimelineStop();
        }))
        .on('changed.zf.mediaquery', function(event, newSize, oldSize) {
            if (newSize === 'small') {
                timelineSlider = $timelineNav.bxSlider(timelineSliderOptions);
            } else {
                if (timelineSlider && timelineSlider.destroySlider) {
                    timelineSlider.destroySlider();
                    timelineSlider = null;
                }
            }
        }
    );
});
