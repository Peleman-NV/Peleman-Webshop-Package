<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Display_F2D_Customer_Number_Field extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'woocommerce_admin_order_data_after_billing_address',
            'display_f2d_customer_number_div',
            $priority
        );
    }

    public function display_f2d_customer_number_div(\WC_Order $order)
    {
        $currentF2dCustomerNumber = esc_html(get_user_meta($order->get_user_id(), 'f2d_custnr', true));

        $f2dCustomerNumberField = '<p class="form-field form-field-wide"></p>'
            . '<label for="f2d_cust">F2D customer number:</label>'
            . '<div>'
            . '<input type="text" name="f2d_cust" id="f2d_cust" value="' . $currentF2dCustomerNumber . '"  style="display: inline !important;">'
            . '<div style="display: inline !important;">'
            . '<button id="save-f2d-custnr" class="button button-primary">'
            . __('Save to User')
            . '<span id="pwp-admin-loading" class="dashicons dashicons-update rotate pwp-hidden"></span>'
            . '</button>'
            . '</div>'
            . '<div id="f2d-error" class="pwp-hidden"></div>'
            . '</div>';

        echo $f2dCustomerNumberField;
    }
}
