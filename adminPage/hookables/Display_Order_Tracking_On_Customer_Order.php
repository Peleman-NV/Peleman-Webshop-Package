<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Display_Order_Tracking_On_Customer_Order extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'display_order_tracking_information',
            'show_tracking_order_rows',
            $priority,
            1
        );
    }

    /**
     * Output tracking information
     */
    public function show_tracking_order_rows(\WC_Order $order)
    {
        $trackingData = $order->get_meta('f2d_tracking_data');
        $decodedTrackingData = json_decode($trackingData, true);

        foreach ($decodedTrackingData as $key => $trackingObject) {
            echo '<a style="text-decoration: underline;" href="' . sanitize_text_field($trackingObject['url']) . '" target="blank">' . sanitize_text_field($key) . '</a><br>';
        }
    }
}
