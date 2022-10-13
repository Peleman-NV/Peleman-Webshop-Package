/**
 * This script is responsible for redirecting customers to the Imaxel editor,
 * if the product has a template attached, in the admin backend.
 * It fires on the click event, and passes the variantId and,
 * if present, the content file Id.  It call   the PHP function
 * "ppi_add_to_cart" in PublicPage/PpiProductPage.php,
 * which makes a request to Imaxel to create a project.
 * It then persists this as a customer project and redirects the user to the editor.
 *
 * In the event of an error, it's displayed.
 * Errors can be caused by:
 * -an outage of the Imaxel servers (rare)
 * -a non-existing template defined in the backend
 */

(function ($) {
    ('use strict');
    $(function () {

        console.log('updated add to cart js initializing...');


        $(document).on('click', '.single_add_to_cart_button', function (e) {
            e.preventDefault();

            var $thisButton = $(this);
            var $form = $thisButton.closest('form.cart');
            var id = $thisButton.val();
            var product_qty = $form.find('input[name=quantity]').val() || 1;
            var product_id = $form.find('input[name=product_id]').val() || id;
            var variation_id = $form.find('input[name=variation_id]').val() || 0;
            var file = $form.find('input[id="pwp-file-upload"]')[0].files[0];

            var formData = new FormData();
            formData.append('action', 'PWP_Ajax_Add_To_Cart');
            formData.append('product_id', product_id);
            formData.append('product_sku', '');
            formData.append('quantity', product_qty);
            formData.append('variation_id', variation_id);
            formData.append('upload', file);
            formData.append('nonce', PWP_Ajax_Add_To_Cart_object.nonce);

            $(document.body).trigger('adding_to_cart', [$thisButton, formData]);

            $.ajax({
                url: PWP_Ajax_Add_To_Cart_object.ajax_url,
                method: 'POST',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $thisButton.removeClass('pwp-added')
                    $thisButton.addClass('pwp-loading');
                },
                complete: function (response) {
                    $thisButton.addClass('pwp-added')
                    $thisButton.removeClass('pwp-loading');
                    console.log(response);
                },
                success: function (response) {
                    data = response.data;
                    response.success ? onSuccess(data, $thisButton) : onFailure(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    logAjaxError(jqXHR, textStatus, errorThrown);

                }
            });
        });
        return false;
    });

    function onFailure(data) {
        console.log(data);
        alert(data.message);
        $('#redirection-info').html(data.message);

        $('#redirection-info').addClass('ppi-response-error');
    }

    function onSuccess(data, button) {
        if (data.destination_url) {
            window.location.href = data.destination_url;
            return;
        }

        $(document.body).trigger('added_to_cart', [
            data.fragments,
            data.cart_hash,
            button
        ]);
    }

    function logAjaxError(jqXHR, textStatus, errorThrown) {
        alert(textStatus);

        console.log(jqXHR);
        console.error(
            'Something went wrong:\n' +
            jqXHR.status +
            ': ' +
            jqXHR.statusText +
            '\nTextstatus: ' +
            textStatus +
            '\nError thrown: ' +
            errorThrown);
    }

})(jQuery);