jQuery(document).ready(function () {
    nglazyLoad();
    setInterval(function () {
        nglazyLoad();
    }, 500);
});

function nglazyLoad() {
    jQuery('.nglazyload').each(function(){
        if (jQuery(this).isInViewport()) {
            jQuery(this).removeClass('nglazyload');
        }
    });
    jQuery('[data-ngll-srcB]').each(function () {
        if (jQuery(this).isInViewport()) {
            jQuery(this).css(
                'background-image', 'url(' + jQuery(this).data('ngll-srcb') + ')'
            );
            jQuery(this).removeAttr('data-ngll-srcB');
        }
    });
	jQuery('img[data-ngll-src]').each(function () {
        if (jQuery(this).isInViewport()) {
            jQuery(this).attr('src', jQuery(this).data('ngll-src'));
            jQuery(this).removeAttr('data-ngll-src');
        }
    });
}

jQuery.fn.isInViewport = function () {
    let elementTop = jQuery(this).offset().top;
    let elementBottom = elementTop + jQuery(this).outerHeight();
    let viewportTop = jQuery(window).scrollTop();
    let viewportBottom = viewportTop + jQuery(window).height()*2;
    return elementBottom > viewportTop && elementTop < viewportBottom;
};
