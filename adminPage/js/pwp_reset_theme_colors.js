(function ($) {
    ('use strict');

    class valueResetter {
        constructor(value, element) {
            this.value = value;
            this.element = $(element);
        }

        resetValue() {
            if (element != null) {
                this.element.value = this.value;
            }
        }
    }

    $(function () {

        var _resetButton = $('#reset_colors');

        var _main = new valueResetter(
            "#2c5baa",
            '#main_color');
        var _secondary = new valueResetter(
            "#fdbe10",
            '#secondary_color');
        var _mainText = new valueResetter(
            "#444444",
            '#main_text_color');
        var _menuText = new valueResetter(
            "#ffffff",
            '#menu_text_color');


        _resetButton.click(function () {
            console.log("resetting colour values...");
            _main.resetValue();
            _secondary.resetValue();
            _mainText.resetValue();
            _menuText.resetValue();
        })


    })

})(jQuery);

