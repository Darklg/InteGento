/**
 * Allow customisation for fake elements
 */
var setAllPagesFakeElements = function() {
    /* Fake Select */
    var $fakeSelect = jQuery('.fakeselect-child select, select.fakeselect-me, select[name=country_id]');
    $fakeSelect.FakeSelect();
    $fakeSelect.on('change', function(e) {
        setTimeout(function() {
            $fakeSelect.FakeSelect();
        }, 300);
    });

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