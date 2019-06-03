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

    $('.mnmlwp-flyout-title').click(function() {
        $(this).toggleClass('active').next('.mnmlwp-flyout-content').stop().slideToggle(350);
    });

});
