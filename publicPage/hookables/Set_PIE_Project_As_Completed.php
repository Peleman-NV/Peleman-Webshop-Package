<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Complete_PIE_Project_Request;
use PWP\includes\exceptions\Invalid_Response_Exception;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Set_PIE_Project_As_Completed extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_order_status_changed', 'pwp_set_pie_order_as_completed');
    }

    public function pwp_set_pie_order_as_completed(int $orderId)
    {
        $order = wc_get_order($orderId);
        $status = $order->get_status();

        if ('completed' !== $status && 'processing' !== $status) {
            return;
        }

        $items = $order->get_items();

        $customerId =  get_option('pie_customer_id', '');
        $apiKey = get_option('pie_api_key', '');

        foreach ($items as $item) {

            $projectId = $item->get_meta('_project_id');

            if (!($projectId)) {
                continue;
            }

            $this->editor_set_order_as_complete($orderId, $customerId, $apiKey, $projectId);
        }
    }

    private function editor_set_order_as_complete(int $orderId, string $customerId, string $apiKey, string $projectId): bool
    {
        try {
            $request = new Complete_PIE_Project_Request(get_option('pie_domain'), $apiKey, $customerId);
            $request
                ->set_order_id((string)$orderId)
                ->set_output_type('print');

            $response = $request->make_request();

            error_log("editor response: " . print_r($response, true));

            return !empty($response);
        } catch (Invalid_Response_Exception $exception) {
            error_log(__("an error occurred trying to complete order {$orderId}; project id {$projectId}", PWP_TEXT_DOMAIN));
        }
    }
}
