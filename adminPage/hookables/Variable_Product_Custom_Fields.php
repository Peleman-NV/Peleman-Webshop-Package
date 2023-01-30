<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use WP_Post;


/**
 * Adds PWP/PIE specific fields to a WC Variant product
 */
class Variable_Product_Custom_Fields extends Abstract_Action_Hookable
{
    private string $loopEnd = '';
    private int $loop;

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

        $this->loop = $loop;
        $this->loopEnd = "[{$loop}]";

        $this->heading(
            __('Product Settings', PWP_TEXT_DOMAIN),
            2,
            ['pwp-options-group-title']
        );
        $this->open_div(['classes' => ['pwp-options-group']]);
        $this->render_standard_product_settings($meta_data);
        $this->close_div();

        $this->heading(
            __('Image Editor Settings', PWP_TEXT_DOMAIN),
            2,
            ['pwp-options-group-title']
        );
        $this->open_div(['classes' => ['pwp-options-group']]);
        $this->render_PIE_product_settings($meta_data);
        $this->close_div();
        $this->heading(
            __('PDF Upload Settings', PWP_TEXT_DOMAIN),
            2,
            ['pwp-options-group-title']
        );
        $this->open_div(['classes' => ['pwp-options-group']]);
        $this->render_PDF_upload_settings($meta_data);
        $this->close_div();
?>
    <?php
    }

    private function render_standard_product_settings(Product_Meta_Data $meta): void
    {
        if (get_option('pwp_enable_f2d')) {
            woocommerce_wp_text_input(array(
                'id' => "f2d_sku_components" . $this->loopEnd,
                'name' => "f2d_sku_components" . $this->loopEnd,
                'label' => __('Fly2Data SKU', PWP_TEXT_DOMAIN),
                'value' => $meta->get_parent()->get_meta('f2d_sku_components'),
                'desc_tip' => true,
                'description' =>  __('F2D components that make up a variation', PWP_TEXT_DOMAIN),
                'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            ));

            woocommerce_wp_text_input(array(
                'id' => "f2d_artcd" . $this->loopEnd,
                'name' => "f2d_artcd" . $this->loopEnd,
                'label' => __('Fly2Data article code', PWP_TEXT_DOMAIN),
                'value' => $meta->get_parent()->get_meta('f2d_artcd'),
                'desc_tip' => true,
                'description' =>  __('Fly2Data article code for this variation/product', PWP_TEXT_DOMAIN),
                'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
                'placeholder'   => 'Fly2Data article code',
            ));
        }
        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::UNIT_PRICE . $this->loopEnd,
            'name' => Product_Meta_Data::UNIT_PRICE . $this->loopEnd,
            'label' => __('Unit Purchase Price', PWP_TEXT_DOMAIN),
            'value' =>  (string)$meta->get_unit_price() ?: 0,
            'desc_tip' => true,
            'description' => __('The price of the unit total that will be added to cart. This is used in conjunction with UNIT AMOUNT.', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'class' => "wc_input_price",
            'data_type' => 'price',
            'type' => 'number',
            'custom_attributes' => array('step' => 0.01),
            'placeholder' => 0.00,
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::UNIT_AMOUNT . $this->loopEnd,
            'name' => Product_Meta_Data::UNIT_AMOUNT . $this->loopEnd,
            'label' => __('Unit amount', PWP_TEXT_DOMAIN),
            'value' => (string)$meta->get_unit_amount() ?: 1,
            'desc_tip' => true,
            'description' =>  __('Amount of items per unit. ie. 1 box (unit) contains 20 cards (items).', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 1
            ),
            'placeholder' => 1
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::UNIT_CODE  . $this->loopEnd,
            'name' => Product_Meta_Data::UNIT_CODE . $this->loopEnd,
            'label' => __('Unit code', PWP_TEXT_DOMAIN),
            'value' => (string)$meta->get_unit_code(),
            'desc_tip' => true,
            'description' =>  __('The unit code for internal identification , ie. BOX, CRT, ...', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'placeholder' => 'BOX, CRT, ...'
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::CUSTOM_LABEL_KEY  . $this->loopEnd,
            'name' => Product_Meta_Data::CUSTOM_LABEL_KEY . $this->loopEnd,
            'label' => __('Custom add to cart label', PWP_TEXT_DOMAIN),
            'value' => $meta->get_custom_add_to_cart_label(),
            'desc_tip' => true,
            'description' =>  __('Custom Add To Cart label that will be displayed on the product page', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'placeholder' => 'Add to cart'
        ));
    }

    private function render_PIE_product_settings(Product_Meta_Data $meta): void
    {
        $editorToggle = 'editor_' . $this->loop;
        $uploadToggle = 'upload_' . $this->loop;
        $required = 'pie_req_' . $this->loop;

        woocommerce_wp_select(array(
            'id' => Product_Meta_Data::EDITOR_ID_KEY . $this->loopEnd,
            'name' => Product_Meta_Data::EDITOR_ID_KEY . $this->loopEnd,
            'label'     => __("Editor", PWP_TEXT_DOMAIN),
            'desc_tip' => true,
            'description' => __('Enable/disable the editor for this product/variation. Ensure the template ID is at least filled in.', PWP_TEXT_DOMAIN),
            'custom_attributes' => array(
                'foldout' => $editorToggle,
                'requires' => $required
            ),
            'options'   => [
                ''                          => 'No customization',
                Product_PIE_Data::MY_EDITOR => 'Peleman Image Editor'
            ],
            'value' => $meta->get_editor_id() ?: 'none',
            'wrapper_class' => 'form-row form-row-full pwp-form-row-padding-5',
        ));

        $this->open_div([
            'id' => $editorToggle,
            'classes' => $meta->get_editor_id() == 'PIE' ? [] : ['pwp-hidden']
        ]);

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::PIE_TEMPLATE_ID_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::PIE_TEMPLATE_ID_KEY . $this->loopEnd,
            'label' => __('Template ID', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_template_id(),
            'desc_tip' => true,
            'description' =>  __('ID of the template that will be used in the editor. This needs to correspond with the template ID defined in the editor dashboard', PWP_TEXT_DOMAIN),
            'class' => $required,
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'placeholder' => 'REQUIRED'
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::DESIGN_ID_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::DESIGN_ID_KEY . $this->loopEnd,
            'label' => __('Design ID', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_design_id(),
            'desc_tip' => true,
            'description' =>  __('The design theme that can be used in the webshop, ie. Funeral, Copyshop, ...', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'placeholder' => 'Design ID'
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::BACKGROUND_ID_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::BACKGROUND_ID_KEY . $this->loopEnd,
            'label' => __('Background ID', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_background_id(),
            'desc_tip' => true,
            'description' =>  __('The background that will be displayed in the editor. This needs to correspond with the background ID defined in the format', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'placeholder' => 'Background ID'
        ));

        // woocommerce_wp_text_input(array(
        //     'id' => Product_PIE_Data::FORMAT_ID_KEY . $this->loopEnd,
        //     'name' => Product_PIE_Data::FORMAT_ID_KEY . $this->loopEnd,
        //     'label' => __('Format ID', PWP_TEXT_DOMAIN),
        //     'value' => $meta->pie_data()->get_format_id(),
        //     'desc_tip' => true,
        //     'description' =>  __('format id for the template to be filled out', PWP_TEXT_DOMAIN),
        //     'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
        // ));

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::COLOR_CODE_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::COLOR_CODE_KEY . $this->loopEnd,
            'label' => __('Color code', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_color_code(),
            'desc_tip' => true,
            'description' =>  __('The color code of this product/variation to use the corresponding background inside the editor. This needs to correspond with the color code defined in the format', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'placeholder' => 'Color code'
        ));

        woocommerce_wp_checkbox(array(
            'id'    => Product_PIE_Data::USE_IMAGE_UPLOAD_KEY . $this->loopEnd,
            'name'  => Product_PIE_Data::USE_IMAGE_UPLOAD_KEY . $this->loopEnd,
            'label' => __('Use Image Uploads', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->uses_image_upload() ? 'yes' : 'no',
            'desc_tip' => true,
            'description' => __('Require image uploads before you enter the editor. These images will be used to fill in placeholders, ie. a photobook', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'custom_attributes' => array('foldout' => $uploadToggle),
        ));

        $this->open_div([
            'id' => $uploadToggle,
            'classes' => $meta->pie_data()->uses_image_upload() ? [] : ['pwp-hidden']
        ]);

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::NUM_PAGES_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::NUM_PAGES_KEY . $this->loopEnd,
            'label' => __('Pages to Fill', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_num_pages(),
            'desc_tip' => true,
            'description' =>  __('Number of pages to fill in, this will be used for templates that have multiple pages, ie. a photobook', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 0
            ),
            'placeholder' => 0
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::MIN_IMAGES_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::MIN_IMAGES_KEY . $this->loopEnd,
            'label' => __('Min Images for upload', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_min_images(),
            'desc_tip' => true,
            'description' =>  __('Minimum images that users are required to upload', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 0
            ),
            'placeholder' => 0
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_PIE_Data::MAX_IMAGES_KEY . $this->loopEnd,
            'name' => Product_PIE_Data::MAX_IMAGES_KEY . $this->loopEnd,
            'label' => __('Max images for upload', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_max_images(),
            'desc_tip' => true,
            'description' =>  __('Maximum images that users are required to upload', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 0
            ),
            'placeholder' => 0
        ));

        woocommerce_wp_checkbox(array(
            'id'    => Product_PIE_Data::AUTOFILL_KEY . $this->loopEnd,
            'name'  => Product_PIE_Data::AUTOFILL_KEY . $this->loopEnd,
            'label' => __('Autofill template pages in editor', PWP_TEXT_DOMAIN),
            'value' => $meta->pie_data()->get_autofill() ? 'yes' : 'no',
            'desc_tip' => true,
            'description' => __('Autofill the template pages inside the editor', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
        ));

        $this->close_div();

        woocommerce_wp_checkbox(array(
            'id'    => Product_Meta_Data::OVERRIDE_CART_THUMB . $this->loopEnd,
            'name'  => Product_Meta_Data::OVERRIDE_CART_THUMB . $this->loopEnd,
            'label' => __('Use project preview thumbnail in cart', PWP_TEXT_DOMAIN),
            'value' => $meta->get_override_thumbnail() ? 'yes' : 'no',
            'desc_tip' => true,
            'description' => __('Show a preview of the project when the product is added to the cart', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
        ));

        $this->render_editor_instructions($meta);
        $this->close_div();
    }

    private function render_editor_instructions(Product_Meta_Data $meta): void
    {
    ?>
        <div class="pwp-options-header">Editor Instructions</div>
<?php
        $this->open_div();
        $instructions = $meta->pie_data()->get_editor_instructions();
        $index = 0;
        foreach ($instructions as $key => $instruction) {
            woocommerce_wp_checkbox(array(
                'id' => $key . $this->loopEnd,
                'name' => $key . $this->loopEnd,
                'label' => $instruction->get_label(),
                'value' => $instruction->is_enabled() ? 'yes' : 'no',
                'desc_tip' => true,
                'description' => $instruction->get_description(),
                'wrapper_class' => 'form-row-multi-3 pwp-form-row-padding-5',
            ));
            $index++;
        }
        $this->close_div();
    }

    private function render_PDF_upload_settings(Product_Meta_Data $meta): void
    {
        $editorToggle = 'pdf_required_' . $this->loop;
        $required = 'pdf_req_' . $this->loop;

        woocommerce_wp_checkbox(array(
            'id'    => Product_Meta_Data::USE_PDF_CONTENT_KEY . $this->loopEnd,
            'name'  => Product_Meta_Data::USE_PDF_CONTENT_KEY . $this->loopEnd,
            'label' => __('Require PDF upload', PWP_TEXT_DOMAIN),
            'value' => $meta->uses_pdf_content() ? 'yes' : 'no',
            'desc_tip' => true,
            'description' => __('Enable/disable PDF upload for this product/variation', PWP_TEXT_DOMAIN),
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'custom_attributes' => array('foldout' => $editorToggle, 'requires' => $required)
        ));

        $this->open_div(array(
            'id' => $editorToggle,
            'classes' => $meta->uses_pdf_content() ? [] : ['pwp-hidden']
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY . $this->loopEnd,
            'name' => Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY . $this->loopEnd,
            'label' => __('PDF price per page', PWP_TEXT_DOMAIN),
            'value' => $meta->get_price_per_page(),
            'desc_tip' => true,
            'description' => __('Additional price per page that will be added to product/variation price', PWP_TEXT_DOMAIN),
            'class' => "{$required} wc_input_price",
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'data_type' => 'price',
            'type' => 'number',
            'custom_attributes' => array('step' => 0.001, 'min' => 0.000),
            'placeholder' => '0.000'
        ));


        woocommerce_wp_text_input(array(
            'id'            => Product_Meta_Data::PDF_MIN_PAGES_KEY . $this->loopEnd,
            'name'          => Product_Meta_Data::PDF_MIN_PAGES_KEY . $this->loopEnd,
            'label'         => __('PDF Min Pages', PWP_TEXT_DOMAIN),
            'value'         => $meta->get_pdf_min_pages() ?: 1,
            'desc_tip'      => true,
            'description'   =>  __('Minimum number of pages required for PDF upload', PWP_TEXT_DOMAIN),
            'class' => $required,
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'type'          => 'number',
            'custom_attributes' => array(
                'step'  => 1,
                'min'   => 1,
                'max'   => 1000
            ),
            'placeholder' => 1
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::PDF_MAX_PAGES_KEY . $this->loopEnd,
            'name' => Product_Meta_Data::PDF_MAX_PAGES_KEY . $this->loopEnd,
            'label' => __('PDF Max Pages', PWP_TEXT_DOMAIN),
            'value' => $meta->get_pdf_max_pages() ?: 1,
            'desc_tip' => true,
            'description' =>  __('Maximum number of pages allowed for PDF upload', PWP_TEXT_DOMAIN),
            'class' => $required,
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 1,
                'max' => 1000
            ),
            'placeholder' => 1
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::PDF_WIDTH_KEY . $this->loopEnd,
            'name' => Product_Meta_Data::PDF_WIDTH_KEY . $this->loopEnd,
            'label' => __('PDF Format Width (mm)', PWP_TEXT_DOMAIN),
            'value' => $meta->get_pdf_width() ?: 1,
            'desc_tip' => true,
            'description' =>  __('permitted width of PDF uploads in mm', PWP_TEXT_DOMAIN),
            'class' => $required,
            'wrapper_class' => 'form-row form-row-first pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 1
            ),
            'placeholder' => 210
        ));

        woocommerce_wp_text_input(array(
            'id' => Product_Meta_Data::PDF_HEIGHT_KEY . $this->loopEnd,
            'name' => Product_Meta_Data::PDF_HEIGHT_KEY . $this->loopEnd,
            'label' => __('PDF Format Height (mm)', PWP_TEXT_DOMAIN),
            'value' => $meta->get_pdf_height() ?: 1,
            'desc_tip' => true,
            'description' =>  __('permitted height of PDF uploads in mm', PWP_TEXT_DOMAIN),
            'class' => $required,
            'wrapper_class' => 'form-row form-row-last pwp-form-row-padding-5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 1,
                'min' => 1
            ),
            'placeholder' => 297
        ));

        $this->close_div();
    }

    private function open_div(array $args = []): void
    {
        $classes = isset($args['classes']) ? implode(' ', $args['classes']) : '';
        $id = isset($args['id']) ? $args['id'] : '';

        echo '<div id ="' . esc_attr($id) . '" class="' . esc_attr($classes) . '">';
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
