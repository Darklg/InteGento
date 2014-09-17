/**
 * Slider product images
 */
var setProductViewImagesSlider = function() {
    var moreViews = jQuery('.product-img-box .more-views a'),
        productImages = jQuery('.product-img-box .product-image-list .product-image');

    moreViews.on('click', function(e) {
        if (e) e.preventDefault();
        var i = parseInt(jQuery(this).data('i'), 10);
        moreViews.removeClass('current');
        productImages.removeClass('current');
        moreViews.eq(i).addClass('current');
        productImages.eq(i).addClass('current');
    });
};