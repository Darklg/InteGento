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

});