jQuery(document).ready(function ($) {
    /* open folding content */
    $('.cd-gallery div').on('click', function (event) {
        event.preventDefault();
        console.log($(this).attr('id'))
        openItemInfo($(this).attr('id'));
        $(".video-js").css({})
    });
    var myPlayer = _V_('example_video_1');
    /* close folding content */
    $('.cd-folding-panel .cd-close').on('click', function (event) {
        $('.cd-folding-panel').removeClass('is-open');
        $('.cd-main').removeClass('fold-is-open');
        myPlayer.pause();
        //event.stopPropagation();
        //toggleContent('', false);
    });
    $('.cd-gallery').on('click', function (event) {
        /* detect click on .cd-gallery::before when the .cd-folding-panel is open */
        console.log($(event.target));
        if ($(event.target).is('.cd-gallery') && $('.fold-is-open').length > 0) toggleContent('', false);
    })

    function openItemInfo(url) {
        var mq = viewportSize();
        if ($('.cd-gallery').offset().top > $(window).scrollTop() && mq != 'mobile') {
            /* if content is visible above the .cd-gallery - scroll before opening the folding panel */
            $('body,html').animate({
                'scrollTop': $('.cd-gallery').offset().top
            }, 100, function () {
                toggleContent(url, true);
            });

        } else {
            toggleContent(url, true);
        }
    }

    function toggleContent(url, bool) {
        console.log(url);
        if (url === 'video') {
            /* load and show new content */
            $(".cd-fold-content").find(".single-page").hide();
            $('.cd-fold-content').find("#example_video_1").show();
            setTimeout(function () {
                $('body').addClass('overflow-hidden');
                $('.cd-folding-panel').addClass('is-open');
                $('.cd-main').addClass('fold-is-open');
            }, 100);

            //});
        } else if (url === 'test') {
            /* close the folding panel */
            var mq = viewportSize();
            $('.cd-folding-panel').removeClass('is-open');
            $('.cd-main').removeClass('fold-is-open');

            (mq == 'mobile' || $('.no-csstransitions').length > 0 )
                /* according to the mq, immediately remove the .overflow-hidden or wait for the end of the animation */
                ? $('body').removeClass('overflow-hidden')

                : $('.cd-main').find('.cd-item').eq(0).one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
                $('body').removeClass('overflow-hidden');
                $('.cd-main').find('.cd-item').eq(0).off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
            });

            $('.cd-fold-content').find("#example_video_1").hide();
            $(".cd-fold-content").find(".single-page").show();
            setTimeout(function () {
                $('body').addClass('overflow-hidden');
                $('.cd-folding-panel').addClass('is-open');
                $('.cd-main').addClass('fold-is-open');
            }, 100);
        }

    }

    function viewportSize() {
        /* retrieve the content value of .cd-main::before to check the actua mq */
        return window.getComputedStyle(document.querySelector('.cd-main'), '::before').getPropertyValue('content');
    }
});