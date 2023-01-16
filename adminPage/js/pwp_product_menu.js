(function ($) {
    ('use strict');
    $(function () {
        connectElements();
        $(document).on('woocommerce_variations_loaded', function (event) {
            connectElements();
        });
    });

    function elementVisibility(element, isVisible) {
        isVisible ? showElements(element) : hideElements(element);
    }
    function hideElements(element) {
        element.addClass('pwp-hidden');
    }
    function showElements(element) {
        element.removeClass('pwp-hidden');
    }

    function connectElements() {

        console.log('ding');

        var selections = $("select[foldout]");
        selections.each(function () {
            var id = '#' + $(this).attr('foldout');
            console.log(id);

            var target = $(id);
            $(this).change(function () {
                console.log('foo');
                elementVisibility(target, $(this).val() != '');
            });
        });

        var checks = $("input[foldout]");
        checks.each(function () {
            var target = $('#' + $(this).attr('foldout'));
            $(this).change(function () {
                elementVisibility(target, $(this).prop('checked'));
            });
        })
    };

})(jQuery);