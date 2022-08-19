/**
 * This script is only responsible to the content file upload.
 * If a product requires a content file, 'variable-product.js' will load an upload form.
 * This script fires on the change event of that form, and performs an AJAX call to
 * the PHP function "upload_content_file" in PublicPage/pwpProductPage.php,
 * where the file is validated and uploaded to the server on success.
 * A response is then return (success or error) after which the "add to cart" button is
 * enabled, or an error message is displayed.
 *
 * The upload button's colour is also set, depending on the URL.
 */

(function ($) {
    'use strict';
    $(function () {

        // Event: when the file input changes, ie: when a new file is selected
        $('#file-upload').on('change', e => {
            const variationId = $("[name='variation_id']").val();
            //re-enable this line to automatically disable the upload button
            // $('.single_add_to_cart_button').addClass('pwp-disabled');
            $('#upload-info').html(''); // clear html content in upload-info
            $('#upload-info').removeClass(); // removes all classes from upload info
            $('#pwp-loading').removeClass('pwp-hidden'); // display loading animation
            $('.thumbnail-container').css('background-image', ''); // remove thumbnail
            $('.thumbnail-container').removeClass('pwp-min-height');
            $('.thumbnail-container').prop('alt', '');

            const formData = constructFormData();

            // automatically submit form on change event
            $('#file-upload').submit();
            e.preventDefault();

            $.ajax({
                //ajax setup
                url: PWP_Upload_Content_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                dataType: 'json',
                success: function (response) {
                    onUploadSuccess(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    onUploadError(jqXHR, textStatus, errorThrown);
                },
            });
            $('#file-upload').val('');
        });

        function getDomain() {
            const url = window.location.href;
            return url.substring(
                url.indexOf('//') + 2,
                url.indexOf('.com') + 4
            );
        }

        /**
         * Updates the price for the user after uploading a content file
         *
         * @param {number} price
         */
        function updatePrice(price) {
            const pricetext = $(
                'div.woocommerce-variation-price span.woocommerce-Price-amount'
            ).text();

            const currencySymbol = pricetext.replace(/[0-9]./g, '');
            const newPriceText = currencySymbol + price.toFixed(2);

            $(
                'div.woocommerce-variation-price span.woocommerce-Price-amount'
            ).text(newPriceText);
        }

        function onUploadSuccess(response) {
            console.log(response);
            $('#upload-info').html(response.message);
            if (response.status === 'success') {
                updatePrice(response.file.price_vat_incl);
                // enable add to cart button
                $('.single_add_to_cart_button').removeClass('pwp-disabled');
                // update thumbnail container   
                $('.thumbnail-container').addClass('pwp-min-height');
                $('.thumbnail-container').css('background-image', 'url("' + response.file.thumbnail + '")');
                $('.thumbnail-container').prop('alt', response.file.name);

                // add content file id to hidden input
                $("[name='variation_id']").after(
                    '<input type="hidden" name="content_file_id" class="content_file_id" value="' + response.file.content_file_id + '"></input>'
                );
                $('#pwp-loading').addClass('pwp-hidden');
            } else {
                $('#upload-info').html(response.message);
                $('#upload-info').addClass('pwp-response-error');
                $('#pwp-loading').addClass('pwp-hidden');
            }
        }

        function onUploadError(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            $('#upload-info').html('Something went wrong.  Please try again with a different file.');
            $('#upload-info').addClass('response-error');
            $('#pwp-loading').addClass('pwp-hidden');
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

        }

        function constructFormData() {
            const fileInput = document.getElementById('file-upload');
            const file = fileInput.files[0];
            const formData = new FormData();

            formData.append('action', 'upload_content_file');
            formData.append('file', file);
            formData.append('variant_id', variationId);
            // formData.append('_ajax_nonce', PWP_Upload_Content_object.nonce); 
            return formData;
        }
    });
})(jQuery);
