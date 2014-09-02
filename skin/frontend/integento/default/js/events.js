var $jQBody = false;

jQuery(document).ready(function() {
    $jQBody = jQuery('body');

    /* All Pages */
    jQuery('[href^=#]').dkSmoothScroll();
    setAllPagesFakeElements();
    setAllPagesDataTogglers();

    /* Cart */
    if ($jQBody.hasClass('checkout-cart-index')) {
        setCartQtyModifierButtons();
    }
});