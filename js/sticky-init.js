jQuery(document).ready(function($) {

    var gallerySelector = '.gallery-thumbs';
    var checkInterval = 100;
    var maxAttempts = 150;
    var attempts = 0;

    var stickyWatcher = setInterval(function() {
        attempts++;
        var $gallery = $(gallerySelector);
        if ($gallery.length && $gallery.hasClass('swiper-initialized')) {

            clearInterval(stickyWatcher);

            $gallery.stick_in_parent({
                offset_top: 70,
                parent: '.card-main__column'
            });

        } else if (attempts > maxAttempts) {
            clearInterval(stickyWatcher);
        }

    }, checkInterval);
});


