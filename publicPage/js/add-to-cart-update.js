(function ($) {
    ('use strict');
    $(function () {

        console.log('updated add to cart js initializing...');

        $(document).on('click', '.single_add_to_cart_button', function (e) {
            e.preventDefault();

            var $thisButton = $(this),
                $form = $thisButton.closest('form.cart'),
                id = $thisButton.val(),
                product_qty = $form.find('input[name=quantity]').val() || 1,
                product_id = $form.find('input[name=product_id]').val() || id,
                variation_id = $form.find('input[name=variation_id]').val() || 0,
                file = $form.find('input[id="pwp-file-upload"]')[0].files[0];

            var formData = new FormData();
            formData.append('action', 'PWP_Ajax_Add_To_Cart');
            formData.append('product_id', product_id);
            formData.append('product_sku', '');
            formData.append('quantity', product_qty);
            formData.append('variation_id', variation_id);
            formData.append('upload', file);
            formData.append('nonce', PWP_Ajax_Add_To_Cart_object.nonce);

            // var data = {
            //     action: 'PWP_Ajax_Add_To_Cart',
            //     product_id: product_id,
            //     product_sku: '',
            //     quantity: product_qty,
            //     variation_id: variation_id,
            //     files: files,
            // };

            $(document.body).trigger('adding_to_cart', [$thisButton, formData]);

            $.ajax({
                url: PWP_Ajax_Add_To_Cart_object.ajax_url,
                method: 'POST',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function (response) { },
                complete: function (response) {
                    console.log(response);
                },
                success: function (response) { },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        });
        return false;
    });
})(jQuery);