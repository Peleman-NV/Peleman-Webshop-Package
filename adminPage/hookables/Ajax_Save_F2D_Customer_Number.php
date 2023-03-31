<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Ajax_Hookable;

class Ajax_Save_F2D_customer_Number extends Abstract_Ajax_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'pwp_save_f2d_nr',
            plugins_url('..\js\pwp_order_f2d_nr.js', __FILE__),
            $priority
        );
        $this->set_admin(true);
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    public function callback(): void
    {
        $orderNumber = sanitize_text_field($_POST['orderNumber']);
        $fly2DataCustomerNumber = intval(sanitize_text_field($_POST['fly2DataCustomerNumber']));

        error_log('hi!');
        error_log(print_r($fly2DataCustomerNumber, true));
        $order = wc_get_order($orderNumber);
        if (!$order) {
            $response['status'] = 'error';
            $response['message'] = 'No order found for ' . $orderNumber;
            wp_send_json($response);
            wp_die();
        }

        $orderUserId = $order->get_user_id();

        try {
            update_user_meta($orderUserId, 'f2d_custnr', $fly2DataCustomerNumber);
        } catch (\Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = "Error saving {$fly2DataCustomerNumber} to user {$orderUserId}";
            $response['error'] = $th->getMessage();
            wp_send_json($response);
            wp_die();
        }

        $response['status'] = 'success';
        $response['user'] = $orderUserId;
        wp_send_json($response);
        wp_die();
    }
}
