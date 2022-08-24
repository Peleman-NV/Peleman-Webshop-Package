<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_IMAXEL_Data;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\editor\PWP_Product_Meta;
use PWP\includes\editor\PWP_Product_Meta_Data;
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
        $meta_data = new PWP_Product_Meta_Data($product);
        /* F2D settings */
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

        /* Editor settings */
        PWP_Input_Fields::dropdown_input(
            PWP_Product_Meta_Data::EDITOR_ID,
            "editor",
            array(
                '' => 'no customization',
                PWP_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                PWP_IMAXEL_Data::MY_EDITOR => "Imaxel"
            ),
            $meta_data->get_editor_id(),
            ['form-row', 'form-row-full', 'editor_select'],
            'which editor to use for this product. Ensure the template and variant IDs are valid for the editor.'
        );

        $this->render_PIE_product_settings($meta_data);
        $this->render_IMAXEL_product_settings($meta_data);
    }

    private function render_PIE_product_settings(PWP_Product_Meta_Data $meta_data): void
    {
        PWP_INPUT_FIELDS::text_input(
            PWP_PIE_DATA::TEMPLATE_ID_KEY,
            'PIE Template ID',
            $meta_data->pie_data()->get_template_id(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_PIE_DATA::DESIGN_ID_KEY,
            'Design ID',
            $meta_data->pie_data()->get_design_id(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_PIE_DATA::COLOR_CODE_KEY,
            'Color Code',
            $meta_data->pie_data()->get_color_code(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_PIE_DATA::BACKGROUND_ID_KEY,
            'PIE background ID',
            $meta_data->pie_data()->get_background_id(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::checkbox_input(
            PWP_PIE_DATA::USE_IMAGE_UPLOAD,
            'Use Image Uploads',
            $meta_data->pie_data()->get_uses_image_upload(),
            [],
        );

        $this->open_form_field('minmax', ' ');
        PWP_INPUT_FIELDS::number_input(
            PWP_PIE_DATA::MIN_IMAGES,
            'Min Images for upload',
            $meta_data->pie_data()->get_min_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        PWP_INPUT_FIELDS::number_input(
            PWP_PIE_DATA::MAX_IMAGES,
            'Max Images for upload',
            $meta_data->pie_data()->get_max_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );
        $this->close_form_field();
    }

    private function render_IMAXEL_product_settings(PWP_Product_Meta_Data $meta_data): void
    {
        $this->open_form_field('imaxel', 'imaxel template ids');
        PWP_INPUT_FIELDS::text_input(
            PWP_IMAXEL_DATA::TEMPLATE_ID_KEY,
            'IMAXEL template ID',
            $meta_data->imaxel_data()->get_template_id(),
            '',
            ['input-text'],
            'IMAXEL specific template ID'
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_IMAXEL_DATA::VARIANT_ID_KEY,
            'IMAXEL Variant ID',
            $meta_data->imaxel_data()->get_variant_id(),
            '',
            ['input-text'],
            'IMAXEL specific variant ID'
        );
        $this->close_form_field();
    }

    private function open_form_field(string $id, string $label_text): void
    {

        echo("<p class="form-field">
            <span class="wrap">
                <label for=<?= $id ?>><?= $label_text ?></label>"
        )
        }

        private function close_form_field(): void
        {
            ?>
        </p>
        </span>
<?php
        }
    }
