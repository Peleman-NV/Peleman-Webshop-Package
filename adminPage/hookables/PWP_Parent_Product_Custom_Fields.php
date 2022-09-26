<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_PIE_Editor_Instructions;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
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
        <div class="options_group">
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
        // PWP_Input_Fields::checkbox_input(
        //     'customizable_product',
        //     'Customizable Product',
        //     boolval($product->get_meta('customizable_product')),
        //     ['short'],
        //     'Check if this product can be personalized with the editor'
        // );

        PWP_Input_Fields::text_input(
            PWP_Product_Meta_Data::CUSTOM_LABEL,
            'Custom add to cart label',
            $product->get_meta(PWP_Product_Meta_Data::CUSTOM_LABEL) ?: '',
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
        $meta_data = new PWP_Product_Meta_Data($product);

        /* F2D settings */
        // PWP_Input_Fields::checkbox_input(
        //     'call_to_order',
        //     'Call us to order',
        //     boolval($product->get_meta('call_to_order')),
        //     ['short'],
        //     'Remove Add to Cart button and display "call us to order" instead'
        // );

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
                PWP_Product_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                PWP_Product_IMAXEL_Data::MY_EDITOR => "Imaxel"
            ),
            $meta_data->get_editor_id(),
            ['form-row', 'form-row-full', 'pwp-editor-select'],
            'which editor to use for this product. Ensure the template and variant IDs are valid for the editor.'
        );

        $this->render_PIE_product_settings($meta_data);
        $this->render_IMAXEL_product_settings($meta_data);
        $this->render_PDF_upload_settings($meta_data);
    }

    private function render_PIE_product_settings(PWP_Product_Meta_Data $meta_data): void
    {
        $this->open_form_div();
        PWP_INPUT_FIELDS::text_input(
            PWP_Product_PIE_Data::TEMPLATE_ID_KEY,
            'PIE Template ID',
            $meta_data->pie_data()->get_template_id(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_Product_PIE_Data::DESIGN_ID_KEY,
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
            'name' => PWP_PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'id' => PWP_PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'value' => implode(" ", $instructions),
            'desc_tip' => true,
            'description' => 'editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.',
            'wrapper_class' => implode(" ", []),
        ));
        $this->close_form_div();
        $this->open_form_div();

        PWP_INPUT_FIELDS::text_input(
            PWP_Product_PIE_Data::COLOR_CODE_KEY,
            'Color Code',
            $meta_data->pie_data()->get_color_code(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_Product_PIE_Data::BACKGROUND_ID_KEY,
            'PIE background ID',
            $meta_data->pie_data()->get_background_id(),
            '',
            [],
        );
        $this->close_form_div();
        $this->open_form_div();

        PWP_INPUT_FIELDS::checkbox_input(
            PWP_Product_PIE_Data::USE_IMAGE_UPLOAD,
            'Use Image Uploads',
            $meta_data->pie_data()->uses_image_upload(),
            [],
        );

        PWP_INPUT_FIELDS::checkbox_input(
            PWP_Product_PIE_Data::AUTOFILL,
            'autofill templage pages in editor',
            $meta_data->pie_data()->get_autofill(),
            [],
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_Product_PIE_DATA::FORMAT_ID,
            'format id',
            $meta_data->pie_data()->get_format_id(),
            '',
            [],
        );

        PWP_INPUT_FIELDS::number_input(
            PWP_Product_PIE_Data::NUM_PAGES,
            'Pages to Fill',
            $meta_data->pie_data()->get_num_pages(),
            [],
            '',
            array('min' => 0)
        );

        PWP_INPUT_FIELDS::number_input(
            PWP_Product_PIE_Data::MIN_IMAGES,
            'Min Images for upload',
            $meta_data->pie_data()->get_min_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        PWP_INPUT_FIELDS::number_input(
            PWP_Product_PIE_Data::MAX_IMAGES,
            'Max Images for upload',
            $meta_data->pie_data()->get_max_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        $this->close_form_div();
    }

    private function render_IMAXEL_product_settings(PWP_Product_Meta_Data $meta_data): void
    {
        PWP_INPUT_FIELDS::text_input(
            PWP_Product_IMAXEL_Data::TEMPLATE_ID_KEY,
            'IMAXEL template ID',
            $meta_data->imaxel_data()->get_template_id(),
            '',
            ['input-text'],
            'IMAXEL specific template ID'
        );

        PWP_INPUT_FIELDS::text_input(
            PWP_Product_IMAXEL_Data::VARIANT_ID_KEY,
            'IMAXEL Variant ID',
            $meta_data->imaxel_data()->get_variant_id(),
            '',
            ['input-text'],
            'IMAXEL specific variant ID'
        );
    }

    private function render_PDF_upload_settings(PWP_Product_Meta_Data $meta_data): void
    {
        PWP_Input_Fields::checkbox_input(
            PWP_Product_Meta_Data::USE_PDF_CONTENT,
            'Require PDF upload',
            $meta_data->uses_pdf_content(),
            [],
            'wether this product requires customers to upload a pdf file for contents.'
        );

        PWP_Input_Fields::number_input(
            PWP_PRODUCT_META_DATA::PDF_MIN_PAGES,
            'pdf Min Pages',
            $meta_data->get_pdf_min_pages(),
            [],
            'min pages allowed per PDF upload'
        );

        PWP_Input_Fields::number_input(
            PWP_PRODUCT_META_DATA::PDF_MAX_PAGES,
            'pdf Max Pages',
            $meta_data->get_pdf_max_pages(),
            [],
            'max pages allowed per PDF upload'
        );

        PWP_Input_Fields::number_input(
            PWP_PRODUCT_META_DATA::PDF_HEIGHT,
            'pdf Format Height',
            $meta_data->get_pdf_height(),
            [],
            'permitted height of PDF uploads'
        );

        PWP_Input_Fields::number_input(
            PWP_PRODUCT_META_DATA::PDF_WIDTH,
            'pdf Format Width',
            $meta_data->get_pdf_width(),
            [],
            'permitted width of PDF uploads'
        );

        //pdf price per additional page field. precision up to 3 decimal places
        PWP_Input_Fields::number_input(
            PWP_PRODUCT_META_DATA::PDF_PRICE_PER_PAGE,
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
