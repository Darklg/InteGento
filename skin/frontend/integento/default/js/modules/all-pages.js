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
        openOneMenuAtATime = true,
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
            var $parentLink = toggler.closest(linkReq);
            e.preventDefault();
            if (openOneMenuAtATime) {
                $parentLink.attr('data-oldclass', $parentLink.attr('class'));
                navLinks.removeClass(openNavElClassName);
                $parentLink.attr('class', $parentLink.attr('data-oldclass'));
            }
            toggler.closest(linkReq).toggleClass(openNavElClassName);
        });
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

/* ----------------------------------------------------------
  Set integento menu with arrows
---------------------------------------------------------- */

function setAllPagesResponsiveSubMenus() {
    var $nav = jQuery('#nav'),
        visibleSubmenuClassname = 'has-visible-submenu',
        arrowHTML = '<span class="display-submenu">&gt;</span>',
        backLinkHTML = '<li class="close-submenu"><a href="#">Back</a></li>',
        linksSelector = '.level0, .level1',
        linksASelector = '.level0 > a, .level1 > a',
        $links = false;
    if (!$nav) {
        return;
    }

    $links = $nav.find(linksSelector);
    $linksA = $nav.find(linksASelector);
    $linksA.each(function(i, el) {

        // If link is followed by a submenu
        $next = jQuery(this).next();
        if ($next.length < 1 || $next.get(0).tagName != 'UL') {
            return;
        }

        // Create arrows after each link
        var $arrow = jQuery(arrowHTML);
        jQuery(this).append($arrow);

        // Close all links and leave only parents opened
        $arrow.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $links.removeClass(visibleSubmenuClassname);
            jQuery(this).parents(linksSelector).addClass(visibleSubmenuClassname);
        });

        // Create back link to hide submenu
        var $back = jQuery(backLinkHTML);
        $next.prepend($back);
        $back.on('click', 'a', function(e) {
            e.preventDefault();
            jQuery(this).closest('.' + visibleSubmenuClassname).removeClass(visibleSubmenuClassname);
        });

    });

}
