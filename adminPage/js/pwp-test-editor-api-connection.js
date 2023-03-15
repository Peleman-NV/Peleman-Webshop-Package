(function ($) {
    ('use strict');

    let domainField = $('#pie_domain');
    let customerIdField = $('#pie_customer_id');
    let apiKeyField = $('#pie_api_key');
    let testButton = $('#pie_api_test');

    if (testButton) {
        testButton.on('click', function () {
            test_api_connection();
        });
    }

    async function test_api_connection() {
        let message = 'err';
        domain = domainField.val();
        customerId = customerIdField.val();
        apiKey = apiKeyField.val();
        statusCode = -1;

        if (!domain || !customerId || !apiKey) {
            ('missing credentials.');
        }

        let url = domain + `/editor/api/getcustomerbyid.php?customerid=${customerId}&customerApiKey=${apiKey}`;
        url = 'https://www.google.com';

        await fetch(url, {
            method: "GET",
            // mode: "no-cors",
            // cache: "no-cache",
            // credentials: "same-origin",
            // headers: {
                // customerApiKey: apiKey,
                // Accept: 'application/json'
            // },
            // redirect: "follow",
            // referrrerPolicy: "same-origin"
        })
            .then((response) => {
                //TODO: fix CORS errors
                // if (!response.ok) {
                //     console.log(response);
                // }
                alert(handle_status_code(response.status))
            })
            .catch((data) => {
                console.log(data);
            });

    }

    function handle_status_code(code) {
        code = parseInt(code);
        if (code < 200) {
            return 'Weird response but ok.';
        } if (code >= 200 && code < 300) {
            return 'Credentials ok.';
        } if (code >= 400 && code < 500) {
            return "Invalid credentials.";
        } if (code >= 500) {
            return "Server error; Try again later";
        }
        return "Invalid response received.";

    }

})(jQuery);