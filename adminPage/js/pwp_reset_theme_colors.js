(function ($) {
    ('use strict');

    class valueResetter {
        constructor(element, value) {
            this.value = value;
            this.element = $(element);
        }

        resetValue() {
            this.element.val(this.value);
        }
    }

    $(function () {

        var _resetButton = $('#reset_colors');
        var _submit = $("#submit");

        const _resetters = [
            new valueResetter('#main_color', "#2c5baa"),
            new valueResetter('#secondary_color', "#fdbe10"),
            new valueResetter('#main_text_color', "#444444"),
            new valueResetter('#menu_text_color', "#ffffff"),
            new valueResetter('#h1_text', 30,),
            new valueResetter('#h2_text', 25),
            new valueResetter('#h3_text', 20),
            new valueResetter('#h4_text', 17),
            new valueResetter('#h5_text', 17),
            new valueResetter('#h6_text', 17),
        ];

        _resetButton.on("click", function () {
            console.log("resetting colour values...");

            if (confirm("Are you sure you want to reset the styling?")) {
                _resetters.forEach(function (resetter) {
                    resetter.resetValue()
                });
                _submit.click();
            }
        });
    })

})(jQuery);

