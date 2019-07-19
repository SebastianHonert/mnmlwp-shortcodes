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

    // Video Cover
    $('.mnmlwp-video-container').on('click', function() {
        var iframe;
        var id = $(this).data('id');
        var platform = $(this).data('platform');

        if( ! id || ! platform )
            return;

        if( platform === 'youtube' ) {
            iframe = '<iframe src="//www.youtube.com/embed/' + id + '?autoplay=1" height="240" width="320" allow="autoplay" allowfullscreen=""></iframe>';
        } else if( platform === 'vimeo' ) {
            iframe ='<iframe src="https://player.vimeo.com/video/' + id + '" allowfullscreen=""></iframe>';
        }

        $(this).html(iframe);
    });

});
