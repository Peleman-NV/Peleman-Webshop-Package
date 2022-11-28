<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\editor\Product_IMAXEL_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use PWP\includes\utilities\Input_Fields;
use WP_Post;


/**
 * Adss PWP/PIE specific fields to a WC Variant product
 */
class Variable_Product_Custom_Fields extends Abstract_Action_Hookable
{
    private string $loopEnd = '';

    public function __construct()
    {
        parent::__construct('woocommerce_product_after_variable_attributes', 'render_custom_fields', 11, 3);
    }

    /**
     * Undocumented function
     *
     * @param int $loop
     * @param array $variation_data
     * @param WP_Post $variation
     * @return void
     */
    public function render_custom_fields(int $loop, array $variation_data, WP_Post $variation): void
    {
        $variationId = $variation->ID;
        $wc_variation = wc_get_product($variationId);
        $meta_data = new Product_Meta_Data(wc_get_product($variationId));
        $parentId = $wc_variation->get_parent_id();

        $this->loopEnd = "[{$loop}]";

        $this->heading(
            __('Peleman Webshop Properties', PWP_TEXT_DOMAIN),
            2,
            ['pwp-options-group-title']
        );
        $this->open_div(['pwp-options-group']);
        $this->render_standard_product_settings($meta_data);
        $this->close_div();

        $this->heading(
            __('Image Editor Settings', PWP_TEXT_DOMAIN),
            2,
            ['pwp-options-group-title']
        );
        $this->open_div(['pwp-options-group']);
        $this->render_PIE_product_settings($meta_data);
        // $this->render_IMAXEL_product_settings($meta_data);
        $this->render_PDF_upload_settings($meta_data);
        $this->close_div();
    }

    private function render_standard_product_settings(Product_Meta_Data $meta): void
    {
        INPUT_FIELDS::text_input(
            "f2d_sku_components" . $this->loopEnd,
            __('Fly2Data SKU', PWP_TEXT_DOMAIN),
            $meta->get_parent()->get_meta('f2d_sku_components'),
            '',
            ['form-row', 'form-row-first'],
            __('F2D components that make up a variation', PWP_TEXT_DOMAIN)
        );

        INPUT_FIELDS::text_input(
            "f2d_artcd" . $this->loopEnd,
            __('Fly2Data article code', PWP_TEXT_DOMAIN),
            $meta->get_parent()->get_meta('f2d_artcd'),
            '',
            ['form-row', 'form-row-last'],
            __('F2D article code', PWP_TEXT_DOMAIN)
        );

        Input_Fields::number_input(
            Product_Meta_Data::UNIT_PRICE . $this->loopEnd,
            __('Unit Purchase Price', PWP_TEXT_DOMAIN),
            (string)$meta->get_cart_price() ?: 0,
            ['form-row', 'form-row-first'],
            __('These items are sold as units, not individually', PWP_TEXT_DOMAIN),
            array(
                'step' => '0.1'
            )
        );

        Input_Fields::number_input(
            Product_Meta_Data::UNIT_AMOUNT . $this->loopEnd,
            __('Unit amount', PWP_TEXT_DOMAIN),
            (string)$meta->get_cart_units() ?: 1,
            ['form-row', 'form-row-last'],
            __('Amount of items per unit. ie. 1 box (unit) contains 20 cards (items).', PWP_TEXT_DOMAIN),
            array('min' => 1)
        );


        Input_Fields::text_input(
            Product_Meta_Data::CUSTOM_LABEL_KEY . $this->loopEnd,
            __('Custom add to cart label', PWP_TEXT_DOMAIN),
            $meta->get_custom_add_to_cart_label() ?: '',
            'add to cart',
            ['form-row', 'form-row-full'],
            __('custom add to cart button label for this variation', PWP_TEXT_DOMAIN)
        );

        Input_Fields::dropdown_input(
            Product_Meta_Data::EDITOR_ID_KEY . $this->loopEnd,
            __("editor", PWP_TEXT_DOMAIN),
            array(
                '' => __('no customization', PWP_TEXT_DOMAIN),
                Product_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                // Product_IMAXEL_Data::MY_EDITOR => "Imaxel"
            ),
            $meta->get_editor_id(),
            ['form-row', 'form-row-full', 'pwp-editor-select'],
            __('Which editor to use for this product. Ensure the template and variant IDs are valid for the editor.', PWP_TEXT_DOMAIN)
        );

        Input_Fields::checkbox_input(
            Product_Meta_Data::OVERRIDE_CART_THUMB . $this->loopEnd,
            __('use project preview thumbnail in cart', PWP_TEXT_DOMAIN),
            $meta->get_override_thumbnail(),
            ['form-row', 'form-row-full'],
            __('Whether to override the product thumbnail in the cart with a preview of the editor project, if available.', PWP_TEXT_DOMAIN)
        );
    }
    private function render_PIE_product_settings(Product_Meta_Data $meta): void
    {
        INPUT_FIELDS::create_field(
            Product_PIE_Data::PIE_TEMPLATE_ID_KEY . $this->loopEnd,
            __('PIE Template ID', PWP_TEXT_DOMAIN),
            'text',
            $meta->pie_data()->get_template_id(),
            ['form-row', 'form-row-first'],
        );

        INPUT_FIELDS::create_field(
            Product_PIE_Data::DESIGN_ID_KEY . $this->loopEnd,
            __('Design ID', PWP_TEXT_DOMAIN),
            'text',
            $meta->pie_data()->get_design_id(),
            ['form-row', 'form-row-last'],
        );

        $instructions = $meta->pie_data()->get_editor_instructions();
        woocommerce_wp_textarea_input(array(
            'label' => __('editor instructions', PWP_TEXT_DOMAIN),
            'name' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY . $this->loopEnd,
            'id' => PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY . $this->loopEnd,
            'value' => implode(" ", $instructions),
            'desc_tip' => true,
            'description' => __('editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.', PWP_TEXT_DOMAIN),
            'wrapper_class' => implode(' ', ['form-row', 'form-row-full']),
        ));

        INPUT_FIELDS::create_field(
            Product_PIE_Data::COLOR_CODE_KEY . $this->loopEnd,
            __('Color Code', PWP_TEXT_DOMAIN),
            'text',
            $meta->pie_data()->get_color_code(),
            ['form-row', 'form-row-first'],
        );

        INPUT_FIELDS::create_field(
            Product_PIE_Data::BACKGROUND_ID_KEY . $this->loopEnd,
            __('PIE background ID', PWP_TEXT_DOMAIN),
            'text',
            $meta->pie_data()->get_background_id(),
            ['form-row', 'form-row-last'],
        );

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::USE_IMAGE_UPLOAD_KEY . $this->loopEnd,
            __('Use Image Uploads', PWP_TEXT_DOMAIN),
            $meta->pie_data()->uses_image_upload(),
            ['form-row', 'form-row-full'],
        );

        INPUT_FIELDS::checkbox_input(
            Product_PIE_Data::AUTOFILL_KEY . $this->loopEnd,
            __('autofill templage pages in editor', PWP_TEXT_DOMAIN),
            $meta->pie_data()->get_autofill(),
            ['form-row', 'form-row-full'],
        );

        INPUT_FIELDS::text_input(
            Product_PIE_Data::FORMAT_ID_KEY . $this->loopEnd,
            __('format id', PWP_TEXT_DOMAIN),
            $meta->pie_data()->get_format_id(),
            '',
            ['form-row', 'form-row-first'],
            __('format id for the template to be filled out', PWP_TEXT_DOMAIN)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::NUM_PAGES_KEY . $this->loopEnd,
            __('Pages to Fill', PWP_TEXT_DOMAIN),
            $meta->pie_data()->get_num_pages(),
            ['form-row', 'form-row-last'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MIN_IMAGES_KEY . $this->loopEnd,
            __('Min Images for upload', PWP_TEXT_DOMAIN),
            $meta->pie_data()->get_min_images(),
            ['form-row', 'form-row-first'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Product_PIE_Data::MAX_IMAGES_KEY . $this->loopEnd,
            __('Max Images for upload', PWP_TEXT_DOMAIN),
            $meta->pie_data()->get_max_images(),
            ['form-row', 'form-row-last'],
            '',
            array('min' => 0)
        );
    }

    private function render_IMAXEL_product_settings(Product_Meta_Data $meta): void
    {
        INPUT_FIELDS::text_input(
            Product_IMAXEL_Data::IMAXEL_TEMPLATE_ID_KEY . $this->loopEnd,
            __('IMAXEL template ID', PWP_TEXT_DOMAIN),
            $meta->imaxel_data()->get_template_id(),
            '',
            ['form-row', 'form-row-first'],
            __('IMAXEL specific template ID', PWP_TEXT_DOMAIN)
        );

        INPUT_FIELDS::text_input(
            Product_IMAXEL_Data::IMAXEL_VARIANT_ID_KEY . $this->loopEnd,
            __('IMAXEL Variant ID', PWP_TEXT_DOMAIN),
            $meta->imaxel_data()->get_variant_id(),
            '',
            ['form-row', 'form-row-last'],
            __('IMAXEL specific variant ID', PWP_TEXT_DOMAIN)
        );
    }

    private function render_PDF_upload_settings(Product_Meta_Data $meta): void
    {
        Input_Fields::checkbox_input(
            Product_Meta_Data::USE_PDF_CONTENT_KEY . $this->loopEnd,
            __('Require PDF upload', PWP_TEXT_DOMAIN),
            $meta->uses_pdf_content(),
            ['form-row', 'form-row-first'],
            __('Whether this product requires customers to upload a pdf file for contents.', PWP_TEXT_DOMAIN)
        );

        //pdf price per additional page field. precision up to 3 decimal places
        Input_Fields::number_input(
            Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY . $this->loopEnd,
            __('pdf price per page', PWP_TEXT_DOMAIN),
            $meta->get_price_per_page(),
            ['form-row', 'form-row-last'],
            __('additional price per page', PWP_TEXT_DOMAIN),
            array('step' => 0.001)
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_MIN_PAGES_KEY . $this->loopEnd,
            __('pdf Min Pages', PWP_TEXT_DOMAIN),
            $meta->get_pdf_min_pages(),
            ['form-row', 'form-row-first'],
            __('min pages allowed per PDF upload', PWP_TEXT_DOMAIN)
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_MAX_PAGES_KEY . $this->loopEnd,
            __('pdf Max Pages', PWP_TEXT_DOMAIN),
            $meta->get_pdf_max_pages(),
            ['form-row', 'form-row-last'],
            __('max pages allowed per PDF upload', PWP_TEXT_DOMAIN)
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_WIDTH_KEY . $this->loopEnd,
            __('pdf Format Width', PWP_TEXT_DOMAIN),
            $meta->get_pdf_width(),
            ['form-row', 'form-row-first'],
            __('permitted width of PDF uploads', PWP_TEXT_DOMAIN)
        );

        Input_Fields::number_input(
            Product_Meta_Data::PDF_HEIGHT_KEY . $this->loopEnd,
            __('pdf Format Height', PWP_TEXT_DOMAIN),
            $meta->get_pdf_height(),
            ['form-row', 'form-row-last'],
            __('permitted height of PDF uploads', PWP_TEXT_DOMAIN)
        );
    }

    private function open_div(array $classes = []): void
    {
        if ($classes) {
            $classes = implode(' ', $classes);
            echo ("<div class='{$classes}'>");
            return;
        }
        echo ('<div>');
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
