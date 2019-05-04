$(document).ready(function () {
	nglazyLoad();
	setInterval(function () {
		nglazyLoad();
	}, 500);
	$(window).scroll(function () {
		nglazyLoad();
	});
	$(window).resize(function () {
		nglazyLoad();
	});
});

function nglazyLoad() {
	$('[data-ngll-srcB]').each(function () {
		if ($(this).isInViewport()) {
			$(this).css(
				'background-image', 'url(' + $(this).data('ngll-srcb') + ')'
			);
			$(this).removeAttr('data-ngll-srcB');
		}
	})
	$('img[data-ngll-src]').each(function () {
		if ($(this).isInViewport()) {
			$(this).attr('src', $(this).data('ngll-src'));
			$(this).removeAttr('data-ngll-src');
		}
	});
}
jQuery.fn.isInViewport = function () {
	var elementTop = $(this).offset().top;
	var elementBottom = elementTop + $(this).outerHeight();
	var viewportTop = $(window).scrollTop();
	var viewportBottom = viewportTop + $(window).height();
	return elementBottom > viewportTop && elementTop < viewportBottom;
};
