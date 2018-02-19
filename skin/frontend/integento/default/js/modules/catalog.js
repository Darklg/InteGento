/**
 * Slider product images
 */
var setProductViewImagesSlider = function() {
    var moreViews = jQuery('.product-img-box .more-views'),
        productImages = jQuery('.product-img-box .product-image-list > *');

    /* Click action */
    moreViews.on('click', 'a', function(e) {
        if (e) {
            e.preventDefault();
        }
        var i = parseInt(jQuery(this).data('i'), 10);
        moreViews.removeClass('current');
        productImages.removeClass('current');
        moreViews.eq(i).addClass('current');
        productImages.eq(i).addClass('current');
    });

    /* Mobile slider */
    (function() {
        var $productImageList = jQuery('.product-image-list'),
            $moreViews = jQuery('.more-views'),
            sliderUnder = 768;
        if (jQuery === undefined || jQuery.fn.slick === undefined) {
            return;
        }

        function set_slider() {
            $moreViews.hide();
            $productImageList.slick({
                infinite: true,
                arrows: false,
                dots: true
            });
        }

        function unset_slider() {
            $moreViews.show();
            $productImageList.slick('unslick');
        }

        function check_resize() {
            if (window.innerWidth >= sliderUnder) {
                /* Unset over size */
                if ($productImageList.hasClass('slick-initialized')) {
                    unset_slider();
                }
            }
            else {
                /* Set under size */
                if (!$productImageList.hasClass('slick-initialized')) {
                    set_slider();
                }
            }
        }
        check_resize();
        jQuery(window).on('resize', check_resize);

    }());

};
