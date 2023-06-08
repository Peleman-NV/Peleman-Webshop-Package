<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Update_PIE_Project_Return_URL extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'pwp_update_pie_project_return_url',
            'update_return_url',
            $priority,
            2
        );
    }

    public function update_return_url(string $project_id, string $target_url): void
    {
        $apiKey = get_option('pie_api_key');
        $customerId = get_option('pie_customer_id');

        $url =  get_option('pie_domain');
        $url .= "/editor/api/setprojectreturnurl.php";
        $url .= "?projectid={$project_id}";

        $result = wp_remote_get($url, array('headers' => array(
            'HTTP_REFERER' => $target_url,
            "PIEAPIKEY" => $apiKey,
        )));
        error_log("set project return url results: " . print_r($result, true));

        // do_action('pwp_update_pie_project_return_url', '1256d5454', 'my.site.be/here');
    }
}
