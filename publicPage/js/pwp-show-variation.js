/**
 * This script is responsible the custom display of Peleman products.  This is either
 *      displaying the additional attributes that Peleman products require
 *              OR
 *      displaying the custom Add to cart text
 *
 * If a product requires a content file, the add to cart button is disabled and
 * an upload form with parameters is displayed.
 * 'upload-content.js' fires on the change event of that form,
 * and performs an AJAX call to the PHP function "upload_content_file"
 * in /PublicPage/pwpProductPage.php, where the file is validated
 * and uploaded to the server on success.
 * A response is then returned and displayed.
 * On success, the "add to cart" button is enabled.
 * On error, a message is displayed.
 *
 * If no content is required, the custom add to cart buton is displayed and enabled.
 *
 * The upload button's colour is also set, depending on the URL.
 *
 * An additional aspect is displaying the per piece and per unit prices for each variable
 */

(function ($) {
    ('use strict');
    const buttonText = setAddToCartLabel();

    /** EVENTS */
    // Event: when a variation is selected
    $(document).on('show_variation', (event, variation) => {
        event.stopPropagation();
        initRefreshVariantElements();
        getProductVariationData(variation.variation_id);
    });

    // Event: when a new variation is chosen
    $(document).on('hide_variation', e => {
        hideUploadElement();
        disableAddToCartButton(buttonText);
        resetUnitPrice();
        hideArticleCodeElement();
    });

    /** FUNCTIONS */

    function hideElement(element) {
        element.addClass('pwp-hidden');
    }

    function showElement(element) {
        element.removeClass('pwp-hidden');
    }

    function enableElement(element) {
        element.prop('disabled', false);
        element.removeClass('pwp-disabled');
        element.removeClass('disabled');
    }

    function disableElement(element) {
        element.prop('disabled', true)
        element.addClass('pwp-disabled');
        element.addClass('disabled')
    }

    function getProductVariationData(variationId) {
        const data = {
            variant: variationId,
            action: 'Ajax_Show_Variation',
            //_ajax_nonce: pwp_product_variation_information_object.nonce,
        };
        let fallbackAddToCartLabel = setAddToCartLabel();

        $.ajax({
            url: Ajax_Show_Variation_object.ajax_url,
            method: 'GET',
            data: data,
            cache: false,
            dataType: 'json',
            beforeSend: function () {
                console.log('working...');
                disableAddToCartButton(getLoadingMsg());
            },
            complete: function () {
                console.log('completed');
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    if (response.data.callUsToOrder) {
                        showCallUsTextAndButton();
                        return;
                    }
                    // showUnitPrice(response.data.bundleObject);
                    var buttonText = response.data.button_text ?? fallbackAddToCartLabel;

                    if (response.data.f2dArtCode) {
                        displayArticleCode(response.data.f2dArtCode);
                    }
                    if (!response.data.in_stock) {
                        disableAddToCartButton(buttonText);
                        disableUploadButton();
                        return;
                    }
                    if (response.data.requires_pdf_upload) {
                        displayUploadElement(response.data);
                    } else {
                        hideUploadElement(response.data);
                    }

                    if (response.data.is_customizable) {
                        AddButtonIconClass();
                    } else {
                        RemoveButtonIconClass();
                    }
                    DisplayBundlePricing(response.data);

                    enableAddToCartButton(buttonText);
                    return;

                }
                $('#variant-info').html(response.data.message);
                $('#variant-info').addClass('pwp-response-error');
                hideElement($('#pwp-loading'));

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

    // when showing a (new) variation, all previous elements need to be cleared or hidden
    function initRefreshVariantElements() {
        // display loading animation
        showElement($('#pwp-loading'));
        // clear any old upload information
        // $('#pwp-file-upload').val('');
        // hide the ever present max upload file size
        hideElement($('#max-upload-size'));
        // disable add-to-cart btn
        disableElement($('.single_add_to_cart_button'));
        // hide upload button
        hideElement($('.pwp-upload-form'));
        // hide upload parameters block
        hideElement($('.pwp-upload-parameters'));

        $('.single_variation_wrap').show();
        $('.summary p.price').show();
        $('.add-to-cart-price').show();
        $('p').remove('#call-us');
        hideElement($('#call-us-btn'));
        hideElement($('#call-us-price'));

        // article code
        hideElement($('span.article-code-container'));
        console.log("foo!");
    }

    function displayArticleCode(articleCode) {
        // $('.article-code').remove();
        if (articleCode) {
            console.log(articleCode);
            $('.article-code').html(articleCode);
            showElement($('span.article-code-container'));
        }
    }

    /**
     * Function displays the necessary parameters, when present
     */
    function displayUploadElement(data) {
        enableUploadBtn();
        const { height, width, min_pages, max_pages, price_per_page, price_per_page_html, total_price } =
            data.pdf_data;

        hideElement($('#ppi-loading'));
        showElement($('.pwp-upload-parameters'));
        showElement($('.pwp-upload-form'));
        showElement($('.upload-label'));
        showElement($('#max-upload-size'));
        $('#pwp-file-upload').prop('required', true);

        if (height != '') {
            $('#content-height').html(height);
            showElement($('#content-height').parent());
        } else {
            hideElement($('#content-height').parent());
        }
        if (width != '') {
            $('#content-width').html(width);
            showElement($('#content-width').parent());
        } else {
            hideElement($('#content-width').parent());
        }
        if (min_pages != '') {
            $('#content-min-pages').html(min_pages);
            showElement($('#content-min-pages').parent());
        } else {
            hideElement($('#content-min-pages').parent());
        }
        if (max_pages != '') {
            $('#content-max-pages').html(max_pages);
            showElement($('#content-max-pages').parent());
        } else {
            hideElement($('#content-max-pages').parent());
        }
        if (price_per_page != '') {
            $('.price-per-page').attr('value', price_per_page);
            $('.price-per-page').html(price_per_page_html);
            showElement($('#content-price-per-page').parent());
        } else {
            hideElement($('#content-price-per-page').parent());
            $('.price_per_page').attr('value', 0);
        }
        $('#pwp-product-price').attr('value', total_price);
    }

    function DisplayBundlePricing(data) {
        if (data.is_bundle) {
            $('.individual-price').removeClass('pwp-hidden');
            $('.individual-price-amount').html(data.item_price);
            $('.bundle-price-amount').html(data.unit_price);
            $('.bundle-suffix').removeClass('pwp-hidden');
            $('.bundle-suffix').html(data.unit_amount)
            hideElement($('.woocommerce-variation-price'));
            return;
        }
        $('.bundle-price-amount').html(data.item_price);
        $('.individual-price').addClass('pwp-hidden');
        $('.bundle-suffix').addClass('pwp-hidden');
        showElement($('woocommerce-variation-price'));
    }

    /**
     * Function hides upload parameters,
     * because a  new variant may not have upload parameters
     */
    function hideUploadElement() {
        hideElement($('.pwp-upload-form'));
        hideElement($('.pwp-upload-parameters'));
        $('.upload-label').addClass('upload-disabled');
        $('#pwp-file-upload').prop('required', false);
    }

    function setAddToCartLabel() {
        const language = getSiteLanguage();

        switch (language) {
            case 'en':
            default:
                return 'Add to cart';
            case 'nl':
                return 'Voeg toe aan winkelmand';
            case 'fr':
                return 'Ajouter au panier';
            case 'de':
                return 'In den Warenkorb legen';
            case 'it':
                return 'Aggiungi al carrello';
            case 'es':
                return 'Añadir al carrito';
        }
    }

    function getLoadingMsg() {
        const lang = getSiteLanguage();

        switch (lang) {
            case 'en':
            default:
                return 'loading...';
            case 'nl':
                return 'laden...';
            case 'fr':
                return 'chargement...';
            case 'de':
                return 'laden...'
            case 'it':
                return 'caricamento...';
            case 'es':
                return 'carga...';

        }
    }

    function getSiteLanguage() {
        var userlang = navigator.language;
        if (userlang !== null) { return userlang.split("_")[0]; }
        const cookies = document.cookie;
        const cookieArray = cookies.split(';');
        for (const cookie of cookieArray) {
            if (cookie.startsWith(' wp-wpml_current_language=')) {
                return cookie.slice(-2);
            }
        }
        return 'en';
    }

    function enableAddToCartButton(addToCartLabel = '') {
        enableElement($('.single_add_to_cart_button'));
        $('.single_add_to_cart_button').html(
            '<span id="pwp-loading" class="dashicons dashicons-update pwp-hidden"></span>' +
            addToCartLabel
        );
        hideElement($('#pwp-loading'));
    }

    function disableAddToCartButton(addToCartLabel = '') {
        disableElement($('.single_add_to_cart_button'));
        $('.single_add_to_cart_button').html(
            '<span id="pwp-loading" class="dashicons dashicons-update rotate"></span>' +
            addToCartLabel
        );
        showElement($('#pwp-loading'));
    }

    function disableUploadButton() {
        disableElement($('.upload-label'));
    }

    function enableUploadBtn() {
        enableElement($('.upload-label'));
    }

    function showCallUsTextAndButton() {
        $('.single_variation_wrap').hide();
        $('.summary p.price').hide();
        $('.add-to-cart-price').hide();
        showElement($('#call-us-btn'));
        showElement($('#call-us-price'));
        $('.summary h1.product_title.entry-title').after(
            '<p id="call-us" class="price"><span class="woocommerce-Price-amount amount">Call us for a quote at +32 3 889 32 41<span></p>'
        );
    }

    function showUnitPrice(bundleObject) {
        return;
        const {
            bundlePriceExists,
            bundlePriceWithCurrencySymbol,
            priceSuffix,
            bundleSuffix,
            individualPriceWithCurrencySymbol,
        } = bundleObject;

        // hide individual price
        $('.individual-price').addClass('pwp-hidden');
        if (!bundlePriceExists) {
            $('.add-to-cart-price span.price-amount').html(
                individualPriceWithCurrencySymbol
            );
            $('.add-to-cart-price span.woocommerce-price-suffix').html(
                priceSuffix
            );
        } else {
            $('.individual-price').removeClass('pwp-hidden');
            $('.add-to-cart-price span.price-amount').html(
                bundlePriceWithCurrencySymbol
            );
            $('.add-to-cart-price span.woocommerce-price-suffix').html(
                priceSuffix +
                '<span class="bundle-suffix">' +
                bundleSuffix +
                '</span>'
            );

            $('.individual-price span.price-amount').html(
                individualPriceWithCurrencySymbol
            );
            $('.individual-price span.woocommerce-price-suffix').html(
                priceSuffix
            );
        }
    }

    function resetUnitPrice() {
        hideElement($('.cart-unit-block'));
        $('.individual-price-text').html();
    }

    function hideArticleCodeElement() {
        hideElement($('span.article-code-container'));
    }

    function AddButtonIconClass() {
        $('.single_add_to_cart_button').addClass('pwp_customizable');
    }

    function RemoveButtonIconClass() {
        $('.single_add_to_cart_button').removeClass('pwp_customizable');
    }
})(jQuery);
