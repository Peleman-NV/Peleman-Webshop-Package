<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Order_PIE_Project_Request;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Confirm_PIE_Project_On_Checkout extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'woocommerce_order_status_completed',
            'order_pie_projects',
            $priority,
            1
        );
    }

    public function order_pie_projects(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $request = new Order_PIE_Project_Request(
            get_option('pie_domain', 'https://deveditor.peleman.com'),
            get_option('pie_api_key', ''),
            get_option('pie_customer_id', ''),
            $order_id
        );

        error_log(print_r($request, true));

        $orderLines = $order->get_items();
        foreach ($orderLines as $key => $values) {
            $projectId = $values->get_meta('_project_id');
            if (empty($projectId)) {
                continue;
            }
            $request->add_order_line((string)$key, $values['_project_id']);
        }
        // error_log('request data: ' . print_r($request, true));
        $response = $request->make_request();

        error_log('order file response: ' . print_r($response, true));
    }
}
