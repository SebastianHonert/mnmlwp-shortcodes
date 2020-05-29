jQuery(document).ready(function($) {

    // Valuemeters
    $('.mnmlwp-valuemeter').on('inview', function(event, isInView) {
        if (isInView) {
            var value = $(this).find('.mnmlwp-valuemeter-item-value').not('.activated');

            value.addClass('activated').width('0').stop().css('opacity', '.33').animate({
                width   : [value.attr('data-value') + '%', 'swing'],
                opacity : [1, 'swing'],
            }, 1500 + Math.ceil(Math.random() * 800));
        }
    });

    // Lightbox
    lightbox.option({
        'disableScrolling': true,
        'resizeDuration': 350,
        'fadeDuration': 0,
        'wrapAround': true,
        'positionFromTop': 72,
        'albumLabel': '%1 / %2',
    });

    // Flyout Containers
    $('.mnmlwp-flyout:not(.active) .mnmlwp-flyout-content').hide(0).toggleClass('active');
    $('.mnmlwp-flyout.active').find('.mnmlwp-flyout-title').addClass('active');

    $('.mnmlwp-flyout-title').on('click', function() {
        var flyout = $(this).closest('.mnmlwp-flyouts');
        if (typeof flyout !== 'undefined' && flyout.hasClass('mnmlwp-accordion')) {
            if ($(this).hasClass('active')) {
                return;
            }

            var children = flyout.find('.mnmlwp-flyout');
            $.each(children, function(index, child) {
                $(child).find('.mnmlwp-flyout-title').removeClass('active');
                $(child).find('.mnmlwp-flyout-content').removeClass('active');
            });

            if (flyout.hasClass('mnmlwp-accordion--close-all')) {
                $('.mnmlwp-flyout-content').slideUp(350);
            } else {
                children.find($('.mnmlwp-flyout-content')).slideUp(350);
            }
        }

        $(this).toggleClass('active').next('.mnmlwp-flyout-content').stop().slideToggle(350);
    });

    // Video Cover
    $('.mnmlwp-video-container').on('click', function() {
        var iframe;
        var id = $(this).data('id');
        var platform = $(this).data('platform');

        if( ! id || ! platform )
            return;

        if( platform === 'youtube' ) {
            iframe = '<iframe src="//www.youtube.com/embed/' + id + '?autoplay=1&rel=0" height="240" width="320" allow="autoplay" allowfullscreen=""></iframe>';
        } else if( platform === 'vimeo' ) {
            iframe ='<iframe src="https://player.vimeo.com/video/' + id + '" allowfullscreen=""></iframe>';
        }

        $(this).html(iframe);
    });

    // Tables
    (function ($) {
        "use strict";
        $.fn.responsiveTable = function() { 
      
          var toggleColumns = function($table) {
            var selectedControls = [];
            $table.find('.mnmlwp-tab').each( function() {
              selectedControls.push( $(this).attr('aria-selected') );
            });
            var cellCount = 0, colCount = 0;
            var setNum = $table.find('.mnmlwp-table-cell').length / Math.max( $table.find('.mnmlwp-tab').length );
            $table.find('.mnmlwp-table-cell').each( function() {
              $(this).addClass('hiddenSmall');
              if( selectedControls[colCount] === 'true' ) $(this).removeClass('hiddenSmall');
              cellCount++;
              if( cellCount % setNum === 0 ) colCount++; 
            });
          };
          $(this).each(function(){ toggleColumns($(this)); });
      
          $(this).find('.mnmlwp-tab').click( function() {
            $(this).attr('aria-selected', 'true').siblings().attr('aria-selected', 'false');
            toggleColumns( $(this).parents('.mnmlwp-table') );
          });
      
        };
    }(jQuery));
      
      
    $('.js-mnmlwp-table-tabs').responsiveTable();

});
