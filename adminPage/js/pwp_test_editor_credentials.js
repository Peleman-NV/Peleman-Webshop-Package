(function ($) {
    ('use strict');

    const _domainField = $('#pie_domain');
    const _customerIdField = $('#pie_customer_id');
    const _apiKeyField = $('#pie_api_key');
    const _testButton = $('#pie_api_test');

    if (_testButton) {
        _testButton.on('click', function () {
            test_api_connection();
        });
    }

    function test_api_connection() {
        var formData = new FormData();

        formData.append('domain', _domainField.val());
        formData.append('customer_id', _customerIdField.val());
        formData.append('api_key', _apiKeyField.val());
        formData.append('action', 'Ajax_verify_pie_credentials');
        statusCode = -1;

        $.ajax({
            url: Ajax_verify_pie_credentials_object.ajax_url,
            method: 'POST',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                _testButton.prop('disabled', true);
            },
            complete: function (response) {
                _testButton.prop('disabled', false);
            },
            success: function (response) {
                console.log(response.data.responseCode);
                alert(response.data.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus.statusCode);
                console.log(errorThrown);
                alert('Error making request; Try again later.');
            }

        });
    }

})(jQuery);