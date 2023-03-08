<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Display_Order_Tracking_Information extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'woocommerce_admin_order_data_after_shipping_address',
            'display_tracking_information',
            $priority,
            1
        );
    }

    /**
     * Displays custom tracking information
     *
     * @param \WC_Order $order
     * @return void
     */
    public function display_tracking_information(\WC_Order $order): void
    {
        $trackingData = $order->get_meta('f2d_tracking_data');
        echo '<h3>PWP Tracking numbers</h3>';
        if (empty($trackingData)) {
            echo 'No tracking information available';
            return;
        }
        $decodedTrackingData = json_decode($trackingData, true);

        foreach ($decodedTrackingData as $number => $trackingObject) {
            echo '<a style="text-decoration: underline;" href="' . esc_html($trackingObject['url']) . '" target="blank">' . esc_html($number) . '</a><br>';
        }
    }
}
