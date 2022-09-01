<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_PIE_Editor_Instructions;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;
use PWP\includes\utilities\PWP_Input_Fields;
use WP_Post;

class PWP_Variable_Product_Custom_Fields extends PWP_Abstract_Action_hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_after_variable_attributes', 'add_custom_fields', 11, 3);
    }

    /**
     * Undocumented function
     *
     * @param int $loop
     * @param array $variation_data
     * @param WP_Post $variation
     * @return void
     */
    public function add_custom_fields(int $loop, array $variation_data, WP_Post $variation): void
    {
        $variationId = $variation->ID;
        $wc_variation = wc_get_product($variationId);
        $editor_data = new PWP_Product_Meta_Data(wc_get_product($variationId));
        $parentId = $wc_variation->get_parent_id();

        $loopEnd = "[{$loop}]"
?>
        <div class="pwp-options-group">
            <h2 class="pwp-options-group-title">Fly2Data Properties - V2</h2>
            <div class="option_group">
                <?php

                PWP_INPUT_FIELDS::text_input(
                    "f2d_sku_components" . $loopEnd,
                    'Fly2Data SKU',
                    $wc_variation->get_meta('f2d_sku_components'),
                    '',
                    ['form-row', 'form-row-first'],
                    'F2D components that make up a variation'
                );

                PWP_INPUT_FIELDS::text_input(
                    "f2d_artcd" . $loopEnd,
                    'Fly2Data article code',
                    $wc_variation->get_meta('f2d_artcd'),
                    '',
                    ['form-row', 'form-row-last'],
                    'F2D article code'
                );

                PWP_INPUT_FIELDS::create_field(
                    PWP_Product_Meta_Data::USE_PDF_CONTENT . $loopEnd,
                    'use pdf content upload',
                    'bool',
                    $editor_data->uses_pdf_content(),
                    ['form-row', 'form-row-full', 'checkbox', 'pie-pdf_content'],
                    'whether the product requires a PDF file for its contents'
                );

                PWP_Input_Fields::text_input(
                    PWP_Product_Meta_Data::CUSTOM_LABEL . $loopEnd,
                    'Custom add to cart label',
                    $editor_data->get_custom_add_to_cart_label() ?: '',
                    'add to cart',
                    ['form-row', 'form-row-full'],
                    'custom add to cart button label for this variation'
                );

                PWP_Input_Fields::dropdown_input(
                    PWP_Product_Meta_Data::EDITOR_ID . $loopEnd,
                    "editor",
                    array(
                        '' => 'no customization',
                        PWP_Product_PIE_Data::MY_EDITOR => "Peleman Image Editor",
                        PWP_Product_IMAXEL_Data::MY_EDITOR => "Imaxel"
                    ),
                    $editor_data->get_editor_id(),
                    ['form-row', 'form-row-full', 'editor_select'],
                    'which editor to use for this product. Ensure the template and variant IDs are valid for the editor.'
                );
                ?>
            </div>
            <h3 class="ppi_options_group_title"> Image Editor Settings</h3>
            <div class="option_group">
                <?php
                PWP_INPUT_FIELDS::create_field(
                    PWP_Product_PIE_Data::TEMPLATE_ID_KEY . $loopEnd,
                    'PIE Template ID',
                    'text',
                    $editor_data->pie_data()->get_template_id(),
                    ['form-row', 'form-row-first'],
                );

                PWP_INPUT_FIELDS::create_field(
                    PWP_Product_PIE_Data::DESIGN_ID_KEY . $loopEnd,
                    'Design ID',
                    'text',
                    $editor_data->pie_data()->get_design_id(),
                    ['form-row', 'form-row-last'],
                );

                $instructions = $editor_data->pie_data()->get_editor_instructions();
                woocommerce_wp_textarea_input(array(
                    'label' => 'editor instructions',
                    'name' => PWP_PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY . $loopEnd,
                    'id' => PWP_PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY . $loopEnd,
                    'value' => implode(" ", $instructions),
                    'desc_tip' => true,
                    'description' => 'editor instruction values. for reference, see the PIE editor documentation. enter values separated by a space.',
                    'wrapper_class' => implode(' ', ['form-row', 'form-row-full']),
                ));

                PWP_INPUT_FIELDS::create_field(
                    PWP_Product_PIE_Data::COLOR_CODE_KEY . $loopEnd,
                    'Color Code',
                    'text',
                    $editor_data->pie_data()->get_color_code(),
                    ['form-row', 'form-row-first'],
                );

                PWP_INPUT_FIELDS::create_field(
                    PWP_Product_PIE_Data::BACKGROUND_ID_KEY . $loopEnd,
                    'PIE background ID',
                    'text',
                    $editor_data->pie_data()->get_background_id(),
                    ['form-row', 'form-row-last'],
                );

                PWP_INPUT_FIELDS::checkbox_input(
                    PWP_Product_PIE_Data::USE_IMAGE_UPLOAD . $loopEnd,
                    'Use Image Uploads',
                    $editor_data->pie_data()->uses_image_upload(),
                    ['form-row', 'form-row-full'],
                );

                PWP_INPUT_FIELDS::checkbox_input(
                    PWP_Product_PIE_Data::AUTOFILL . $loopEnd,
                    'autofill templage pages in editor',
                    $editor_data->pie_data()->get_autofill(),
                    ['form-row', 'form-row-full'],
                );

                PWP_INPUT_FIELDS::text_input(
                    PWP_Product_PIE_DATA::FORMAT_ID . $loopEnd,
                    'format id',
                    $editor_data->pie_data()->get_format_id(),
                    '',
                    ['form-row', 'form-row-first'],
                    'format id for the template to be filled out'
                );

                PWP_INPUT_FIELDS::number_input(
                    PWP_Product_PIE_Data::NUM_PAGES . $loopEnd,
                    'Pages to Fill',
                    $editor_data->pie_data()->get_num_pages(),
                    ['form-row', 'form-row-last'],
                    '',
                    array('min' => 0)
                );

                PWP_INPUT_FIELDS::number_input(
                    PWP_Product_PIE_Data::MIN_IMAGES . $loopEnd,
                    'Min Images for upload',
                    $editor_data->pie_data()->get_min_images(),
                    ['form-row', 'form-row-first'],
                    '',
                    array('min' => 0)
                );

                PWP_INPUT_FIELDS::number_input(
                    PWP_Product_PIE_Data::MAX_IMAGES . $loopEnd,
                    'Max Images for upload',
                    $editor_data->pie_data()->get_max_images(),
                    ['form-row', 'form-row-last'],
                    '',
                    array('min' => 0)
                );

                PWP_INPUT_FIELDS::text_input(
                    PWP_Product_IMAXEL_Data::TEMPLATE_ID_KEY . $loopEnd,
                    'IMAXEL template ID',
                    $editor_data->imaxel_data()->get_template_id(),
                    '',
                    ['form-row', 'form-row-first'],
                    'IMAXEL specific template ID'
                );

                PWP_INPUT_FIELDS::text_input(
                    PWP_Product_IMAXEL_Data::VARIANT_ID_KEY . $loopEnd,
                    'IMAXEL Variant ID',
                    $editor_data->imaxel_data()->get_variant_id(),
                    '',
                    ['form-row', 'form-row-last'],
                    'IMAXEL specific variant ID'
                );
                ?>
            </div>
        </div>
<?php
    }
}
