/**
 * jQuery Slide Plugin
 * @author bakkelun
 * @version 0.5
 * @website: www.dosspirit.net
 * Enables a list of images to be displayed as a slider with custom intervals between
 * slides along with transition speed and type. Added support for tabs.
 */
$.fn.slide = function(options) {

	var defaultConfig = {
	'speed': 400, // defined as milliseconds, or "fast" / "slow".
	'anim': 'fade', // Valid options are fade and transition
	'pause': 4000, // time between slides, defined in milliseconds
	'tabelem': 'slideshow-tabs', // the class of the tabs (if applicable)
	'add': 'slideshow', // the class which will be added to the ul
	'tabselcls': 'tabselected' // the class which is applied when a tab is clicked
};

var config = $.extend(defaultConfig, options);
var el = $(this).find('> li'); // Find only the first level descendants.
var cur = $(el).first();
var tabs = $('.' + config['tabelem']).find('li');
var animSpeed = config['speed'];
var animType = config['anim'];
var addCls = config['add'];
var selTabCls = config['tabselcls'];
var slidePause = config['pause'];
var curTab = tabs.first('li');
var cur = el.first();

$(this).addClass(addCls);

// Hide all li els and show the first. Add id's to each of the slides.
$(el).each(function(i) {
	$(this).attr('id', 'slide' + i); // must be unique if referred to!
	$(this).hide();
});

$(cur).show();
curTab.addClass(selTabCls);

/*
 * Get next image, if it reached the end of the slideshow, rotate it back to the first image.
 * Do not transition if number of elements are only 1.
 */
if (el.length > 1) {
    setInterval(function() {
    	curTab.removeClass(selTabCls);
    	next = cur.next().length ? cur.next() : $(el).first();
    	curTab = curTab.next().length ? curTab.next() : $(tabs).first();
    	curTab.addClass(selTabCls);

    	if (animType == 'fade') {
    		next.fadeIn(animSpeed);
    		cur.fadeOut(animSpeed);
    	} else if (animType == 'slide') {
    		next.slideToggle(animSpeed);
    		cur.slideToggle(animSpeed);
    	}

    	cur = next;
    	},
    	slidePause
    );
}

// Bind each tab icon click to change to that tab.
$(tabs).each(function(i) {
	$(this).bind('click', function() {
		var showTab = $('#slide'+i);
		curTab.removeClass(selTabCls);

		if (animType == 'fade') {
			cur.fadeOut(animSpeed);
			showTab.fadeIn(animSpeed);
		} else if (animType == 'slide') {
			cur.slideToggle(animSpeed);
			showTab.slideToggle(animSpeed);
		}

		$(this).addClass(selTabCls);
		curTab = $(this);
		cur = showTab;
		return false;
	});
});

};