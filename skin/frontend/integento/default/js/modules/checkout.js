/**
 * set Qty modifier buttons in cart
 */
var setCartQtyModifierButtons = function() {
    if (!$jQBody.hasClass('checkout-cart-index')) {
        return;
    }

    var el = jQuery('[data-decrement], [data-increment]'),
        action = function(e) {
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
        };

    // Increment / Decrement on click
    el.on('click', action);
};