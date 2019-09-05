jQuery(document).ready(function () {
	setInterval(function () {
		nglazyLoad();
	}, 500);
});

function nglazyLoad() {
	jQuery('[data-ngll-srcB]').each(function () {
		if (jQuery(this).isInViewport()) {
			jQuery(this).css(
				'background-image', 'url(' + jQuery(this).data('ngll-srcb') + ')'
			);
			jQuery(this).removeAttr('data-ngll-srcB');
		}
	})
	jQuery('img[data-ngll-src]').each(function () {
		if (jQuery(this).isInViewport()) {
			jQuery(this).attr('src', jQuery(this).data('ngll-src'));
			jQuery(this).removeAttr('data-ngll-src');
		}
	});
}

jQuery.fn.isInViewport = function () {
	var elementTop = jQuery(this).offset().top;
	var elementBottom = elementTop + jQuery(this).outerHeight();
	var viewportTop = jQuery(window).scrollTop();
	var viewportBottom = viewportTop + jQuery(window).height();
	return elementBottom > viewportTop && elementTop < viewportBottom;
};
