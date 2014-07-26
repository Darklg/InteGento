var $jQBody = false;

jQuery(document).ready(function() {
    $jQBody = jQuery('body');

    /* All pages */
    (function() {

        /* Fake Select */
        var $fakeSelect = jQuery('select.fakeselect-me, select[name=country_id]');
        $fakeSelect.FakeSelect();
        $fakeSelect.on('change', function(e) {
            setTimeout(function() {
                $fakeSelect.FakeSelect();
            }, 300);
        });

        /* Fake Input box */
        jQuery('input.fakeinputbox-me').fakeInputBox();

        /* Smooth scroll */
        jQuery('[href^=#]').dkSmoothScroll();

    }());

    /* Cart */

    (function() {
        if ($jQBody.hasClass('checkout-cart-index')) {
            // Increment / Decrement
            jQuery('[data-decrement], [data-increment]').on('click', function(e) {
                e.preventDefault();
                var $this = jQuery(this),
                    newVal = 0,
                    val = 0,
                    $input = false,
                    type = 'decrement';
                if ($this.data('increment')) {
                    type = 'increment';
                }

                $input = jQuery('#' + $this.data(type));
                if ($input.length > 0) {
                    val = parseInt($input.val(), 10);
                    newVal = Math.max(0, val - 1);
                    if (type == 'increment') {
                        newVal = val + 1;
                    }

                    $input.val(newVal);
                    $input.trigger('change');
                }
            });
        }
    }());

});