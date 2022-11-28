<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_IMAXEL_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use PWP\includes\utilities\Input_Fields;
use WC_Product_Simple;

/**
 * Ads PWP/PIE specific fields to a WC simple/parent product
 */
class Parent_Product_Custom_Fields extends Abstract_Action_Hookable
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
        <div class="options_group">
            <?php
            $this->render_standard_product_settings($product);
            if ($product instanceof \WC_Product_Simple)
                $this->render_simple_product_settings($product);
            ?>
        </div>
<?php
    }

    private function render_standard_product_settings(\WC_Product $product): void
    {
        Input_Fields::text_input(
            Product_Meta_Data::CUSTOM_LABEL_KEY,
            'Custom add to cart label',
            $product->get_meta(Product_Meta_Data::CUSTOM_LABEL_KEY) ?: '',
            'eg. Design Project',
            ['short'],
            'Define a custom Add to Cart label. will be the backup label for variable products'
        );
    }

    /**
     * render additional buttons for a simple product
     *
     * @param WC_Product_Simple $product
     * @return void
     */
    private function render_simple_product_settings(WC_Product_Simple $product): void
    {
        $meta_data = new Product_Meta_Data($product);

        /* F2D settings */
        // Input_Fields::checkbox_input(
        //     'call_to_order',
        //     'Call us to order',
        //     boolval($product->get_meta('call_to_order')),
        //     ['short'],
        //     'Remove Add to Cart button and display "call us to order" instead'
        // );

        Input_Fields::number_input(
            'cart_price',
            'Unit Purchase Price',
            (string)$product->get_meta('cart_price') ?: 0.0,
            ['short'],
            'These items are sold as units, not individually',
            array('step' => 0.1)
        );

        Input_Fields::number_input(
            'cart_units',
            'Unit amount',
            (string)$product->get_meta('cart_units') ?: 1,
            ['short'],
            'Amount of items per unit. ie. 1 box (unit) contains 20 cards (items).'
        );

        // Input_Fields::text_input(
        //     'unit_code',
        //     'Unit code',
        //     $product->get_meta('unit_code'),
        //     '',
        //     ['short'],
        //     'The unit code of this item'
        // );

        Input_Fields::text_input(
            'f2d_artcd',
            'F2D Article Code',
            $product->get_meta('f2d_artcd'),
            '',
            ['short'],
            'F2D article code'
        );

        /* Editor settings */
        Input_Fields::dropdown_input(
            Product_Meta_Data::EDITOR_ID_KEY,
            "editor",
            array(
                '' => 'no customization',
                Product_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                // Product_IMAXEL_Data::MY_EDITOR => "Imaxel"
            ),
            $meta_data->get_editor_id(),
            ['form-row', 'form-row-full', 'pwp-editor-select'],
            'which editor to use for this product. Ensure the template and variant IDs are valid for the editor.'
        );

        Input_Fields::checkbox_input(
            Product_Meta_Data::OVERRIDE_CART_THUMB,
            'use project preview thumbnail in cart',
            $meta_data->get_override_thumbnail(),
            ['form-row', 'form-row-full'],
            'wether to override the product thumbnail in the cart with a preview of the editor project, if available.'
        );

        $this->render_PIE_product_settings($meta_data);
        // $this->render_IMAXEL_product_settings($meta_data);
        $this->render_PDF_upload_settings($meta_data);
    }

    private function render_PIE_product_settings(Product_Meta_Data $meta_data): void
    {
        $this->open_form_div();
        INPUT_FIELDS::text_input(
            Product_PIE_Data::PIE_TEMPLATE_ID_KEY,
            'PIE Template ID',
            $meta_data->pie_data()->get_template_id(),
            '',
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::DESIGN_ID_KEY,
            'Design ID',
            $meta_data->pie_data()->get_design_id(),
            '',
            [],
        );

        $this->close_form_div();
        $this->open_form_div();

        $instructions = $meta_data->pie_data()->get_editor_instructions();
        woocommerce_wp_textarea_input(array(
            'label' => 'instructions',
            'name' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'id' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'value' => implode(" ", $instructions),
            'desc_tip' => true,
            'description' => 'editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.',
            'wrapper_class' => implode(" ", []),
        ));
        $this->close_form_div();
        $this->open_form_div();

        INPUT_FIELDS::text_input(
            Product_PIE_Data::COLOR_CODE_KEY,
            'Color Code',
            $meta_data->pie_data()->get_color_code(),
            '',
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::BACKGROUND_ID_KEY,
            'PIE background ID',
            $meta_data->pie_data()->get_background_id(),
            '',
            [],
        );
        $this->close_form_div();
        $this->open_form_div();

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::USE_IMAGE_UPLOAD_KEY,
            'Use Image Uploads',
            $meta_data->pie_data()->uses_image_upload(),
            [],
        );

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::AUTOFILL_KEY,
            'autofill templage pages in editor',
            $meta_data->pie_data()->get_autofill(),
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::FORMAT_ID_KEY,
            'format id',
            $meta_data->pie_data()->get_format_id(),
            '',
            [],
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::NUM_PAGES_KEY,
            'Pages to Fill',
            $meta_data->pie_data()->get_num_pages(),
            [],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MIN_IMAGES_KEY,
            'Min Images for upload',
            $meta_data->pie_data()->get_min_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MAX_IMAGES_KEY,
            'Max Images for upload',
            $meta_data->pie_data()->get_max_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        $this->close_form_div();
    }

    private function render_IMAXEL_product_settings(Product_Meta_Data $meta_data): void
    {
        INPUT_FIELDS::text_input(
            Product_IMAXEL_Data::IMAXEL_TEMPLATE_ID_KEY,
            'IMAXEL template ID',
            $meta_data->imaxel_data()->get_template_id(),
            '',
            ['input-text'],
            'IMAXEL specific template ID'
        );

        INPUT_FIELDS::text_input(
            Product_IMAXEL_Data::IMAXEL_VARIANT_ID_KEY,
            'IMAXEL Variant ID',
            $meta_data->imaxel_data()->get_variant_id(),
            '',
            ['input-text'],
            'IMAXEL specific variant ID'
        );
    }

    private function render_PDF_upload_settings(Product_Meta_Data $meta_data): void
    {
        Input_Fields::checkbox_input(
            Product_Meta_Data::USE_PDF_CONTENT_KEY,
            'Require PDF upload',
            $meta_data->uses_pdf_content(),
            [],
            'wether this product requires customers to upload a pdf file for contents.'
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_MIN_PAGES_KEY,
            'pdf Min Pages',
            $meta_data->get_pdf_min_pages(),
            [],
            'min pages allowed per PDF upload'
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_MAX_PAGES_KEY,
            'pdf Max Pages',
            $meta_data->get_pdf_max_pages(),
            [],
            'max pages allowed per PDF upload'
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_WIDTH_KEY,
            'pdf Format Width',
            $meta_data->get_pdf_width(),
            [],
            'permitted width of PDF uploads'
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_HEIGHT_KEY,
            'pdf Format Height',
            $meta_data->get_pdf_height(),
            [],
            'permitted height of PDF uploads'
        );

        //pdf price per additional page field. precision up to 3 decimal places
        Input_Fields::number_input(
            Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY,
            'pdf price per page',
            $meta_data->get_price_per_page(),
            [],
            'additional price per page',
            array('step' => 0.001)
        );
    }

    private function open_form_div(array $classes = []): void
    {
        $classes = implode(' ', $classes);
        echo ("<div class='options_group {$classes}'>");
    }

    private function close_form_div(): void
    {
        echo ("</div>");
    }
}
