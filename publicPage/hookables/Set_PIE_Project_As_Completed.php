<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

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

            $editorId = $item->get_meta('_editor_id');
            $projectId = $item->get_meta('_project_id');

            if (!($editorId && $projectId)) {
                continue;
            }

            $this->editor_set_order_as_complete($orderId, $customerId, $apiKey, $editorId, $projectId);
        }
    }

    private function editor_set_order_as_complete(int $orderId, string $customerId, string $apiKey, string $editorId, string $projectId): bool
    {
        $request = $this->generate_request_array($orderId, $customerId, $apiKey, $projectId);

        error_log(print_r($request, true));
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL             => get_option('pie_domain') . '/editor/api/addtoqueueAPI.php',
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_HEADER          => 0,
            CURLOPT_CUSTOMREQUEST   => 'GET',
            CURLOPT_POSTFIELDS      => http_build_query($request),
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        error_log("editor response: " . print_r($response, true));

        return !empty($response);
    }

    private function generate_request_array(int $orderId, string $customerId, string $apiKey, string $projectId): array
    {
        $request = array(
            'customerid' => $customerId,
            'customerapikey' => $apiKey,
            'projectid' => $projectId,
            'type' => 'default',
            "orderid" => (string)$orderId,
            'outputtype' => 'print',
        );

        return $request;
    }
}
