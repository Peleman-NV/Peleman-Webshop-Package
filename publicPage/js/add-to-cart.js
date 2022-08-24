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
        $('.single_add_to_cart_button').on(
            'click',
            overrideDefaultAddToCartBehaviour
        );


        function overrideDefaultAddToCartBehaviour(e) {
            $('#ppi-loading').removeClass('ppi-hidden');
            e.preventDefault();
            var $thisButton = $(this),
                $form = $thisButton.closest('form.cart'),
                id = $thisButton.val(),
                product_qty = $form.find('input[name="quantity"]').val() || 1,
                product_id = $form.find('input[name="product_id"]').val() || id,
                variation_id = $form.find('input[name="variation_id"]').val() || 0;

            const variationId = $("[name='variation_id']").val();
            const productId = $("[name='product_id']").val();
            const contentFileId = $("[name='content_file_id']").val();
            const quantity = $("[name='quantity']").val();
            attemptAddProductToCart($thisButton, productId, variationId, quantity, contentFileId);
        }

        function attemptAddProductToCart(Button, productId, variationId, quantity, contentFileId = null) {
            $('#redirection-info').html('');
            const data = {
                action: 'PWP_Ajax_Redirect_To_Editor',
                product: productId,
                variant: variationId,
                quantity: quantity,
                content: contentFileId,
                nonce: PWP_Ajax_Redirect_To_Editor_object.nonce,
            };

            $(document.body).trigger('adding_to_cart', [Button, data])
            $.ajax({
                url: PWP_Ajax_Redirect_To_Editor_object.ajax_url,
                method: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                beforeSend: function (response) {
                    console.log("clicked button");
                    Button.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    console.log("response received");
                    Button.addClass('added').removeClass('loading');
                },
                success: function (response) {
                    console.log(response);
                    if (response.success !== true) {
                        //in case something went wrong generating a new project and we cannot redirect the user
                        $('#redirection-info').html(response.data.message);
                        $('#redirection-info').addClass('ppi-response-error');
                        return;
                    }
                    if (response.data.destination_url !== '') {
                        //if the response has a destination url, redirect.
                        console.log(response.data.destination_url);
                        window.location.href = response.data.destination_url;
                        return;
                    }

                    //if we're at this point in the script, we can safely assume that we should use the default functionality of the button.
                    $(document.body).trigger('added_to_cart',
                        [
                            response.fragments,
                            response.cart_hash,
                            Button
                        ]);
                    $('.single_add_to_cart_button').off(
                        'click',
                        overrideDefaultAddToCartBehaviour
                    );
                    $('.single_add_to_cart_button').trigger('click');
                    return;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log({ jqXHR });
                    console.error(
                        'Something went wrong:\n' +
                        jqXHR.status +
                        ': ' +
                        jqXHR.statusText +
                        '\nTextstatus: ' +
                        textStatus +
                        '\nError thrown: ' +
                        errorThrown
                    );
                },
            });
        }
    });
})(jQuery);
