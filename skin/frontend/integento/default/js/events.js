var $jQBody = false;

jQuery(document).ready(function() {
    $jQBody = jQuery('body');

    /* All Pages */
    jQuery('[href^=#]').dkSmoothScroll();
    setAllPagesFakeElements();
    setAllPagesDataTogglers();
    setAllPagesResponsiveMainMenu();

    /* Cart */
    if ($jQBody.hasClass('checkout-cart-index')) {
        setCartQtyModifierButtons();
    }

    /* Catalog */
    if ($jQBody.hasClass('catalog-product-view')) {
        setProductViewImagesSlider();
    }

    /* Collateral */
    if (jQuery('.product-collateral').length > 0) {
        new setIntegentoProductViewCollateral(jQuery('.product-collateral'));
    }
});
