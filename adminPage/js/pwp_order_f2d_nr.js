/**
 * This is the JS code to pass a Fly2Data customer number to the backend to be saved,
 * and handle the response
 */
(function ($) {
    ('use strict');
    $('#save-f2d-custnr').on('click', function (e) {
        e.preventDefault();
        console.log("beep");
        resetUI();
        const orderNumber = $('#post_ID').val();
        const fly2DataCustomerNumber = $('#f2d_cust').val();
        const pattern = /[A-Za-z]/;

        if (fly2DataCustomerNumber === '') {
            displayWarning('F2D Customer number cannot be empty');
        } else if (pattern.test(fly2DataCustomerNumber)) {
            displayWarning('F2D Customer number can only contains digits');
        } else {
            showLoadingAnimation();
            const data = {
                action: 'pwp_save_f2d_nr',
                orderNumber: orderNumber,
                fly2DataCustomerNumber: fly2DataCustomerNumber,
            };

            $.ajax({
                url: pwp_save_f2d_nr_object.ajax_url,
                method: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                success: function (response) {
                    console.log('Success: ', response);
                    if (response.status === 'success') {
                        showResultIcon('success');
                    } else {
                        showResultIcon('error');
                        displayWarning(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    HandleErrorResponse(showResultIcon,
                        jqXHR,
                        textStatus,
                        errorThrown,
                        displayWarning);
                },
            });
        }
    });

    function displayWarning(message) {
        $('#f2d-error').removeClass('pwp-hidden');
        $('#f2d-error').text(message);
        console.log(message);
    }

    function resetUI() {
        $('#f2d-custnr-saved').remove();
        $('#f2d-error').addClass('pwp-hidden');
        $('#f2d-error').text('');
    }

    function showLoadingAnimation() {
        $('#pwp-admin-loading').removeClass('pwp-hidden');
    }

    function hideLoadingAnimation() {
        $('#pwp-admin-loading').addClass('pwp-hidden');
    }

    function showResultIcon(result) {
        hideLoadingAnimation();
        if (result === 'success') {
            $('#pwp-admin-loading').after(
                '<span id="f2d-custnr-saved" class="dashicons dashicons-yes"></span>'
            );
        }
        if (result === 'error') {
            $('#pwp-admin-loading').after(
                '<span id="f2d-custnr-saved" class="dashicons dashicons-no-alt"></span>'
            );
        }
    }

    function HandleErrorResponse(showResultIcon, jqXHR, textStatus, errorThrown, displayWarning) {
        showResultIcon('error');
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
        displayWarning('error');
    };
})(jQuery);



