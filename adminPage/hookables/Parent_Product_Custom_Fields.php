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
            __('Custom add to cart label', PWP_TEXT_DOMAIN),
            $product->get_meta(Product_Meta_Data::CUSTOM_LABEL_KEY) ?: '',
            'eg. Design Project',
            ['short'],
            __('Define a custom Add to Cart label. will be the backup label for variable products', PWP_TEXT_DOMAIN)
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

        $this->open_div();
        Input_Fields::number_input(
            $meta_data::UNIT_PRICE,
            __('Unit Purchase Price', PWP_TEXT_DOMAIN),
            (string)$meta_data->get_unit_price() ?: 0.0,
            ['form-row', 'form-row-first', 'short'],
            __('Total price of a unit. a unit is composed of multiple individual items.', PWP_TEXT_DOMAIN),
            array('step' => 0.1)
        );

        Input_Fields::number_input(
            $meta_data::UNIT_AMOUNT,
            __('Unit amount', PWP_TEXT_DOMAIN),
            (string)$meta_data->get_unit_amount() ?: 1,
            ['form-row', 'form-row-last', 'short'],
            __('Amount of items per unit. ie. 1 box (unit) contains 20 cards (items).', PWP_TEXT_DOMAIN)
        );

        Input_Fields::text_input(
            $meta_data::UNIT_CODE,
            __('Unit code', PWP_TEXT_DOMAIN),
            $meta_data->get_unit_code(),
            '',
            ['form-row', 'form-row-full'],
        );


        $this->close_div();
        $this->render_PIE_product_settings($meta_data);
        // $this->render_IMAXEL_product_settings($meta_data);
        $this->render_PDF_upload_settings($meta_data);
    }

    private function render_PIE_product_settings(Product_Meta_Data $meta_data): void
    {
        Input_Fields::wp_dropdown_input(
            [
                'id'        => Product_Meta_Data::EDITOR_ID_KEY,
                'classes'   => ['pwp-editor-select', 'form-field', 'form-row-full'],
                'label'     => __("Editor", PWP_TEXT_DOMAIN),
                'options'   => [
                    'none'                      => 'no customization',
                    Product_PIE_Data::MY_EDITOR => 'Peleman Image Editor'
                ],
                'selected'  => $meta_data->get_editor_id(),
                'desc'      => __('which editor to use for this product. Ensure the template and variant IDs are valid for the editor.', PWP_TEXT_DOMAIN),
                'custom_attributes' => [
                    'target'     => 'pwp_editor_properties'
                ]
            ]
        );

        $this->open_div(['id' => 'pwp_editor_properties', 'classes' => ['pwp-hidden']]);

        Input_Fields::checkbox_input(
            Product_Meta_Data::OVERRIDE_CART_THUMB,
            __('Use project preview thumbnail in cart', PWP_TEXT_DOMAIN),
            $meta_data->get_override_thumbnail(),
            ['form-row', 'form-row-full'],
            __('wether to override the product thumbnail in the cart with a preview of the editor project, if available.', PWP_TEXT_DOMAIN)
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::PIE_TEMPLATE_ID_KEY,
            __('PIE Template ID', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_template_id(),
            '',
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::DESIGN_ID_KEY,
            __('Design ID', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_design_id(),
            '',
            [],
        );

        $instructions = $meta_data->pie_data()->get_editor_instructions();
        woocommerce_wp_textarea_input(array(
            'label' => __('Instructions', PWP_TEXT_DOMAIN),
            'name' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'id' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY,
            'value' => implode(" ", $instructions),
            'desc_tip' => true,
            'description' => __('editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.', PWP_TEXT_DOMAIN),
            'wrapper_class' => implode(" ", []),
        ));

        INPUT_FIELDS::text_input(
            Product_PIE_Data::COLOR_CODE_KEY,
            __('Color Code', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_color_code(),
            '',
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::BACKGROUND_ID_KEY,
            __('PIE background ID', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_background_id(),
            '',
            [],
        );

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::USE_IMAGE_UPLOAD_KEY,
            __('Use Image Uploads', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->uses_image_upload(),
            [],
        );

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::AUTOFILL_KEY,
            __('Autofill templage pages in editor', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_autofill(),
            [],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::FORMAT_ID_KEY,
            __('Format id', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_format_id(),
            '',
            [],
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::NUM_PAGES_KEY,
            __('Pages to Fill', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_num_pages(),
            [],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MIN_IMAGES_KEY,
            __('Min Images for upload', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_min_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MAX_IMAGES_KEY,
            __('Max Images for upload', PWP_TEXT_DOMAIN),
            $meta_data->pie_data()->get_max_images(),
            ['input-text'],
            '',
            array('min' => 0)
        );

        $this->close_div();
    }

    private function render_PDF_upload_settings(Product_Meta_Data $meta_data): void
    {
        Input_Fields::checkbox_input(
            Product_Meta_Data::USE_PDF_CONTENT_KEY,
            __('Require PDF upload', PWP_TEXT_DOMAIN),
            $meta_data->uses_pdf_content(),
            [],
            __('wether this product requires customers to upload a pdf file for contents.', PWP_TEXT_DOMAIN)
        );

        $this->open_div(['id' => 'pdf_upload_properties', 'classes' => ['pwp-hidden']]);
        Input_Fields::number_input(
            Product_Meta_Data::PDF_MIN_PAGES_KEY,
            __('PDF Min Pages', PWP_TEXT_DOMAIN),
            $meta_data->get_pdf_min_pages(),
            [],
            __('min pages allowed per PDF upload', PWP_TEXT_DOMAIN),
            array('min' => 1)
        );
        Input_Fields::number_input(
            Product_Meta_Data::PDF_MAX_PAGES_KEY,
            __('PDF Max Pages', PWP_TEXT_DOMAIN),
            $meta_data->get_pdf_max_pages(),
            [],
            __('max pages allowed per PDF upload. Leave at 0 for unlimited', PWP_TEXT_DOMAIN),
            array('min' => 0)
        );
        Input_Fields::number_input(
            Product_Meta_Data::PDF_WIDTH_KEY,
            __('PDF Format Width', PWP_TEXT_DOMAIN),
            $meta_data->get_pdf_width(),
            [],
            __('permitted width of PDF uploads in mm', PWP_TEXT_DOMAIN)
        );
        Input_Fields::number_input(
            Product_Meta_Data::PDF_HEIGHT_KEY,
            __('PDF Format Height', PWP_TEXT_DOMAIN),
            $meta_data->get_pdf_height(),
            [],
            __('permitted height of PDF uploads in mm', PWP_TEXT_DOMAIN)
        );
        //pdf price per additional page field. precision up to 3 decimal places
        Input_Fields::number_input(
            Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY,
            __('PDF price per page', PWP_TEXT_DOMAIN),
            $meta_data->get_price_per_page(),
            [],
            __('additional price per page', PWP_TEXT_DOMAIN),
            array('step' => 0.001)
        );
        $this->close_div();
    }

    private function open_div(array $args = []): void
    {
        echo ("<div");
        if (isset($args['id'])) {
            $id = $args['id'];
            echo (" id='{$id}'");
        }
        if (isset($args['classes'])) {
            $classes = implode(' ', $args['classes']);
            echo (" class='{$classes}'");
        }
        echo ">";
    }

    private function close_div(): void
    {
        echo ("</div>");
    }

    private function heading(string $text, int $importance = 1, array $classes = []): void
    {
        $importance = min(6, max(1, $importance));
        $classes = implode(' ', $classes);
        echo "<h{$importance} class='{$classes}'>{$text}</h{$importance}>";
    }
}
