/**
 * This script is responsible for redirecting customers to the Imaxel editor,
 * if the product has a template attached, in the admin backend.
 * It fires on the click event, and passes the variantId and,
 * if present, the content file Id.  It call the * the PHP function
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
            blockDefaultAddToCartBehavior
        );

        function blockDefaultAddToCartBehavior(e) {
            $('#ppi-loading').removeClass('ppi-hidden');
            e.preventDefault();
            const variationId = $("[name='variation_id']").val();
            const contentFileId = $("[name='content_file_id']").val();
            getRedirect(variationId, contentFileId);
        }

        function getRedirect(variationId, contentFileId = null) {
            $('#redirection-info').html('');
            const data = {
                variant: variationId,
                content: contentFileId,
                action: 'pwp_add_to_cart',
                // _ajax_nonce: pwp_add_to_cart_object.nonce,
            };

            $.ajax({
                url: pwp_add_to_cart_object.ajax_url,
                method: 'GET',
                data: data,
                cache: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    window.location.href = 'http://www.google.com';
                    if (response.status === 'success') {
                        if (response.isCustomizable === 'no') {
                            //redirect to IMAXEL editor
                            //after editing, the IMAXEL editor then returns the customer to 
                            // window.location.href = response.url;
                            return;
                        } else {
                            $('.single_add_to_cart_button').off(
                                'click',
                                blockDefaultAddToCartBehavior
                            );
                            $('.single_add_to_cart_button').trigger('click');
                        }
                    }
                    if (response.status === 'error') {
                        $('#redirection-info').html(response.message);
                        $('#redirection-info').addClass('ppi-response-error');
                    }
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
