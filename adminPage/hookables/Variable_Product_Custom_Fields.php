<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_IMAXEL_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_hookable;
use PWP\includes\utilities\Input_Fields;
use WP_Post;

class Variable_Product_Custom_Fields extends Abstract_Action_hookable
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

        $this->heading('Fly2Data properties - V2', 2, ['pwp-options-group-title']);
        $this->open_div(['pwp-options-group']);
        $this->render_standard_product_settings($meta_data);
        $this->close_div();

        $this->heading('Image Editor Settings', 2, ['pwp-options-group-title']);
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
            'Fly2Data SKU',
            $meta->get_parent()->get_meta('f2d_sku_components'),
            '',
            ['form-row', 'form-row-first'],
            'F2D components that make up a variation'
        );

        INPUT_FIELDS::text_input(
            "f2d_artcd" . $this->loopEnd,
            'Fly2Data article code',
            $meta->get_parent()->get_meta('f2d_artcd'),
            '',
            ['form-row', 'form-row-last'],
            'F2D article code'
        );

        Input_Fields::text_input(
            Keys::CUSTOM_LABEL_KEY . $this->loopEnd,
            'Custom add to cart label',
            $meta->get_custom_add_to_cart_label() ?: '',
            'add to cart',
            ['form-row', 'form-row-full'],
            'custom add to cart button label for this variation'
        );

        Input_Fields::dropdown_input(
            Keys::EDITOR_ID_KEY . $this->loopEnd,
            "editor",
            array(
                '' => 'no customization',
                Product_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                Product_IMAXEL_Data::MY_EDITOR => "Imaxel"
            ),
            $meta->get_editor_id(),
            ['form-row', 'form-row-full', 'pwp-editor-select'],
            'which editor to use for this product. Ensure the template and variant IDs are valid for the editor.'
        );

        Input_Fields::checkbox_input(
            Keys::OVERRIDE_CART_THUMBNAIL . $this->loopEnd,
            'use project preview thumbnail in cart',
            $meta->get_override_thumbnail(),
            ['form-row', 'form-row-full'],
            'wether to override the product thumbnail in the cart with a preview of the editor project, if available.'
        );
    }
    private function render_PIE_product_settings(Product_Meta_Data $meta): void
    {
        INPUT_FIELDS::create_field(
            Keys::PIE_TEMPLATE_ID_KEY . $this->loopEnd,
            'PIE Template ID',
            'text',
            $meta->pie_data()->get_template_id(),
            ['form-row', 'form-row-first'],
        );

        INPUT_FIELDS::create_field(
            Keys::DESIGN_ID_KEY . $this->loopEnd,
            'Design ID',
            'text',
            $meta->pie_data()->get_design_id(),
            ['form-row', 'form-row-last'],
        );

        $instructions = $meta->pie_data()->get_editor_instructions();
        woocommerce_wp_textarea_input(array(
            'label' => 'editor instructions',
            'name' => Keys::EDITOR_INSTRUCTIONS_KEY . $this->loopEnd,
            'id' => Keys::EDITOR_INSTRUCTIONS_KEY . $this->loopEnd,
            'value' => implode(" ", $instructions),
            'desc_tip' => true,
            'description' => 'editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.',
            'wrapper_class' => implode(' ', ['form-row', 'form-row-full']),
        ));

        INPUT_FIELDS::create_field(
            Keys::COLOR_CODE_KEY . $this->loopEnd,
            'Color Code',
            'text',
            $meta->pie_data()->get_color_code(),
            ['form-row', 'form-row-first'],
        );

        INPUT_FIELDS::create_field(
            Keys::BACKGROUND_ID_KEY . $this->loopEnd,
            'PIE background ID',
            'text',
            $meta->pie_data()->get_background_id(),
            ['form-row', 'form-row-last'],
        );

        INPUT_FIELDS::checkbox_input(
            Keys::USE_IMAGE_UPLOAD_KEY . $this->loopEnd,
            'Use Image Uploads',
            $meta->pie_data()->uses_image_upload(),
            ['form-row', 'form-row-full'],
        );

        INPUT_FIELDS::checkbox_input(
            Keys::AUTOFILL_KEY . $this->loopEnd,
            'autofill templage pages in editor',
            $meta->pie_data()->get_autofill(),
            ['form-row', 'form-row-full'],
        );

        INPUT_FIELDS::text_input(
            Keys::FORMAT_ID_KEY . $this->loopEnd,
            'format id',
            $meta->pie_data()->get_format_id(),
            '',
            ['form-row', 'form-row-first'],
            'format id for the template to be filled out'
        );

        INPUT_FIELDS::number_input(
            Keys::NUM_PAGES_KEY . $this->loopEnd,
            'Pages to Fill',
            $meta->pie_data()->get_num_pages(),
            ['form-row', 'form-row-last'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Keys::MIN_IMAGES_KEY . $this->loopEnd,
            'Min Images for upload',
            $meta->pie_data()->get_min_images(),
            ['form-row', 'form-row-first'],
            '',
            array('min' => 0)
        );

        INPUT_FIELDS::number_input(
            Keys::MAX_IMAGES_KEY . $this->loopEnd,
            'Max Images for upload',
            $meta->pie_data()->get_max_images(),
            ['form-row', 'form-row-last'],
            '',
            array('min' => 0)
        );
    }

    private function render_IMAXEL_product_settings(Product_Meta_Data $meta): void
    {
        INPUT_FIELDS::text_input(
            Keys::IMAXEL_TEMPLATE_ID_KEY . $this->loopEnd,
            'IMAXEL template ID',
            $meta->imaxel_data()->get_template_id(),
            '',
            ['form-row', 'form-row-first'],
            'IMAXEL specific template ID'
        );

        INPUT_FIELDS::text_input(
            Keys::IMAXEL_VARIANT_ID_KEY . $this->loopEnd,
            'IMAXEL Variant ID',
            $meta->imaxel_data()->get_variant_id(),
            '',
            ['form-row', 'form-row-last'],
            'IMAXEL specific variant ID'
        );
    }

    private function render_PDF_upload_settings(Product_Meta_Data $meta): void
    {
        Input_Fields::checkbox_input(
            Keys::USE_PDF_CONTENT_KEY . $this->loopEnd,
            'Require PDF upload',
            $meta->uses_pdf_content(),
            ['form-row', 'form-row-first'],
            'wether this product requires customers to upload a pdf file for contents.'
        );

        //pdf price per additional page field. precision up to 3 decimal places
        Input_Fields::number_input(
            Keys::PDF_PRICE_PER_PAGE_KEY . $this->loopEnd,
            'pdf price per page',
            $meta->get_price_per_page(),
            ['form-row', 'form-row-last'],
            'additional price per page',
            array('step' => 0.001)
        );

        Input_Fields::number_input(
            Keys::PDF_MIN_PAGES_KEY . $this->loopEnd,
            'pdf Min Pages',
            $meta->get_pdf_min_pages(),
            ['form-row', 'form-row-first'],
            'min pages allowed per PDF upload'
        );

        Input_Fields::number_input(
            Keys::PDF_MAX_PAGES_KEY . $this->loopEnd,
            'pdf Max Pages',
            $meta->get_pdf_max_pages(),
            ['form-row', 'form-row-last'],
            'max pages allowed per PDF upload'
        );

        Input_Fields::number_input(
            Keys::PDF_WIDTH_KEY . $this->loopEnd,
            'pdf Format Width',
            $meta->get_pdf_width(),
            ['form-row', 'form-row-first'],
            'permitted width of PDF uploads'
        );

        Input_Fields::number_input(
            Keys::PDF_HEIGHT_KEY . $this->loopEnd,
            'pdf Format Height',
            $meta->get_pdf_height(),
            ['form-row', 'form-row-last'],
            'permitted height of PDF uploads'
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
