(function ($) {
    ('use strict');
    $(function () {
        //do main logic here

        console.log("ding!");

        var selections = $("select[target]");
        selections.each(function () {
            var target = $('#' + $(this).attr('target'));
            elementVisibility(target, $(this).val() != 'none');
            $(this).change(function () {
                elementVisibility(target, $(this).val() != 'none');
            });
        });

        var checks = $("input[target]");
        checks.each(function () {
            var target = $('#' + $(this).attr('target'));
            elementVisibility(target, $(this).prop('checked'));
            $(this).change(function () {
                elementVisibility(target, $(this).prop('checked'));
            });
        })
    })

    function elementVisibility(element, isVisible) {
        isVisible ? showElements(element) : hideElements(element);
    }
    function hideElements(element) {
        element.addClass('pwp-hidden');
    }
    function showElements(element) {
        element.removeClass('pwp-hidden');
    }

})(jQuery);