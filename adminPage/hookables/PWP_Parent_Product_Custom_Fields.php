<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\utilities\PWP_Input_Fields;
use WC_Product;
use WC_Product_Simple;

class PWP_Parent_Product_Custom_Fields extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_options_general_product_data', 'add_custom_fields', 11, 3);
    }
    public function add_custom_fields(): void
    {

        $product = wc_get_product(get_the_ID());
        if (!$product) return;
?>
        <div class="option_group">
            <?php
            $this->render_standard_product_settings($product);
            if ($product instanceof WC_Product_Simple)
                $this->render_simple_product_settings($product);
            ?>
        </div>
<?php
    }

    private function render_standard_product_settings(WC_Product $product): void
    {
        PWP_Input_Fields::checkbox_input(
            'customizable_product',
            'Customizable Product',
            boolval($product->get_meta('customizable_product')),
            ['short'],
            'Check if this product can be personalized with the editor'
        );

        PWP_Input_Fields::text_input(
            'custom_add_to_cart_label',
            'Custom add to cart label',
            $product->get_meta('custom_add_to_cart_label') ?: '',
            'eg. Design Project',
            ['short'],
            'Define a custom Add to Cart label'
        );

        
    }

    private function render_simple_product_settings(WC_Product_Simple $product): void
    {
        PWP_Input_Fields::checkbox_input(
            'call_to_order',
            'Call us to order',
            boolval($product->get_meta('call_to_order')),
            ['short'],
            'Remove Add to Cart button and display "call us to order" instead'
        );

        PWP_Input_Fields::number_input(
            'cart_price',
            'Unit Purchase Price',
            (string)$product->get_meta('cart_price') ?: '',
            ['short'],
            'These items are sold as units, not individually'
        );

        PWP_Input_Fields::number_input(
            'cart_units',
            'Unit amount',
            (string)$product->get_meta('cart_units') ?: '',
            ['short'],
            'Number of items per unit'
        );

        PWP_Input_Fields::text_input(
            'unit_code',
            'Unit code',
            $product->get_meta('unit_code'),
            '',
            ['short'],
            'The unit code of this item'
        );

        PWP_Input_Fields::text_input(
            'f2d_artcd',
            'F2D Article Code',
            $product->get_meta('f2d_artcd'),
            '',
            ['short'],
            'F2D article code'
        );
    }
}
