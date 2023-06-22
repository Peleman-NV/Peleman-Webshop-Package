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
        $domain =  get_option('pie_domain');
        $apiKey = get_option('pie_api_key');
        $params = array(
            'projectid' => $project_id,
            'type'      => 'setreturnurl',
            'value'     => $target_url,
        );

        $url = $domain . "/editor/api/projectfileAPI.php?";
        $url .= http_build_query($params);

        $headers = array(
            'PIEAPIKEY' => $apiKey,
            'RETURN_URL' => $target_url,
        );

        $result = wp_remote_get(
            $url,
            array('headers' => $headers)
        );

        if (is_wp_error($result)) {
            error_log($result->get_error_message());
        }
    }
}
