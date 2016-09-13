/**
 * Allow customisation for fake elements
 */
var setAllPagesFakeElements = function() {

    /* Fake Input box */
    jQuery('input.fakeinputbox-me').fakeInputBox();
};

/**
 * Allow an element to toggle another one
 */
var setAllPagesDataTogglers = function() {
    function clickAction(e) {
        e.preventDefault();
        var $this = jQuery(this),
            target = $this.attr('data-togglefor'),
            isOpened = false,
            $target = jQuery(target);

        if (!$target) {
            return;
        }

        isOpened = $target.hasClass('is-opened');

        if (isOpened) {
            $this.removeClass('is-opened');
            $target.removeClass('is-opened');
        }
        else {
            $this.addClass('is-opened');
            $target.addClass('is-opened');
        }

    }
    jQuery('[data-togglefor]').on('click', clickAction);
};

/**
 * Set Responsive main menu
 */
var setAllPagesResponsiveMainMenu = function() {
    var nav = jQuery('#nav'),
        linkReq = 'li.level0.parent',
        openNavClassName = 'has-nav-container-open',
        openNavElClassName = 'is-opened',
        navLinks = false;
    if (nav.length < 1) {
        return false;
    }

    navLinks = nav.find(linkReq);

    /* Inject links */
    navLinks.each(function() {
        var $this = jQuery(this),
            toggler = jQuery('<span class="nav-toggler"></span>');
        toggler.appendTo($this.children('a'));
        toggler.on('click', function(e) {
            e.preventDefault();
            navLinks.removeClass(openNavElClassName);
            toggler.closest(linkReq).toggleClass(openNavElClassName);
        })
    });

    /* Responsive menu */
    jQuery('.toggle-mobile-menu').on('click', function(e) {
        e.preventDefault();
        $jQBody.toggleClass(openNavClassName);
        navLinks.removeClass(openNavElClassName);
    });
};


var setTouchMenusDesktop = function() {
    var target = '.main-link--has-submenu > a',
        minWidth = 1024,
        $window = jQuery(window),
        isTouchScreen = function() {
            return 'ontouchstart' in window;
        };

    /* Prevent click on some links but triggers hover */
    jQuery('body').on('click', target, function(e) {
        if ($window.width() < minWidth || !isTouchScreen()) {
            return;
        }
        e.preventDefault();
    });
};
