/*
 Integento Collateral accordion v 0.1.1 | @darklg | MIT/GPL2 Licensed
*/

var setIntegentoProductViewCollateral = function($parent, userConfig) {
    "use strict";
    this.$titles = [];
    this.$titleWrapper = false;
    this.$targets = [];
    this.$navigation = false;
    this.config = {
        addNav: true,
        navPrevHtml: '<',
        navNextHtml: '>',
        takeOutTitles: true,
        targetSelector: '.box-collateral',
        titleSelector: 'h2',
    };

    /* Check item */
    if (!$parent) {
        return;
    }

    this.init = function() {
        this.setConfig();
        if(this.$targets.length < 1){
            $parent.remove();
            return;
        }
        this.gotoTarget(0);
        this.setEvents();
    };

    this.displayTarget = function(i) {
        i = parseInt(i, 10);
        this.$titles.removeClass('current');
        this.$targets.removeClass('current');
        this.$titles.eq(i).addClass('current');
        this.$targets.eq(i).addClass('current');
        $parent.attr('data-current-target', i);
    };

    this.gotoTarget = function(i) {
        var currentI = parseInt($parent.attr('data-current-target'), 10),
            maxI = this.$titles.size() - 1;

        if (i == 'next') {
            i = currentI + 1;
        }
        if (i == 'prev') {
            i = currentI - 1;
        }
        if (i > maxI) {
            i = 0;
        }
        if (i < 0) {
            i = maxI;
        }

        this.displayTarget(i);
    };

    this.setEvents = function() {
        this.$titles.on('click', jQuery.proxy(function(e) {
            e.preventDefault();
            this.gotoTarget(jQuery(e.currentTarget).attr('data-i'));
        }, this));
        if (this.config.addNav) {
            this.$navigation.on('click', 'a[rel]', jQuery.proxy(function(e) {
                e.preventDefault();
                this.gotoTarget(jQuery(e.currentTarget).attr('rel'));
            }, this));
        }
    };

    this.setConfig = function() {
        /* Set Config */
        userConfig = userConfig || {};
        for (var att in userConfig) {
            this.config[att] = userConfig[att];
        }
        /* Set vars */
        this.$titles = $parent.find(this.config.titleSelector);
        this.$targets = $parent.find(this.config.targetSelector);
        $parent.addClass('has-collateral-slide');
        /* Create wrapper */
        if (this.config.takeOutTitles) {
            this.$titleWrapper = jQuery('<div class="collateral-titles"></div>');
            this.$titleWrapper.prependTo($parent);
        }
        /* Set up titles */
        this.$titles.each(jQuery.proxy(function(i, el) {
            var $el = jQuery(el);
            $el.attr('data-i', i);
            $el.attr('role', 'button');
            if (this.config.takeOutTitles) {
                $el.appendTo(this.$titleWrapper);
            }
        }, this));

        /* Set up arrows */
        if (this.config.addNav) {
            this.$navigation = jQuery('<div class="collateral-navigation"><a href="#" rel="prev">' + this.config.navPrevHtml + '</a><a href="#" rel="next">' + this.config.navNextHtml + '</a></div>');
            this.$navigation.prependTo($parent);
        }
    };

    this.init();
};
